<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// DB connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CMdatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect POST data safely
$colour_id = $_POST['colour_id'] ?? '';
$name = $_POST['name'] ?? '';
$quantity = floatval($_POST['quantity'] ?? 0);
$date = $_POST['date'] ?? '';

// Validate required fields
if (empty($colour_id) || empty($name) || $quantity <= 0 || empty($date)) {
    die("Please fill in all required fields correctly.");
}

$conn->begin_transaction();

try {
    // 1. Insert into customized_colours
    $stmt = $conn->prepare("INSERT INTO customized_colours (colour_id, name, quantity, date) VALUES (?, ?, ?, ?)");
    if (!$stmt) throw new Exception("Prepare failed: " . $conn->error);
    $stmt->bind_param("ssds", $colour_id, $name, $quantity, $date);
    if (!$stmt->execute()) throw new Exception("Insert failed: " . $stmt->error);
    $customized_colour_id = $stmt->insert_id; // Get auto-increment ID
    $stmt->close();

    // 2. Insert mixing colours
    $sql_mix = "INSERT INTO mixing_colours (customized_colour_id, mixing_colour, mixing_quantity) VALUES (?, ?, ?)";
    $stmt_mix = $conn->prepare($sql_mix);
    if (!$stmt_mix) throw new Exception("Prepare failed for mixing colours: " . $conn->error);

    for ($i = 1; $i <= 6; $i++) {
        $mix_col = $_POST["mixing_colour_$i"] ?? '';
        $mix_qty = floatval($_POST["mixing_quantity_$i"] ?? 0);
        if (!empty($mix_col) && $mix_qty > 0) {
            $stmt_mix->bind_param("isd", $customized_colour_id, $mix_col, $mix_qty);
            if (!$stmt_mix->execute()) throw new Exception("Insert mixing colour failed: " . $stmt_mix->error);
        }
    }
    $stmt_mix->close();

    // 3. Insert other ingredients
    $sql_ing = "INSERT INTO ingredients (customized_colour_id, ingredient, ingredient_quantity) VALUES (?, ?, ?)";
    $stmt_ing = $conn->prepare($sql_ing);
    if (!$stmt_ing) throw new Exception("Prepare failed for ingredients: " . $conn->error);

    for ($j = 1; $j <= 4; $j++) {
        $ing = $_POST["ingredient_$j"] ?? '';
        $qty = floatval($_POST["ingredient_quantity_$j"] ?? 0);
        if (!empty($ing) && $qty > 0) {
            $stmt_ing->bind_param("isd", $customized_colour_id, $ing, $qty);
            if (!$stmt_ing->execute()) throw new Exception("Insert ingredient failed: " . $stmt_ing->error);
        }
    }
    $stmt_ing->close();

    // 4. Commit transaction
    $conn->commit();

    echo "<h3 style='color:green; text-align:center; margin-top:50px;'>🎉 Customized colour and ingredients saved successfully!</h3>";
    echo "<p style='text-align:center;'><a href='add_colour.php'>➕ Add Another</a> | <a href='dashboard.php'>🏠 Go to Dashboard</a></p>";

} catch (Exception $e) {
    $conn->rollback(); // Roll back on error
    echo "<h3 style='color:red; text-align:center; margin-top:50px;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
    echo "<p style='text-align:center;'><a href='add_colour.php'>Try Again</a></p>";
}

$conn->close();
?>
