 <?php
// // config.php
// $host = '127.0.0.1';
// $db   = 'demo_crud';
// $user = 'root';
// $pass = ''; // set your DB password
// $charset = 'utf8mb4';

// $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// $options = [
    // PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
// ];

// try {
    // $pdo = new PDO($dsn, $user, $pass, $options);
// } catch (\PDOException $e) {
    // exit('DB connection failed: ' . $e->getMessage());
// }
?>

  <?php
// // config.php - mysqli version
// $host = "localhost";
// $user = "root";
// $pass = "";      // set DB password if you have one
// $db   = "demo_crud";
// $port = 3306;    // change if you run MySQL on another port

// // Create mysqli connection and check for errors
// $conn = new mysqli($host, $user, $pass, $db, $port);

// if ($conn->connect_errno) {
    // // Friendly error message for local dev
    // die("Database connection failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
// }

// // Set charset
// $conn->set_cha */rset("utf8mb4");
// ?>

<?php
// config.php — provides BOTH mysqli ($conn) and PDO ($pdo)
// so parts of the app that use either driver will work.

$host = "127.0.0.1";
$user = "root";
$pass = "";        // set your MySQL root password if any
$db   = "demo_crud";
$port = 3306;      // change if needed
$charset = 'utf8mb4';

// ----------------- mysqli connection ($conn) -----------------
$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_errno) {
    die("MySQLi connection failed: (" . $conn->connect_errno . ") " . $conn->connect_error);
}
$conn->set_charset($charset);

// ----------------- PDO connection ($pdo) -----------------
$dsn = "mysql:host={$host};dbname={$db};port={$port};charset={$charset}";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // If PDO cannot connect we still want mysqli working (so show a friendly message)
    die("PDO connection failed: " . $e->getMessage());
}
?>

