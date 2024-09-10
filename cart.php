<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "telugu@1258";
$dbname = "mysql";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$data = json_decode(file_get_contents("php://input"), true);
$productId = isset($data['productId']) ? intval($data['productId']) : 0;

// Assuming you have a way to get the user_id
$userId = 1; // Replace with actual user ID

// Check if productId is valid
if ($productId > 0) {
    // Check if the product is already in the cart
    $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Product already in cart.']);
    } else {
        // Add product to cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $quantity = 1; // Default quantity
        $stmt->bind_param("iii", $userId, $productId, $quantity);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding product to cart.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
}

$conn->close();
?>
