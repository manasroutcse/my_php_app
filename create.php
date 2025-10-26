<?php
require 'config.php';
require 'header.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
	$password = trim($_POST['password'] ??'');
    $status = in_array($_POST['status'] ?? '', ['Active','Inactive']) ? $_POST['status'] : 'Active';
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
// Validate phone number
   
    if ($name === '') $errors[] = 'Name is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required';
	 if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "<script>alert('Invalid phone number! Please enter a 10-digit number.'); window.history.back();</script>";
        exit;
    }
   ;
   if (empty($errors)) {
        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Prepare insert query
            $stmt = $pdo->prepare("
                INSERT INTO contacts (name, email, phone, city, status, password) 
                VALUES (:name, :email, :phone, :city, :status, :password)
            ");

            // Execute with hashed password
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':city' => $city,
                ':status' => $status,
                ':password' => $hashedPassword
            ]);

            // Redirect to login page on success
            header("Location: login.php");
            exit;

        } catch (PDOException $e) {
            echo "❌ Error: " . $e->getMessage();
        }

    } else {
        // Display validation errors
        foreach ($errors as $error) {
            echo "<script>alert('$error'); window.history.back();</script>";
        }
        exit;
    }
}
?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Add Contact</h5>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $e) echo "<div>".htmlspecialchars($e)."</div>"; ?>
      </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="<?=htmlspecialchars($_POST['name'] ?? '')?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Email</label>
        <input name="email" class="form-control" value="<?=htmlspecialchars($_POST['email'] ?? '')?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control" value="<?=htmlspecialchars($_POST['phone'] ?? '')?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">City</label>
        <input name="city" class="form-control" value="<?=htmlspecialchars($_POST['city'] ?? '')?>">
      </div>
	      <div class="col-md-6">
        <label class="form-label">Password</label>
        <input name="password" class="form-control" required  minlength="6" type="password"value="<?=htmlspecialchars($_POST['password'] ?? '')?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
      </div>
      <div class="col-12">
        <button class="btn btn-primary">Save</button>
        <a class="btn btn-secondary" href="index.php">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require 'footer.php'; ?>