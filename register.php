<?php
header("Access-Control-Allow-Origin: *"); // Allows requests from any origin
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Enable error reporting for debugging (remove or disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "telugu@1258";
$dbname = "mysql";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set content type to JSON
header("Content-Type: application/json");

// Read and decode the input data
$input = file_get_contents("php://input");

// Check if input data is empty or null
if ($input === false || empty($input)) {
    echo json_encode(["status" => "error", "message" => "No input data received"]);
    exit();
}

$data = json_decode($input, true);

// Check if JSON decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "JSON decoding error: " . json_last_error_msg()]);
    exit();
}

// Check if all required fields are present
if (isset($data['name']) && isset($data['email']) && isset($data['password'])) {
    $name = $data['name'];
    $email = $data['email'];
    $password = $data['password'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit();
    }

    $stmt->bind_param("sss", $name, $email, $password);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Registration successful"]);
    } else {
        // Check if duplicate entry error
        if ($conn->errno == 1062) {
            echo json_encode(["status" => "error", "message" => "Duplicate entry for email"]);
        } else {
            echo json_encode(["status" => "error", "message" => "An error occurred: " . $stmt->error]);
        }
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}

// Close the connection
$conn->close();
?>
