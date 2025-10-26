<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM contacts WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['contacts'] = $row['name'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
 
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<link href ="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" ></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" ></script>

<style>
body {
    background-color: #ECEFF1;
    font-family: 'Poppins', sans-serif;
}

/* Card Layout */
.card0 {
    box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
    border-radius: 10px;
    overflow: hidden;
}

/* Left image section */
.logo {
    width: 180px;
    margin: 30px auto;
    display: block;
}

.image {
    width: 100%;
    max-width: 380px;
    display: block;
    margin: 20px auto;
}

/* Form section */
.card2 {
    padding: 40px;
}

h1 {
    font-size: 26px;
    font-weight: 600;
    text-align: center;
    color: #1A237E;
}

/* Social Icons */
.facebook, .twitter, .linkedin {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    margin: 0 10px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.facebook { background-color: #3b5998; }
.twitter { background-color: #1DA1F2; }
.linkedin { background-color: #2867B2; }

.facebook:hover, .twitter:hover, .linkedin:hover {
    transform: scale(1.1);
}

/* Input fields */
input {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    width: 100%;
    margin-bottom: 15px;
    transition: border-color 0.3s;
}

input:focus {
    border-color: #1A237E;
    outline: none;
}

/* Button */
.submit_button {
    background-color: #1A237E;
    color: #fff;
    border: none;
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    transition: background 0.3s;
}

.submit_button:hover {
    background-color: #0D47A1;
    color: #fff;
}

/* Footer */
.bg-blue {
    background-color: #1A237E;
    color: #fff;
    text-align: center;
    padding: 15px;
    margin-top: 30px;
}

.social-contact span {
    margin: 0 10px;
    font-size: 18px;
    cursor: pointer;
}
.remember{
position:relative;
top:0px;	

}
.remember input{
	position:relative;
	left:-50px;
}
.remember label{
	position:relative;
	top:-35px;
	left:30px;
	color:#007bff;
}
/* ✅ RESPONSIVE DESIGN */
@media (max-width: 992px) {
    .image {
        max-width: 300px;
    }
    .card2 {
        padding: 30px 20px;
    }
    h1 {
        font-size: 22px;
    }
}

@media (max-width: 768px) {
    .row.d-flex {
        flex-direction: column-reverse;
    }
    .card1 {
        text-align: center;
    }
    .image {
        max-width: 280px;
    }
    .logo {
        width: 150px;
    }
}

@media (max-width: 480px) {
    .card2 {
        padding: 20px;
    }
    .submit_button {
        font-size: 14px;
        padding: 8px;
    }
    h1 {
        font-size: 20px;
    }
}
</style>
</head>

<body>
<div class="container py-5">
    <div class="card card0 border-0">
        <div class="row d-flex align-items-center">
            <div class="col-lg-6 bg-white">
                <img src="../crud_app/images/logo_shree_jagannath_university-0ne.png" class="logo" alt="Logo">
                <img src="https://i.imgur.com/uNGdWHi.png" class="image" alt="Login Illustration">
            </div>
            <div class="col-lg-6 bg-light">
                <div class="card2">
                    <h1>Sign in</h1>
                    <div class="d-flex justify-content-center my-3">
                        <div class="facebook"><i class="fa fa-facebook"></i></div>
                        <div class="twitter"><i class="fa fa-twitter"></i></div>
                        <div class="linkedin"><i class="fa fa-linkedin"></i></div>
                    </div>

                    <div class="text-center mb-3 text-muted">Or login with your email</div>

                    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                    <form method="POST">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" required>

                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>

                        <div class="d-flex justify-content-between mb-3">
                            <div class="remember">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember me</label>
                            </div>
                            <a href="#">Forgot Password?</a>
                        </div>

                        <button type="submit" class="submit_button">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <small>Don't have an account? <a href="register.php" class="text-primary">Register</a></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-blue">
            <div class="social-contact mb-2">
                <span class="fa fa-facebook"></span>
                <span class="fa fa-google-plus"></span>
                <span class="fa fa-linkedin"></span>
                <span class="fa fa-twitter"></span>
            </div>
            <small>&copy; 2025 All rights reserved.</small>
        </div>
    </div>
</div>
</body>
</html>
