<?php
class movie
{
    public $conn;

    public function __construct($conn)
    {
        $this->conn = $conn; // ini PDO
    }

    public function getNowShowing()
    {
        // Ambil film yg sedang tayang
        $sqlShow = "
            SELECT DISTINCT
                m.movie_id,
                m.title,
                m.poster_path,
                m.duration,
                m.synopsis,
                m.trailer_url,
                m.status
            FROM showtimes s
            JOIN movies m ON s.movie_id = m.movie_id
            WHERE 
                m.status = 'active'
            AND CURDATE() BETWEEN m.start_date AND m.end_date";

        $stmt = $this->conn->prepare($sqlShow);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComingSoon()
    {
        // Ambil film yg akan tayang
        $sqlSoon = "
            SELECT DISTINCT
                m.movie_id,
                m.title,
                m.poster_path
            FROM showtimes s
            JOIN movies m ON s.movie_id = m.movie_id
            WHERE 
                m.status = 'coming_soon'
                AND CURDATE() BETWEEN m.start_date AND m.end_date";

        $stmt = $this->conn->prepare($sqlSoon);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHeroMovie()
    {
        // Ambil satu film random yang sedang tayang
        $sqlHero = "
            SELECT *
            FROM movies
            WHERE status = 'active'
            AND CURDATE() BETWEEN start_date AND end_date
            ORDER BY RAND()
            LIMIT 1";

        $stmt = $this->conn->prepare($sqlHero);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMovieById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM movies WHERE movie_id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchMovie($keyword)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM movies WHERE title LIKE :keyword"
        );
        $stmt->execute([
            'keyword' => "%$keyword%"
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
