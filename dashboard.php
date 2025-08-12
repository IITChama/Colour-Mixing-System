<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Colour Mixing System</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --text-color: #333;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('https://spectrumbylarrys.com/wp-content/uploads/2024/01/Faltenwurf_Solo_01.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-color);
            transition: background 0.4s, color 0.4s;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: rgba(2, 136, 209, 0.95); /* Semi-transparent sidebar */
            color: white;
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
        }

        .sidebar h1 {
            font-size: 22px;
            margin-bottom: 40px;
            text-align: center;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            margin: 15px 0;
            padding: 12px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #03a9f4;
        }

        .sidebar i {
            margin-right: 12px;
            font-size: 18px;
        }

        .main-content {
            flex: 1;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            margin: 20px;
            border-radius: 12px;
        }

        .main-content h2 {
            font-size: 28px;
            color: #0277bd;
        }

        .welcome-box {
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.95); /* More readable box */
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        .sidebar img {
            width: 180px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="https://www.primolk.com/wp-content/uploads/2023/07/Logo.png" alt="Primo Logo">
    <a href="add_colour.php"><i class="fas fa-plus-circle"></i> Add Customized Colour</a>
    <a href="search_colour.php"><i class="fas fa-search"></i> Search Colour</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <h2>Welcome, ColourAdmin!</h2>
    <div class="welcome-box">
        <p>
            Step into your vibrant control center at <strong>Primo Pvt Limited</strong>, where every shade tells a story.
            This dashboard is your command hub to effortlessly create, manage, and explore customized colour recipes tailored for perfection.
            Use the intuitive sidebar navigation to quickly add new colours, search existing mixes, and keep the palette flowing smoothly.
            Let’s bring your colour creations to life with precision and style!
        </p>
    </div>
</div>

</body>
</html>
