<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";

$connection = new mysqli($servername, $username, $password, $database);

$name = $email = $phone = $address = "";
$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (!$name || !$email || !$phone || !$address) {
        $error = "Please fill in all fields.";
    } else {
        $sql = "INSERT INTO clients (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
        if ($connection->query($sql)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Error: " . $connection->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>New Client</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="post">
            <input type="text" class="form-control mb-3" name="name" placeholder="Name" value="<?= $name ?>">
            <input type="email" class="form-control mb-3" name="email" placeholder="Email" value="<?= $email ?>">
            <input type="text" class="form-control mb-3" name="phone" placeholder="Phone" value="<?= $phone ?>">
            <input type="text" class="form-control mb-3" name="address" placeholder="Address" value="<?= $address ?>">
            <button class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>