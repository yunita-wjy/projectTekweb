<?php
require "../../config/connection.php";

$q = $_GET['q'] ?? '';
$q = trim($q);

// ambil movies berdasarkan search title
$sql = "SELECT m.movie_id, m.title, m.duration, m.start_date, m.end_date, 
               m.status, m.poster_path, m.synopsis, m.trailer_url,
               GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres_name,
               GROUP_CONCAT(mg.genre_id) AS genres_id
        FROM movies m
        LEFT JOIN movie_genre mg ON m.movie_id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.genre_id
        WHERE m.title LIKE ?
        GROUP BY m.movie_id
        ORDER BY m.movie_id DESC";

$stmt = $conn->prepare($sql);
$like = "%$q%";
$stmt->bind_param("s", $like);
$stmt->execute();
$res = $stmt->get_result();

$html = '';
if($res->num_rows > 0){
    $i = 1;
    while($m = $res->fetch_assoc()){
        $genres = $m['genres_name'] ?? '-';
        $poster = $m['poster_path'] ? "../{$m['poster_path']}" : '';
        
        // Parse genre_ids
        $genre_ids = [];
        if (!empty($m['genres_id'])) {
            $genre_ids = array_map('intval', explode(',', $m['genres_id']));
        }
        
        $movie_data = [
            "id" => $m["movie_id"],
            "title" => $m["title"],
            "duration" => $m["duration"],
            "start_date" => $m["start_date"],
            "end_date" => $m["end_date"],
            "synopsis" => $m["synopsis"] ?? "",
            "trailer" => $m["trailer_url"] ?? "",
            "genre_ids" => $genre_ids
        ];
        
        $html .= "<tr>
            <td>$i</td>
            <td>";
        
        if($poster) {
            $html .= "<img src='$poster' class='poster-thumb'>";
        } else {
            $html .= "<div class='border rounded poster-thumb' style='background:#e9ecef; width:60px; height:80px;'></div>";
        }
        
        $html .= "</td>
            <td>".htmlspecialchars($m['title'])."</td>
            <td>".htmlspecialchars($genres)."</td>
            <td>{$m['duration']}</td>
            <td>{$m['start_date']}</td>
            <td>{$m['end_date']}</td>
            <td>";
        
        if($m['status'] === 'active') {
            $html .= "<span class='badge bg-success'>Active</span>";
        } elseif($m['status'] === 'coming_soon') {
            $html .= "<span class='badge bg-info text-dark'>Coming Soon</span>";
        } else {
            $html .= "<span class='badge bg-secondary'>Inactive</span>";
        }
        
        $html .= "</td>
            <td>
                <div class='d-flex flex-column gap-2'>
                    <button type='button' class='btn btn-sm btn-warning btn-edit-movie'
                        data-movie='".htmlspecialchars(json_encode($movie_data), ENT_QUOTES)."'>
                        Edit
                    </button>
                    <form method='POST' class='d-inline'>
                        <input type='hidden' name='action' value='delete_movie'>
                        <input type='hidden' name='movie_id' value='{$m['movie_id']}'>
                        <button type='button' class='btn btn-sm btn-danger w-100 btn-delete-movie' 
                                data-id='{$m['movie_id']}'>
                            Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>";
        $i++;
    }
    echo json_encode(['status'=>'success','html'=>$html]);
} else {
    echo json_encode([
        'status'=>'success',
        'html'=>'<tr><td colspan="9" class="text-center text-muted py-4">Movie not found</td></tr>'
    ]);
}

$stmt->close();
?>