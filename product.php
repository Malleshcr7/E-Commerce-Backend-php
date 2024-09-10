<?php
header("Access-Control-Allow-Origin: *"); // Allows requests from any origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "telugu@1258";
$dbname = "mysql";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$sql = "SELECT id, name, price, description, image_url FROM products WHERE id = $id";
$result = $conn->query($sql);

$product = null;

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
}

echo json_encode($product);

$conn->close();
?>
