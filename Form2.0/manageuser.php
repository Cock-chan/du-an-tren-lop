<?php
include("index.php");
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

// Lấy danh sách người dùng
$result = mysqli_query($con, "SELECT * FROM user ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">👥 User Management</h1>

    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Gender</th>
          <th>Address</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($user = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['NAME']) ?></td>
          <td><?= htmlspecialchars($user['EMAIL']) ?></td>
          <td><?= htmlspecialchars($user['NUMBER']) ?></td>
          <td><?= htmlspecialchars($user['GIOI TINH']) ?></td>
          <td><?= htmlspecialchars($user['DIACHI']) ?></td>
          <td><?= htmlspecialchars($user['role']) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</body>
</html>
