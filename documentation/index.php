<?php
require 'config.php';
require 'header.php';
session_start();
if (!isset($_SESSION['contacts'])) {
    header("Location: login.php");
    exit;
}
// Read filters from GET
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$city = isset($_GET['city']) ? trim($_GET['city']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$dir = (isset($_GET['dir']) && strtoupper($_GET['dir']) === 'ASC') ? 'ASC' : 'DESC';

// pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 8;
$offset = ($page - 1) * $perPage;

// Build WHERE + params
$where = [];
$params = [];

if ($q !== '') {
    $where[] = "(name LIKE :q OR email LIKE :q OR phone LIKE :q)";
    $params[':q'] = "%$q%";
}
if ($status !== '' && in_array($status, ['Active','Inactive'])) {
    $where[] = "status = :status";
    $params[':status'] = $status;
}
if ($city !== '') {
    $where[] = "city LIKE :city";
    $params[':city'] = "%$city%";
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Count total
$stmt = $pdo->prepare("SELECT COUNT(*) FROM contacts $whereSQL");
$stmt->execute($params);
$total = $stmt->fetchColumn();
$pages = (int)ceil($total / $perPage);

// Allowed sort columns
$allowedSort = ['name','email','city','status','created_at'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';

// Fetch rows
$sql = "SELECT * FROM contacts $whereSQL ORDER BY $sort $dir LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $k=>$v) $stmt->bindValue($k, $v);
$stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();
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
<div class="card mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-center">
      <div class="col-sm-4">
        <input class="form-control" name="q" placeholder="Search name, email or phone" value="<?=htmlspecialchars($q)?>">
      </div>
      <div class="col-sm-2">
        <select name="status" class="form-select">
          <option value="">All status</option>
          <option value="Active" <?= $status==='Active' ? 'selected' : '' ?>>Active</option>
          <option value="Inactive" <?= $status==='Inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>
      <div class="col-sm-2">
        <input class="form-control" name="city" placeholder="City" value="<?=htmlspecialchars($city)?>">
      </div>
      <div class="col-sm-2">
        <select name="sort" class="form-select">
          <option value="created_at" <?= $sort==='created_at' ? 'selected' : '' ?>>Newest</option>
          <option value="name" <?= $sort==='name' ? 'selected' : '' ?>>Name</option>
          <option value="email" <?= $sort==='email' ? 'selected' : '' ?>>Email</option>
          <option value="city" <?= $sort==='city' ? 'selected' : '' ?>>City</option>
        </select>
      </div>
      <div class="col-sm-1">
        <select name="dir" class="form-select">
          <option value="DESC" <?= $dir==='DESC' ? 'selected' : '' ?>>Desc</option>
          <option value="ASC" <?= $dir==='ASC' ? 'selected' : '' ?>>Asc</option>
        </select>
      </div>
      <div class="col-sm-1">
        <button class="btn btn-primary w-100">Filter</button>
      </div>
    </form>
  </div>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
  <?php if (!$rows): ?>
    <div class="col"><div class="alert alert-info">No records found.</div></div>
  <?php endif; ?>
  <?php foreach ($rows as $r): ?>
    <div class="col">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title mb-1"><?=htmlspecialchars($r['name'])?></h5>
          <p class="mb-1"><strong>Email:</strong> <?=htmlspecialchars($r['email'])?></p>
          <p class="mb-1"><strong>Phone:</strong> <?=htmlspecialchars($r['phone'])?></p>
          <p class="mb-1"><strong>City:</strong> <?=htmlspecialchars($r['city'])?></p>
          <p class="mb-1"><span class="badge <?= $r['status']==='Active' ? 'bg-success' : 'bg-secondary' ?>"><?= $r['status'] ?></span></p>
        </div>
        <div class="card-footer d-flex justify-content-between">
          <div>
            <a href="edit.php?id=<?=$r['id']?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <a href="delete.php?id=<?=$r['id']?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?')">Delete</a>
          </div>
          <small class="text-muted"><?=date('Y-m-d', strtotime($r['created_at']))?></small>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Pagination -->
<nav class="mt-4" aria-label="Page navigation">
  <ul class="pagination justify-content-center">
    <?php
    // Build query string base (preserve filters)
    $qs = $_GET;
    for ($p = 1; $p <= max(1,$pages); $p++):
      $qs['page'] = $p;
      $link = '?'.http_build_query($qs);
    ?>
      <li class="page-item <?= $p==$page ? 'active' : '' ?>"><a class="page-link" href="<?=$link?>"><?=$p?></a></li>
    <?php endfor; ?>
  </ul>
</nav>

<?php require 'footer.php'; ?>
</body>
</html>

