<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Customized Colour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --primary-color: #0288d1;
            --accent-color: #e1f5fe;
            --bg-color: #ffffff;
            --text-color: #333;
            --sidebar-bg: #0277bd;
            --sidebar-text: #e1f5fe;
            --sidebar-hover-bg: #015f8b;
        }

        body.dark-mode {
            --primary-color: #90caf9;
            --accent-color: #263238;
            --bg-color: #121212;
            --text-color: #e0e0e0;
            --sidebar-bg: #37474f;
            --sidebar-text: #90caf9;
            --sidebar-hover-bg: #263238;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://spectrumbylarrys.com/wp-content/uploads/2024/01/Faltenwurf_Solo_01.jpg');
            color: var(--text-color);
            transition: background 0.4s, color 0.4s;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            padding-top: 20px;
            position: fixed;
            height: 100vh;
        }

        .sidebar h2 {
            color: var(--sidebar-text);
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 15px 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: var(--sidebar-hover-bg);
        }

        .sidebar a.active {
            background-color: var(--primary-color);
            font-weight: 700;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px 40px;
            flex: 1;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn-toggle, .btn-print {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-container {
            max-width: 800px;
            background-color: var(--bg-color);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin: 0 auto;
        }

        h2 {
            color: var(--primary-color);
            text-align: center;
        }

        label {
            font-weight: 500;
            display: block;
            margin-top: 20px;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #bbb;
            border-radius: 8px;
            background-color: #f1f1f1;
            margin-top: 5px;
            color: var(--text-color);
        }

        body.dark-mode input {
            background-color: #263238;
            border: 1px solid #555;
            color: var(--text-color);
        }

        .group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
            align-items: center;
        }

        .btn-submit, .btn-add, .btn-remove {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
        }

        .btn-remove {
            background-color: #e53935;
            padding: 6px 12px;
        }

        .success-msg {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                flex-direction: row;
                overflow-x: auto;
                padding: 10px 0;
            }
            .main-content {
                margin-left: 0;
                padding: 20px 10px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="https://www.primolk.com/wp-content/uploads/2023/07/Logo.png" alt="Primo Logo">
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="add_colour.php" class="active"><i class="fas fa-palette"></i> Add Colour</a>
    <a href="search_colour.php"><i class="fas fa-search"></i> Search Colour</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="top-bar">
        <button class="btn-toggle" onclick="toggleDarkMode()"><i class="fas fa-adjust"></i> Toggle Theme</button>
        <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Print Recipe</button>
    </div>

    <div class="form-container">
        <h2><i class="fas fa-palette"></i> Add Customized Colour</h2>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="success-msg">
                🎉 Your customized colour was <strong>saved successfully!</strong> ✅
            </div>
        <?php endif; ?>

        <form action="save_colour.php" method="POST" id="colourForm">
            <label>Customized Colour ID</label>
            <input type="text" name="colour_id" required>

            <label>Name</label>
            <input type="text" name="name" required>

            <label>Quantity (kg)</label>
            <input type="number" step="0.01" name="quantity" required>

            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>

            <label>Mixing Colours, ID & Quantities</label>
            <div id="mixing-colours-wrapper">
                <div class="group mixing-row">
                    <input type="text" name="mixing_colour_1" placeholder="Mixing Colour 1" required>
                    <input type="number" step="0.01" name="mixing_quantity_1" placeholder="Quantity (g)" required>
                </div>
            </div>
            <button type="button" id="addMixingColourBtn" class="btn-add">+ Add Mixing Colour</button>

            <label>Other Ingredients & Quantities</label>
            <div id="ingredients-wrapper">
                <div class="group ingredient-row">
                    <input type="text" name="ingredient_1" placeholder="Ingredient 1" required>
                    <input type="number" step="0.01" name="ingredient_quantity_1" placeholder="Quantity (g)" required>
                </div>
            </div>
            <button type="button" id="addIngredientBtn" class="btn-add">+ Add Ingredient</button>

            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Save Colour</button>
        </form>
    </div>
</div>

<script>
    let mixingCount = 1;
    const maxMixingColours = 6;
    let ingredientCount = 1;
    const maxIngredients = 4;

    document.getElementById('addMixingColourBtn').addEventListener('click', () => {
        if (mixingCount < maxMixingColours) {
            mixingCount++;
            const wrapper = document.getElementById('mixing-colours-wrapper');
            const div = document.createElement('div');
            div.classList.add('group', 'mixing-row');
            div.innerHTML = `
                <input type="text" name="mixing_colour_${mixingCount}" placeholder="Mixing Colour ${mixingCount}" required>
                <input type="number" step="0.01" name="mixing_quantity_${mixingCount}" placeholder="Quantity (g)" required>
                <button type="button" class="btn-remove" onclick="this.parentElement.remove(); mixingCount--;">Remove</button>
            `;
            wrapper.appendChild(div);
        }
    });

    document.getElementById('addIngredientBtn').addEventListener('click', () => {
        if (ingredientCount < maxIngredients) {
            ingredientCount++;
            const wrapper = document.getElementById('ingredients-wrapper');
            const div = document.createElement('div');
            div.classList.add('group', 'ingredient-row');
            div.innerHTML = `
                <input type="text" name="ingredient_${ingredientCount}" placeholder="Ingredient ${ingredientCount}" required>
                <input type="number" step="0.01" name="ingredient_quantity_${ingredientCount}" placeholder="Quantity (g)" required>
                <button type="button" class="btn-remove" onclick="this.parentElement.remove(); ingredientCount--;">Remove</button>
            `;
            wrapper.appendChild(div);
        }
    });

    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
    }
</script>

</body>
</html>




