<?php
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

$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

if (!$client) {
    echo "Client not found";
    exit;
}

$name = $client['name'];
$email = $client['email'];
$phone = $client['phone'];
$address = $client['address'];
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    if (!$name || !$email || !$phone || !$address) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE clients SET name=?, email=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);
        $stmt->execute();
        header("Location: index.php");
        exit;
    }
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
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3"><label>Name</label><input class="form-control" name="name" value="<?= $name ?>"></div>
            <div class="mb-3"><label>Email</label><input class="form-control" name="email" value="<?= $email ?>"></div>
            <div class="mb-3"><label>Phone</label><input class="form-control" name="phone" value="<?= $phone ?>"></div>
            <div class="mb-3"><label>Address</label><input class="form-control" name="address" value="<?= $address ?>"></div>
            <button class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>