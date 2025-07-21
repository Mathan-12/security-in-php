<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name = $email = $phone = $address = "";
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    if (!$name || !$email || !$phone || !$address) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("INSERT INTO clients (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $address);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Database error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>New Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Add New Client</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3"><label>Name</label><input class="form-control" name="name" value="<?= $name ?>"></div>
            <div class="mb-3"><label>Email</label><input class="form-control" name="email" value="<?= $email ?>"></div>
            <div class="mb-3"><label>Phone</label><input class="form-control" name="phone" value="<?= $phone ?>"></div>
            <div class="mb-3"><label>Address</label><input class="form-control" name="address" value="<?= $address ?>"></div>
            <button class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>