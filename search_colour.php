
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$dbname = "cmdatabase";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$colourDetailsList = [];
$quantity = 0;
$colour_id = '';
$search_date = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $colour_id = trim($_POST['colour_id']);
    $quantity = floatval($_POST['quantity']);
    $search_date = !empty($_POST['search_date']) ? $_POST['search_date'] : null;

    if ($search_date) {
        $stmt = $conn->prepare("SELECT * FROM customized_colours WHERE colour_id = ? AND date = ? ORDER BY date DESC");
        $stmt->bind_param("ss", $colour_id, $search_date);
    } else {
        $stmt = $conn->prepare("SELECT * FROM customized_colours WHERE colour_id = ? ORDER BY date DESC");
        $stmt->bind_param("s", $colour_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $colourDetailsList = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Search Colour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2c3e50;
            --input-border: #ccc;
            --error-bg: #f8d7da;
            --error-text: #721c24;
            --card-bg: #f5f5f5;
            --text-color: #333;
            --btn-bg: #2c3e50;
            --btn-hover: #1a252f;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            background: #fff;
        }

        body.dark {
            background: #121212;
            color: #f1f1f1;
        }

        .sidebar {
            width: 220px;
            background-color: var(--primary-dark);
            height: 100vh;
            padding-top: 20px;
            position: fixed;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar img {
            width: 100px;
            margin-bottom: 15px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            width: 100%;
            text-align: center;
            white-space: nowrap;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background-color: #327fc8ff;
        }

        .sidebar a i {
            margin-right: 8px;
        }

        .main-content {
            margin-left: 220px;
            padding: 40px;
            flex: 1;
        }

        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
            margin-left: 10px;
        }

        .toggle-switch input {
            display: none;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            background-color: #ccc;
            top: 0; left: 0; right: 0; bottom: 0;
            transition: 0.4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        form label, form input {
            display: block;
            margin-bottom: 15px;
            width: 100%;
            max-width: 400px;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            padding: 10px;
            border: 1px solid var(--input-border);
            border-radius: 6px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-weight: bold;
            border: none;
            background-color: var(--primary-color);
            color: white;
            border-radius: 6px;
            cursor: pointer;
            max-width: 400px;
        }

        .result-section {
            margin-top: 30px;
            background-color: var(--card-bg);
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }

        .result-section h1, .result-section h2, .result-section h3 {
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        .info {
            font-size: 16px;
            margin: 6px 0;
        }

        .recipe-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .recipe-table th, .recipe-table td {
            border: 1px solid var(--input-border);
            padding: 8px 12px;
            text-align: left;
            font-size: 16px;
            color: var(--text-color);
        }

        .recipe-table th {
            background-color: var(--primary-color);
            color: white;
        }

        .recipe-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        body.dark .recipe-table th {
            background-color: var(--primary-dark);
        }

        body.dark .recipe-table tbody tr:nth-child(even) {
            background-color: #2a2a2a;
        }

        .error {
            background: var(--error-bg);
            color: var(--error-text);
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 25px;
            font-weight: 700;
            text-align: center;
        }

        .print-btn {
            background-color: var(--btn-bg);
            color: white;
            padding: 10px 18px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
        }

        .print-btn:hover {
            background-color: var(--btn-hover);
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
            .sidebar a {
                white-space: nowrap;
                padding: 12px 15px;
            }
            .main-content {
                margin-left: 0;
                padding: 20px 10px;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .printableArea, .printableArea * {
                visibility: visible;
            }
            .printableArea {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                padding: 0 20px;
            }
            .printableArea h1 { font-size: 28pt; margin-bottom: .5em; }
            .printableArea h2 { font-size: 22pt; margin: .2em 0 .5em; }
            .printableArea h3 { font-size: 18pt; margin: 1em 0 .4em; }
            .printableArea p, .printableArea li { font-size: 12pt; line-height: 1.4; }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <img src="https://www.primolk.com/wp-content/uploads/2023/07/Logo.png" alt="Primo Logo" />
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="add_colour.php"><i class="fas fa-palette"></i> Add Colour</a>
    <a href="search_colour.php" class="active"><i class="fas fa-search"></i> Search Colour</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<main class="main-content">
    <div class="top-bar">
        <label for="darkModeToggle">Dark Mode</label>
        <label class="toggle-switch">
            <input type="checkbox" id="darkModeToggle" />
            <span class="slider"></span>
        </label>
    </div>

    <h2>Search Customized Colour</h2>
    <form method="POST" autocomplete="off">
        <label for="colour_id">Colour ID</label>
        <input type="text" name="colour_id" id="colour_id" placeholder="Enter Colour ID" value="<?= htmlspecialchars($colour_id) ?>" required />

        <label for="quantity">Quantity (kg)</label>
        <input type="number" step="0.01" min="0.01" name="quantity" id="quantity" placeholder="Enter quantity in kg" value="<?= $quantity ?>" required />

        <label for="search_date">Search by Date (optional)</label>
        <input type="date" name="search_date" id="search_date" value="<?= htmlspecialchars($search_date) ?>" />

        <input type="submit" value="Search" />
    </form>

    <?php if (!empty($colourDetailsList)): ?>
        <h2>Search Results for Colour ID: <?= htmlspecialchars($colour_id) ?><?= $search_date ? " on " . htmlspecialchars($search_date) : "" ?></h2>
        <table class="recipe-table">
            <thead>
                <tr>
                    <th>Recipe ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Base Quantity (kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($colourDetailsList as $recipe): ?>
                    <tr>
                        <td><?= htmlspecialchars($recipe['id']) ?></td>
                        <td><?= htmlspecialchars($recipe['name']) ?></td>
                        <td><?= htmlspecialchars($recipe['date']) ?></td>
                        <td><?= htmlspecialchars($recipe['quantity']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php foreach ($colourDetailsList as $index => $colourDetails): ?>
            <?php
            $customized_colour_id = $colourDetails['id'];

            // Mixing Colours
            $stmt = $conn->prepare("SELECT mixing_colour, mixing_quantity FROM mixing_colours WHERE customized_colour_id = ?");
            $stmt->bind_param("i", $customized_colour_id);
            $stmt->execute();
            $mixingColours = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            // Ingredients
            $stmt = $conn->prepare("SELECT ingredient, ingredient_quantity FROM ingredients WHERE customized_colour_id = ?");
            $stmt->bind_param("i", $customized_colour_id);
            $stmt->execute();
            $ingredients = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            ?>
            <div class="result-section printableArea" id="printableArea_<?= $index ?>">
                <h1>Colour Recipe</h1>
                <h2>ID: <?= htmlspecialchars($colourDetails['colour_id']); ?></h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($colourDetails['name']); ?></p>
                <p><strong>Base Quantity:</strong> <?= $colourDetails['quantity']; ?> kg</p>
                <p><strong>Date:</strong> <?= $colourDetails['date']; ?></p>

                <h3>Mixing Colours for <?= $quantity; ?> kg</h3>
                <table class="recipe-table">
                    <thead><tr><th>Mixing Colour Name</th><th>Quantity (g)</th></tr></thead>
                    <tbody>
                        <?php foreach ($mixingColours as $mc): ?>
                            <tr>
                                <td><?= htmlspecialchars($mc['mixing_colour']); ?></td>
                                <td><?= round(($mc['mixing_quantity'] / $colourDetails['quantity']) * $quantity, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>Special Ingredients for <?= $quantity; ?> kg</h3>
                <table class="recipe-table">
                    <thead><tr><th>Ingredient Name</th><th>Quantity (g)</th></tr></thead>
                    <tbody>
                        <?php foreach ($ingredients as $ing): ?>
                            <tr>
                                <td><?= htmlspecialchars($ing['ingredient']); ?></td>
                                <td><?= round(($ing['ingredient_quantity'] / $colourDetails['quantity']) * $quantity, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <button class="print-btn" onclick="printRecipe('printableArea_<?= $index ?>')">🖨️ Print Recipe</button>
            </div>
        <?php endforeach; ?>

    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="error">
            No colour found with ID "<?= htmlspecialchars($_POST['colour_id']); ?>"<?= $search_date ? " on " . htmlspecialchars($search_date) : "" ?>.
        </div>
    <?php endif; ?>
</main>

<script>
    const toggle = document.getElementById('darkModeToggle');
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark');
        toggle.checked = true;
    }

    toggle.addEventListener('change', () => {
        if (toggle.checked) {
            document.body.classList.add('dark');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            document.body.classList.remove('dark');
            localStorage.setItem('darkMode', 'disabled');
        }
    });

    function printRecipe(divId) {
        // Get all printable areas
        const printAreas = document.querySelectorAll('.printableArea');

        // Hide all printable areas except the one to print
        printAreas.forEach(area => {
            if (area.id !== divId) {
                area.style.display = 'none';
            }
        });

        // Print the page with only one visible printableArea
        window.print();

        // Restore all printable areas visibility after printing
        printAreas.forEach(area => {
            area.style.display = '';
        });
    }
</script>
</body>
</html>
