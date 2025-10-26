<?php
session_start();
if (!isset($_SESSION['contacts'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Welcome</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 text-center">
  <div class="card p-4 shadow">
    <h2>Welcome, <?php echo $_SESSION['contacts']; ?> 👋</h2>
    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
  </div>
</div>
</body>
</html>
