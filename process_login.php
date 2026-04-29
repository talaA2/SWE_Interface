<?php
session_start();
include "db.php";

$phone = $_POST['phoneNumber'];
$password = $_POST['password'];

if (empty($phone) || empty($password)) {
    header("Location: login.php?error=Please fill all fields");
    exit();
}

$sql = "SELECT * FROM user WHERE phoneNumber = '$phone'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: login.php?error=User not found");
    exit();
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password'])) {
    header("Location: login.php?error=Wrong password");
    exit();
}

$_SESSION['userID'] = $user['userID'];
$_SESSION['role'] = $user['role'];

if ($user['role'] == 'admin') {
    header("Location: admin.php");
    exit();
} 
elseif ($user['role'] == 'resident') {
    header("Location: main.php");
    exit();
} 
else {
    header("Location: login.php?error=Invalid role");
    exit();
}
?>
