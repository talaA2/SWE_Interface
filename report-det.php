<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {

  $delete_id = $_POST['delete_id'];

  $sql = "DELETE FROM report WHERE reportID = '$delete_id'";
  $conn->query($sql);

  header("Location: MyReports.php");
  exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM report WHERE reportID = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report Details</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


<style>
.details-box {
  background: white;
  border-radius: 16px;
  padding: 25px;
  margin-top: 30px;
}

.location-table {
  width: 100%;
  margin-top: 15px;
  border-collapse: collapse;
}

.location-table td {
  padding: 12px;
  border-bottom: 1px solid #eee;
}

.label {
  color: gray;
  font-size: 13px;
}

.photo-box {
  margin-top: 15px;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #eee;
}

.photo-box img {
  width: 100%;
}

.actions {
  display: flex;
  gap: 15px;
  margin-top: 25px;
}

  .back-btn {
margin-left: 10px;
margin-top: 10px;
}

.btn-danger {
  background: white;
  border: 2px solid #e57373;
  color: #d33;
}
</style>

</head>
<header class="topbar" id="actionheader">

</header>


<body>


<div class="container main-content" >
  <div id="actionbtn">
    
  </div>

  <div class="details-box">

    <!-- TITLE -->
    <div style="display:flex; align-items:center; gap:10px;">
      <div class="icon">💧</div>
      <div>
        <h2 style="margin:0;">RPT-<?= $row['reportID'] ?></h2>
        <span style="color:gray;"><?= $row['type'] ?>  Issue</span>
      </div>
    </div>

    <!-- STATUS -->
    <div style="margin-top:15px;">
      <span class="badge" style="background:#eee;"><?= $row['status'] ?></span>
      <span class="badge <?= strtolower($row['severity']) ?>"> <?= $row['severity'] ?></span>
    </div>

    <!-- DESCRIPTION -->
    <div style="margin-top:20px;">
      <p class="label">DESCRIPTION</p>
      <p><?= $row['description'] ?></p>
    </div>

    <!-- LOCATION -->
    <div style="margin-top:20px;">
      <p class="label">LOCATION</p>

      <table class="location-table">
        <tr>
          <td class="label">City</td>
          <td><?= $row['city'] ?></td>
        </tr>
        <tr>
          <td class="label">Neighborhood</td>
          <td><?= $row['neighborhood'] ?></td>
        </tr>
        <tr>
          <td class="label">Street</td>
          <td><?= $row['street'] ?></td>
        </tr>
        <tr>
          <td class="label">Building</td>
          <td><?= $row['building_no'] ?></td>
        </tr>
      </table>
    </div>

    <!-- DATE -->
    <div style="margin-top:20px;">
      <p class="label">SUBMITTED</p>
      <p><?= $row['created_at'] ?></p>
    </div>

    <!-- PHOTO -->
    <div style="margin-top:20px;">
      <p class="label">PHOTO</p>

      <div class="photo-box">
        <img src="uploads/<?= $row['image'] ?>">
      </div>
    </div>

    <!-- ACTIONS -->
    <div class="actions" id="actionsBox">
      
    </div>

  </div>

</div>

<!-- JS -->
<script>

const params = new URLSearchParams(window.location.search);
const role = params.get("role");

const actionsBox = document.getElementById("actionsBox");
const actionheader = document.getElementById("actionheader");
const actionbtn = document.getElementById("actionbtn");

if (role === "admin") {

  actionsBox.innerHTML = `
    <select class="btn">
      <option>Pending</option>
      <option>In Progress</option>
      <option>Completed</option>
    </select>
  `;

  actionheader.innerHTML = `
    <div class="container topbar-inner">

    <div class="brand">
      <a href ="admin-dashboard.php"><img src="images/logo.png" alt="Logo"></a>
      <span class="brand-text"><a href ="admin-dashboard.php">Rasheed</span></a>
    </div>
    <nav class="nav-links">
      <a href="index.php" class="nav-link logout">
    <i class="fa-solid fa-right-from-bracket"></i> Log Out
  </a>
</nav>
  </div>
  `;

  actionbtn.innerHTML = `
  <h1 class="page-title" id="actionbtn">Report Details</h1>
    <a href="admin-dashboard.php" class="back-btn">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
   `;

} else {

  actionsBox.innerHTML = `
  <button class="btn" onclick="window.location.href='EditReport.php?id=<?= $row['reportID'] ?>'">✏️ Edit Report</button>
    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')">
  <input type="hidden" name="delete_id" value="<?= $row['reportID'] ?>">
  <button type="submit" class="btn btn-danger">🗑 Delete Report</button>
</form>
`;

actionheader.innerHTML = `
    <div class="container topbar-inner">

    <div class="brand">
      <a href ="main.php"><img src="images/logo.png" alt="Logo"></a>
      <span class="brand-text"><a href ="main.php">Rasheed</span></a>
    </div>
    <nav class="nav-links">
      <a href="AddReport.php" class="nav-link">
    <i class="fa-regular fa-file-lines"></i> Add Report
  </a>

  <a href="MyReports.php" class="nav-link active">
    <i class="fa-regular fa-clipboard"></i> My Reports
  </a>

  <a href="rewards.php" class="nav-link">
    <i class="fa-regular fa-star"></i> Rewards
  </a>

  <a href="Notifications.php" class="nav-link">
    <i class="fa-regular fa-bell"></i> Notifications
  </a> 
      <a href="index.php" class="nav-link logout">
    <i class="fa-solid fa-right-from-bracket"></i> Log Out
  </a>
</nav>
  </div>
  `;

  actionbtn.innerHTML = `
  <h1 class="page-title" id="actionbtn">Report Details</h1>
<a href="MyReports.php" class="back-btn">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
`;
}

  function goToEdit() {
  window.location.href = "edit-report.php?id=RPT-<?= $row['reportID'] ?>";
}

/*function deleteReport() {

  let confirmDelete = confirm("Are you sure you want to delete this report?");

  if (confirmDelete) {

    let msg = document.createElement("p");
    msg.textContent = "Report deleted successfully!";
    msg.className = "status-text completed";

    document.querySelector(".actions").appendChild(msg);

    setTimeout(() => {
      window.location.href = "MyReports.php";
    }, 600);

  }

}*/

</script>
<footer class="footer">
  <div class="footer-container">

    <div class="footer-left">
      <h3>Rasheed</h3>
      <p>Helping communities report water and electricity issues efficiently.</p>
    </div>


    <div class="footer-copy">
      <p>© 2026 Rasheed. All rights reserved.</p>
    </div>

  </div>
</footer>
</body>
</html>
