<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'cmdatabase';

// Connect to database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Colour Data</title>
    <style>
        table {
            width: 90%;
            margin-bottom: 40px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>

    <h2>Customized Colours</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Colour ID</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Date</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM customized_colours");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['colour_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['date']}</td>
            </tr>";
        }
        ?>
    </table>

    <h2>Ingredients</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Colour ID</th>
            <th>Ingredient Name</th>
            <th>Ingredient Quantity</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM ingredients");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['colour_id']}</td>
                <td>{$row['ingredient_name']}</td>
                <td>{$row['ingredient_quantity']}</td>
            </tr>";
        }
        ?>
    </table>

    <h2>Mixing Colours</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Colour ID</th>
            <th>Mixing Colour Name</th>
            <th>Quantity</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM mixing_colours");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['colour_id']}</td>
                <td>{$row['mixing_colour_name']}</td>
                <td>{$row['quantity']}</td>
            </tr>";
        }
        ?>
    </table>

</body>
</html>
