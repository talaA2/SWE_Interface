<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);
$phone = trim($_POST['phoneNumber']);
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'] ?? '';

// 1️⃣ تحقق الحقول
if (empty($firstName) || empty($lastName) || empty($phone) || empty($password)) {
    header("Location: register.php?error=Please fill all fields");
    exit();
}

// 2️⃣ تحقق رقم الجوال (سعودي)
if (!preg_match("/^05[0-9]{8}$/", $phone)) {
    header("Location: register.php?error=Invalid phone number format");
    exit();
}

// 3️⃣ تحقق الباسورد
$hasUppercase = preg_match('@[A-Z]@', $password);
$hasNumber = preg_match('@[0-9]@', $password);

if (strlen($password) < 8 || !$hasUppercase || !$hasNumber) {
    header("Location: register.php?error=Password must be 8 chars, include capital letter and number");
    exit();
}

// 4️⃣ تأكيد الباسورد
if ($password !== $confirmPassword) {
    header("Location: register.php?error=Passwords do not match");
    exit();
}

// 5️⃣ تحقق التكرار
$sqlCheck = "SELECT * FROM user WHERE phoneNumber = '$phone'";
$result = $conn->query($sqlCheck);

if ($result->num_rows > 0) {
    header("Location: register.php?error=Phone already exists");
    exit();
}

// 6️⃣ تشفير الباسورد
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 7️⃣ إدخال user
$sqlUser = "INSERT INTO user (firstName, lastName, phoneNumber, password, role)
            VALUES ('$firstName', '$lastName', '$phone', '$hashedPassword', 'resident')";

if ($conn->query($sqlUser)) {

    $userID = $conn->insert_id;

    // 8️⃣ إدخال resident
    $sqlResident = "INSERT INTO resident (residentID, points)
                    VALUES ($userID, 0)";
    $conn->query($sqlResident);

    // 9️⃣ session
    $_SESSION['userID'] = $userID;
    $_SESSION['role'] = "resident";

    // 🔟 redirect
    header("Location: main.php");
    exit();

} else {
    header("Location: register.php?error=Something went wrong");
}
?>