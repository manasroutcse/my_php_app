 
<?php
require 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = $conn->prepare("SELECT id FROM contacts WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO contacts (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash);
            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #ECEFF1;
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    border-radius: 10px;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
    padding: 30px;
    max-width: 420px;
    width: 100%;
    background-color: #fff;
}

h3 {
    text-align: center;
    font-weight: 600;
    color: #1A237E;
    margin-bottom: 20px;
}

.form-label {
    font-weight: 500;
    color: #333;
}

input.form-control {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    transition: border-color 0.3s ease;
}

input.form-control:focus {
    border-color: #1A237E;
    box-shadow: none;
}

.btn-primary {
    background-color: #1A237E;
    border: none;
    padding: 10px;
    font-weight: 500;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background-color: #0D47A1;
}

.alert {
    font-size: 14px;
    text-align: center;
}

p {
    margin-top: 15px;
    text-align: center;
    font-size: 14px;
}

p a {
    color: #1A237E;
    text-decoration: none;
    font-weight: 500;
}

p a:hover {
    text-decoration: underline;
}

/* ✅ Responsive tweaks */
@media (max-width: 576px) {
    .card {
        padding: 20px;
        margin: 15px;
    }
    h3 {
        font-size: 22px;
    }
    .btn-primary {
        font-size: 14px;
        padding: 8px;
    }
}
</style>
</head>

<body>
<div class="card">
  <h3>Register</h3>
  
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control" required minlength="6">
    </div>
    <button type="submit" class="btn btn-primary w-100">Register</button>
  </form>

  <p>Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
