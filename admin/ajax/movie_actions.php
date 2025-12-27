<?php
session_start();
require "../config/connection.php";
require "../includes/admin_auth.php";

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$response = ['status' => 'error', 'msg' => 'Invalid action'];

try {
    switch($action) {
        case 'delete_movie':
            $movie_id = intval($_POST['movie_id'] ?? 0);
            
            if ($movie_id <= 0) {
                $response = ['status' => 'error', 'msg' => 'Movie ID tidak valid'];
                break;
            }
            
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Get poster path first
                $q = $conn->prepare("SELECT poster_path FROM movies WHERE movie_id = ?");
                $q->bind_param("i", $movie_id);
                $q->execute();
                $res = $q->get_result()->fetch_assoc();
                $poster = $res['poster_path'] ?? null;
                $q->close();
                
                // Delete from movie_genre pivot table
                $d1 = $conn->prepare("DELETE FROM movie_genre WHERE movie_id = ?");
                $d1->bind_param("i", $movie_id);
                $d1->execute();
                $d1->close();
                
                // Delete from movies table
                $d2 = $conn->prepare("DELETE FROM movies WHERE movie_id = ?");
                $d2->bind_param("i", $movie_id);
                $d2->execute();
                $d2->close();
                
                $conn->commit();
                
                // Delete poster file if exists
                if ($poster && file_exists("../" . $poster)) {
                    @unlink("../" . $poster);
                }
                
                $response = ['status' => 'success', 'msg' => 'Movie berhasil dihapus'];
                
            } catch (Exception $e) {
                $conn->rollback();
                $response = ['status' => 'error', 'msg' => 'Gagal menghapus movie: ' . $e->getMessage()];
            }
            break;
            
        default:
            $response = ['status' => 'error', 'msg' => 'Action tidak dikenali'];
    }
    
} catch (Exception $e) {
    $response = ['status' => 'error', 'msg' => 'Error: ' . $e->getMessage()];
}

echo json_encode($response);
?>