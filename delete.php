<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    die("Access Denied: Only admins can delete records.");
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: index.php");
exit;
