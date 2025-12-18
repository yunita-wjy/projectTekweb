<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Detail: <?= $m['title'] ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Reset & Dark Mode Base */
        body { 
            background-color: #141414; 
            color: white; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* --- NAVBAR STYLING --- */
        #main-header {
            background-color: rgba(0,0,0,0.9);
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
            padding: 10px 0;
        }
        .navbar-brand img { height: 40px; margin-right: 10px; }
        .navbar-brand span { font-weight: bold; font-size: 1.5rem; color: #fff; }
        .nav-link { color: #ccc !important; font-weight: 500; margin-right: 15px; }
        .nav-link:hover { color: #E50914 !important; }
        
        .btn-login {
            background-color: #E50914;
            color: white !important;
            padding: 8px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover { background-color: #b20710; }

        /* --- DETAIL SECTION --- */
        .detail-container {
            padding: 50px 0;
            /* Background Blur effect dari poster */
            background: linear-gradient(to right, #141414 20%, rgba(20,20,20,0.85) 60%, rgba(20,20,20,0.85) 100%), 
                        url('<?= $m['img'] ?>');
            background-size: cover;
            background-position: center;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .poster-hd {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            border: 1px solid #333;
        }

        .info-hd h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 10px; line-height: 1.1; }
        .meta-tags span { margin-right: 15px; font-size: 1.1rem; color: #ccc; }
        .synopsis { font-size: 1.1rem; line-height: 1.6; color: #ddd; margin-top: 20px; max-width: 700px; }

        .btn-ticket {
            background-color: #E50914; 
            color: white; 
            padding: 15px 40px; 
            border: none; 
            border-radius: 5px; 
            font-size: 1.2rem; 
            font-weight: bold;
            margin-top: 30px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }
        .btn-ticket:hover { background-color: #b20710; color: white; transform: scale(1.05); }

        /* --- FAKE TRAILER --- */
        .trailer-box {
            margin-top: 30px;
            width: 200px;
            height: 120px;
            background-color: #000;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            border: 2px solid #333;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }
        .trailer-box:hover { border-color: #E50914; }
        .trailer-bg {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; opacity: 0.5;
        }
        .play-icon {
            z-index: 2; color: white; font-size: 40px;
            filter: drop-shadow(0 0 10px black);
        }
        .trailer-label {
            position: absolute; bottom: 5px; left: 10px; z-index: 2;
            font-size: 12px; font-weight: bold;
        }

        /* --- FOOTER --- */
        footer { background: #000; padding: 40px 0; margin-top: 50px; border-top: 1px solid #333; text-align: center; color: #777; }
        footer ul { list-style: none; padding: 0; }
        footer ul li { display: inline; margin: 0 10px; }
        footer a { color: #777; text-decoration: none; }
        footer a:hover { color: white; }

        @media (max-width: 768px) {
            .detail-container { flex-direction: column; text-align: center; background: #141414; }
            .poster-hd { margin-bottom: 30px; }
            .info-hd h1 { font-size: 2.5rem; }
        }
    </style>
</head>

<body>
    
    <nav id="main-header" class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
                <img src="../assets/filmVerse-light.png" alt="logo" />
                <span>FilmVerse</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#films">Movies</a>
                    </li>
                    <li class="nav-item ms-3">
                        <?php if ($user): ?>
                            <a href="profile.php" class="nav-link text-white">
                                <i class="fa-regular fa-user me-2"></i> <?= htmlspecialchars($user['username']) ?>
                            </a>
                        <?php else: ?>
                            <a href="loginUI.php" class="btn-login">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>