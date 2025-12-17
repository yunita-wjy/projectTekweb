<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmVerse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?php echo (file_exists('style.css') ? 'style.css' : '../style.css'); ?>">
</head>
<body>

<header id="main-header">
    <nav>
        <div class="logo">
            <img src="<?php echo (file_exists('assets/filmVerse-light.png') ? 'assets/filmVerse-light.png' : '../assets/filmVerse-light.png'); ?>" alt="Logo">
            <span>FilmVerse</span>
        </div>
        
        <ul class="menu">
            <li><a href="<?php echo (file_exists('index.php') ? 'index.php' : '../index.php'); ?>">Home</a></li>
            
            <li><a href="<?php echo (file_exists('customer/movies.php') ? 'customer/movies.php' : 'movies.php'); ?>">Movies</a></li>
            
            <li class="akun">
                <a href="<?php echo (file_exists('auth/login.php') ? 'auth/login.php' : '../auth/login.php'); ?>">Login</a>
            </li>
        </ul>
    </nav>
</header>