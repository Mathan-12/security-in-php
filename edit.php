<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";
$connection = new mysqli($servername, $username, $password, $database);

$id = $_GET['id'];
$result = $connection->query("SELECT * FROM clients WHERE id = $id");
if (!$result || $result->num_rows === 0) {
    header("Location: index.php");
    exit;
}
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE clients SET name='$name', email='$email', phone='$phone', address='$address' WHERE id=$id";
    $connection->query($sql);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Edit Client</h2>
        <form method="post">
            <input type="text" class="form-control mb-3" name="name" value="<?= $row['name'] ?>">
            <input type="email" class="form-control mb-3" name="email" value="<?= $row['email'] ?>">
            <input type="text" class="form-control mb-3" name="phone" value="<?= $row['phone'] ?>">
            <input type="text" class="form-control mb-3" name="address" value="<?= $row['address'] ?>">
            <button class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>