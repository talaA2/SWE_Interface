<?php
include "db.php";
if (!isset($_SESSION['userID'])) {
  header("Location: login.php");
  exit();
}
if (!isset($_GET['id'])) {
  die("No report selected");
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $desc = $_POST['description'];
  $city = $_POST['city'];
  $neighborhood = $_POST['neighborhood'];
  $street = $_POST['street'];
  $building = $_POST['building'];
  $severity = $_POST['severity'];

  $old = $conn->query("SELECT image FROM report WHERE reportID='$id'");
  $oldRow = $old->fetch_assoc();
  $imageName = $oldRow['image'];

  if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $targetDir = "uploads/";
    $imageName = time() . "_" . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $targetDir . $imageName);
  }

  $sql = "UPDATE report SET
    description='$desc',
    city='$city',
    neighborhood='$neighborhood',
    street='$street',
    building_no='$building',
    severity='$severity',
    image='$imageName'
    WHERE reportID='$id'";

  $conn->query($sql);

  header("Location: report-det.php?id=$id&updated=1");
  exit();
}

$sql = "SELECT * FROM report WHERE reportID = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Reports</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>
<header class="topbar">
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

  <a href="logout.php" class="nav-link logout">
    <i class="fa-solid fa-right-from-bracket"></i> Log Out
  </a>
</nav>

  </div>
</header>

<body class="edit-report-page">

  <div class="edit-report-wrapper">
    <h1 class="page-title">Edit Report</h1>
    <a href="report-det.php?id=<?= $row['reportID'] ?>" class="back-btn">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>

    <div class="edit-report-card">
      <div class="edit-report-header">
        <div class="edit-report-icon">
          <i class="fa-solid fa-droplet"></i>
        </div>

        <div>
          <h1 class="edit-report-id">RPT-<?= $row['reportID'] ?></h1>
          <p class="edit-report-type"><?= $row['type'] ?> Issue</p>
        </div>
      </div>

      <form id="editReportForm" method="POST" enctype="multipart/form-data">
        <h3 class="edit-section-title">Edit Description</h3>
        <textarea id="description" name="description"><?= $row['description'] ?></textarea>

        <h3 class="edit-section-title">Edit Location</h3>
        <div class="edit-location-grid">
          <div class="edit-field">
            <label for="city">City</label>
            <input type="text" id="city" name="city" value="<?= $row['city'] ?>">
          </div>

          <div class="edit-field">
            <label for="neighborhood">Neighborhood</label>
            <input type="text" id="neighborhood" name="neighborhood" value="<?= $row['neighborhood'] ?>">
          </div>

          <div class="edit-field">
            <label for="street">Street</label>
            <input type="text" id="street" name="street" value="<?= $row['street'] ?>">
          </div>

          <div class="edit-field">
            <label for="building">Building</label>
            <input type="text" id="building" name="building" value="<?= $row['building_no'] ?>">
          </div>
          <input type="hidden" name="severity" id="severityInput" value="<?= $row['severity'] ?>">
        </div>

        <h3 class="edit-section-title">Severity Level</h3>
        <div class="edit-severity-row">
          <button type="button" class="edit-severity-btn <?= $row['severity']=='Low'?'active':'' ?>">Low</button>
          <button type="button" class="edit-severity-btn <?= $row['severity']=='Medium'?'active':'' ?>">Medium</button>
          <button type="button" class="edit-severity-btn <?= $row['severity']=='High'?'active':'' ?>">High</button>
        </div>

        <h3 class="edit-section-title">Update Photo</h3>
        <div class="edit-upload-box" id="uploadBox">
          <div class="edit-current-photo">
            <img id="currentPhoto" src="uploads/<?= $row['image'] ?>" alt="Report Image">
          </div>
          <i class="fa-solid fa-cloud-arrow-up"></i>
          <div class="edit-upload-main" id="uploadMain">Click to upload a new image</div>
          <div class="edit-upload-sub">PNG, JPG up to 5MB</div>
          <input type="file" id="photoInput" name="photo" accept=".png,.jpg,.jpeg" hidden>
        </div>

        <div class="edit-actions">
          <button type="submit" class="save-btn" >
            <i class="fa-solid fa-floppy-disk"></i> Save Changes
          </button>

          <a href="report-det.php?id=<?= $row['reportID'] ?>" class="cancel-btn">
            Cancel
          </a>
        </div>

        <p class="edit-success-message" id="successMessage"></p>
      </form>
    </div>
  </div>

  <script>
    const uploadBox = document.getElementById("uploadBox");
    const photoInput = document.getElementById("photoInput");
    const uploadMain = document.getElementById("uploadMain");
    const successMessage = document.getElementById("successMessage");

    uploadBox.addEventListener("click", () => {
      photoInput.click();
    });

    const currentPhoto = document.getElementById("currentPhoto");

photoInput.addEventListener("change", () => {
  if (photoInput.files.length > 0) {
    const file = photoInput.files[0];

    uploadMain.textContent = file.name;

    const reader = new FileReader();
    reader.onload = function(e) {
      currentPhoto.src = e.target.result;
    };

    reader.readAsDataURL(file);
  }
});

    const severityButtons = document.querySelectorAll(".edit-severity-btn");
    severityButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        severityButtons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        document.getElementById("severityInput").value = btn.textContent.trim();
      });
    });

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
