<?php
session_start();

// Sample login for testing (you can improve it)
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'admin'; // or 'editor'
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "myshop";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Pagination settings
$limit = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchParam = "%$search%";

$sql = "SELECT * FROM clients WHERE name LIKE ? OR email LIKE ? LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $searchParam, $searchParam, $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Count total
$countResult = $conn->prepare("SELECT COUNT(*) FROM clients WHERE name LIKE ? OR email LIKE ?");
$countResult->bind_param("ss", $searchParam, $searchParam);
$countResult->execute();
$countResult->bind_result($total);
$countResult->fetch();
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Clients</h2>
        <form method="GET" class="mb-3">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." class="form-control w-25 d-inline">
            <button class="btn btn-primary">Search</button>
            <a class="btn btn-success" href="create.php">New Client</a>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <td><?= $row['address'] ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this client?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</body>

</html>