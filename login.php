<?php
session_start();

// Check login submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded login (you can connect to database later)
    if ($username === 'ColourAdmin' && $password === 'CA1234') {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Colour Mixing System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('https://www.responsiveindustries.com/wp-content/uploads/2020/06/leather-banner.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .corner-logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            opacity: 0.9;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95); /* restored white */
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
            z-index: 1;
            color: #333;
        }

        .login-container h2 {
            margin-bottom: 30px;
            color: #d32f2f; /* red heading */
        }

        .login-container input {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .login-container button {
            background: #0288d1;
            color: white;
            padding: 12px 15px;
            border: none;
            width: 100%;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button:hover {
            background: #0277bd;
        }

        .error {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <!-- Primo logo in the top-right corner -->
    <img src="https://www.primolk.com/wp-content/uploads/2023/07/Logo.png" class="corner-logo" alt="Primo Logo">

    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
            <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        </form>
    </div>

</body>
</html>
