<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

if (!isset($_POST['reportID']) || !isset($_POST['status'])) {
    die("Missing data.");
}

$reportID = (int) $_POST['reportID'];
$newStatus = trim($_POST['status']);

/* حسب جدولك: القيم Capital */
$allowedStatuses = ["Pending", "In Progress", "Completed", "Deleted"];

if (!in_array($newStatus, $allowedStatuses)) {
    die("Invalid status.");
}

/* جدول report عندك فيه residentID مو userID */
$sql = "SELECT residentID, status, type FROM report WHERE reportID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $reportID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Report not found.");
}

$row = $result->fetch_assoc();

$residentID = (int) $row['residentID'];
$oldStatus = $row['status'];
$type = $row['type'];

/* إذا مكتمل من قبل، لا نحدث ولا نزيد نقاط */
if ($oldStatus === "Completed") {
    header("Location: admin.php?error=This report is already completed");
    exit();
}

/* تحديث الحالة */
$updateSql = "UPDATE report SET status = ? WHERE reportID = ?";
$updateStmt = $conn->prepare($updateSql);

if (!$updateStmt) {
    die("Prepare failed: " . $conn->error);
}

$updateStmt->bind_param("si", $newStatus, $reportID);
$updateStmt->execute();

/* تجهيز الإشعار */
$message = "";
$notificationType = "";

if ($newStatus === "Pending") {
    $notificationType = "submitted";
    $message = "Your report has been received and is waiting for review.";
}

if ($newStatus === "In Progress") {
    $notificationType = "in_progress";
    $message = "Your " . strtolower($type) . " issue is currently being reviewed.";
}

if ($newStatus === "Completed") {
    $notificationType = "completed";
    $message = "Your " . strtolower($type) . " report has been resolved. You earned 10 points.";

    /* زيادة النقاط للمستخدم */
$pointsSql = "UPDATE resident SET points = points + 10 WHERE residentID = ?";
$pointsStmt = $conn->prepare($pointsSql);

    if (!$pointsStmt) {
        die("Prepare failed: " . $conn->error);
    }

    $pointsStmt->bind_param("i", $residentID);
    $pointsStmt->execute();
}

if ($newStatus === "Deleted") {
    $notificationType = "deleted";
    $message = "Your report has been removed. No points were awarded.";
}

/* إضافة إشعار */
$notifSql = "INSERT INTO notification (residentID, reportID, message, type) VALUES (?, ?, ?, ?)";
$notifStmt = $conn->prepare($notifSql);

if (!$notifStmt) {
    die("Prepare failed: " . $conn->error);
}

$notifStmt->bind_param("iiss", $residentID, $reportID, $message, $notificationType);
$notifStmt->execute();
$notifStmt = $conn->prepare($notifSql);

if (!$notifStmt) {
    die("Prepare failed: " . $conn->error);
}

$notifStmt->bind_param("iiss", $residentID, $reportID, $message, $notificationType);
$notifStmt->execute();

header("Location: admin.php?success=Report status updated successfully");
exit();
?>