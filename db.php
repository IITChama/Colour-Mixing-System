<?php
$conn = new mysqli("localhost", "root", "", "CMdatabase");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
