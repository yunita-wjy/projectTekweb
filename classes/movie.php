<?php

class Movie {
    
    public $conn;

    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    
    public function getNowShowing() {
        
        $query = "SELECT * FROM movies"; 
        
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    
    public function getMovieById($id) {
        $id = mysqli_real_escape_string($this->conn, $id);
        $query = "SELECT * FROM movies WHERE movie_id = '$id'";
        $result = mysqli_query($this->conn, $query);
        
        return mysqli_fetch_assoc($result);
    }
}
?>