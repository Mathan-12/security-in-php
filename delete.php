<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";
$connection = new mysqli($servername, $username, $password, $database);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $connection->query("DELETE FROM clients WHERE id = $id");
}
header("Location: index.php");
exit;
?>