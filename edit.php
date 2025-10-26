<?php
require 'config.php';
require 'header.php';

//$id = (int)($_GET['id'] ?? 0);
/* $id = $_GET['id'];

if ($id <= 0) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
stmt_execute = null;
$stmt->execute([':id'=>$id]);
$contact = $stmt->fetch();
if (!$contact) { header('Location: index.php'); exit; } */

$id = $_GET['id'];
// $name = $_POST['name'];
// $email = $_POST['email'];

$stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = :id");
// $stmt->bind_param("ssi", $name, $email, $id);
// $stmt->execute();
$stmt->execute([':id'=>$id]);
$contact = $stmt->fetch();
if (!$contact) { header('Location: index.php'); exit; }
// $stmt->close();
// $conn->close();


$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $status = in_array($_POST['status'] ?? '', ['Active','Inactive']) ? $_POST['status'] : 'Active';

    if ($name === '') $errors[] = 'Name is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required';

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE contacts SET name=:name,email=:email,phone=:phone,city=:city,status=:status WHERE id=:id");
        $stmt->execute([
            ':name'=>$name, ':email'=>$email, ':phone'=>$phone, ':city'=>$city, ':status'=>$status, ':id'=>$id
        ]);
        header('Location: index.php');
        exit;
    }
} else {
    // prefill
    $name = $contact['name'];
    $email = $contact['email'];
    $phone = $contact['phone'];
    $city = $contact['city'];
    $status = $contact['status'];
}
?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Edit Contact</h5>

    <?php if ($errors): ?>
      <div class="alert alert-danger"><?php foreach ($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="<?=htmlspecialchars($name)?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input name="email" class="form-control" value="<?=htmlspecialchars($email)?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control" value="<?=htmlspecialchars($phone)?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">City</label>
        <input name="city" class="form-control" value="<?=htmlspecialchars($city)?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Active" <?= $status==='Active' ? 'selected' : '' ?>>Active</option>
          <option value="Inactive" <?= $status==='Inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>
      <div class="col-12">
        <button class="btn btn-primary">Update</button>
        <a class="btn btn-secondary" href="index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require 'footer.php'; ?>