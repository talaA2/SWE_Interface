<?php
session_start();
include "db.php";

// استقبال البيانات
$phone = $_POST['phoneNumber'];
$password = $_POST['password'];
$isAdmin = $_POST['isAdmin'];

// تحقق مبدئي
if (empty($phone) || empty($password)) {
    header("Location: login.php?error=Please fill all fields");
    exit();
}

// البحث عن المستخدم
$sql = "SELECT * FROM user WHERE phoneNumber = '$phone'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: login.php?error=User not found");
    exit();
}

$user = $result->fetch_assoc();

// التحقق من الباسورد
if (!password_verify($password, $user['password'])) {
    header("Location: login.php?error=Wrong password");
    exit();
}

// ============================
// 🎯 التحقق من الأدمن
// ============================
if ($isAdmin == "1") {

    if ($user['role'] == 'admin') {

        $_SESSION['userID'] = $user['userID'];
        $_SESSION['role'] = 'admin';

        header("Location: admin.php");
        exit();

    } else {
        header("Location: login.php?error=You are not admin");
        exit();
    }
}

// ============================
// 👤 المستخدم العادي
// ============================
if ($user['role'] == 'resident') {

    $_SESSION['userID'] = $user['userID'];
    $_SESSION['role'] = 'resident';

    header("Location: main.php");
    exit();

} else {
    header("Location: login.php?error=Invalid role");
    exit();
}
?>