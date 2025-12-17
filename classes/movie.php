<?php

class movie {
    // Menyimpan koneksi database
    public $conn;

    // Konstruktor: Jalan otomatis saat class dipanggil
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getNowShowing() {
        "
        $query = "SELECT * FROM movies";
        
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    // FUNGSI 2: Ambil detail 1 film berdasarkan ID (Untuk Halaman Detail)
    public function getMovieById($id) {
        // Mencegah error jika ID kosong
        $id = mysqli_real_escape_string($this->conn, $id);
        
        $query = "SELECT * FROM movies WHERE movie_id = '$id'";
        $result = mysqli_query($this->conn, $query);
        
        // Mengembalikan hasil dalam bentuk Array Asosiatif
        return mysqli_fetch_assoc($result);
    }
    
    // FUNGSI 3: Pencarian Film (Bonus buat Fitur Search di desainmu)
    public function searchMovie($keyword) {
        $keyword = mysqli_real_escape_string($this->conn, $keyword);
        $query = "SELECT * FROM movies WHERE title LIKE '%$keyword%'";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
}
?>