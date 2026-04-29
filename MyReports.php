<?php
session_start();
include "db.php";

if (!isset($_SESSION['userID'])) {
  header("Location: login.php");
  exit();
}

$residentID = $_SESSION['userID'];

$sql = "SELECT * FROM report 
        WHERE residentID = '$residentID' 
        AND NOT (status = 'Deleted' AND deletedByUser = 1)
        ORDER BY reportID DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Reports</title>

<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

</head>

<body>

<!-- NAVBAR -->
<header class="topbar">
    <div class="container topbar-inner">
      <div class="brand">
        <a href ="main.php"><img src="images/logo.png" alt="Logo"></a>
      <span class="brand-text"><a href ="main.php">Rasheed</span></a>
      </div>

      <nav class="nav-links">
        <a href="AddReport.php" class="nav-link "><i class="fa-regular fa-file-lines"></i> Add Report</a>
        <a href="MyReports.php" class="nav-link active"><i class="fa-regular fa-clipboard"></i> My Reports</a>
        <a href="Rewards.php" class="nav-link"><i class="fa-regular fa-star"></i> Rewards</a>
        <a href="Notifications.php" class="nav-link"><i class="fa-regular fa-bell"></i> Notifications</a>
        <a href="index.php" class="nav-link logout"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
      </nav>
    </div>
  </header>


<!-- MAIN -->
<div class="container main-content">

  <h1 class="page-title">My Reports</h1>
  <p class="page-subtitle">View and manage your submitted reports.</p>

  <!-- FILTER -->
  <div class="search-row">

<input type="text" id="searchInput" placeholder="Search reports...">

    <select id="typeFilter">
      <option value="all">All Types</option>
      <option value="water">Water</option>
      <option value="electricity">Electricity</option>
    </select>

    <select id="severityFilter">
      <option value="all">All Severity</option>
      <option value="low">Low</option>
      <option value="medium">Medium</option>
      <option value="high">High</option>
    </select>

  </div>

  <?php while($row = $result->fetch_assoc()): ?>

<a href="report-det.php?id=<?= $row['reportID'] ?>"
   class="report-card"
   data-type="<?= strtolower($row['type']) ?>"
   data-severity="<?= strtolower($row['severity']) ?>">

  <div class="icon">
    <?= $row['type'] == "Water" ? "💧" : "⚡" ?>
  </div>

  <div class="info">
    <div class="top">
      <b>RPT-<?= $row['reportID'] ?></b>
      <span class="badge <?= strtolower($row['severity']) ?>">
        <?= $row['severity'] ?>
      </span>
    </div>

    <div class="meta"><?= $row['description'] ?></div>
    <div class="meta"><?= $row['city'] ?></div>
  </div>

  <div class="status-text <?= strtolower($row['status']) ?>">
    <?= $row['status'] ?>
  </div>

</a>

<?php endwhile; ?>

  <!-- REPORTS -->
<!--
  <a href="report-det.html?role=user"
     class="report-card"
     data-type="water"
     data-severity="medium">

    <div class="icon">💧</div>

    <div class="info">
      <div class="top">
        <b>RPT-01</b>
        <span class="badge medium">Medium</span>
      </div>

      <div class="meta">Water leakage near house</div>
      <div class="meta">Riyadh • Apr 4, 2026</div>
    </div>

    <div class="status-text pending">Pending</div>

  </a>

  <a href="report-det2.html?role=user"
     class="report-card"
     data-type="electricity"
     data-severity="high">

    <div class="icon">⚡</div>

    <div class="info">
      <div class="top">
        <b>RPT-02</b>
        <span class="badge high">High</span>
      </div>

      <div class="meta">Power outage in building</div>
      <div class="meta">Jeddah • Apr 3, 2026</div>
    </div>

    <div class="status-text progress">In Progress</div>

  </a>

  <a href="report-det3.html?role=user"
     class="report-card"
     data-type="water"
     data-severity="low">

    <div class="icon">💧</div>

    <div class="info">
      <div class="top">
        <b>RPT-03</b>
        <span class="badge low">Low</span>
      </div>

      <div class="meta">Small pipe issue</div>
      <div class="meta">Dammam • Apr 2, 2026</div>
    </div>

    <div class="status-text completed">Completed</div>

  </a> 

</div> -->

<!-- JS -->
<script>
window.addEventListener("DOMContentLoaded", () => {

const typeFilter = document.getElementById("typeFilter");
const severityFilter = document.getElementById("severityFilter");
const cards = document.querySelectorAll(".report-card");

function filterReports() {

  const type = typeFilter.value;
  const severity = severityFilter.value;

  cards.forEach(card => {

    const cardType = card.getAttribute("data-type");
    const cardSeverity = card.getAttribute("data-severity");

    let show = true;

    if (type !== "all" && cardType !== type) {
      show = false;
    }

    if (severity !== "all" && cardSeverity !== severity) {
      show = false;
    }

    card.style.display = show ? "flex" : "none";

  });

}

typeFilter.addEventListener("change", filterReports);
severityFilter.addEventListener("change", filterReports);

    const searchInput = document.getElementById("searchInput");

searchInput.addEventListener("input", function () {

  const value = searchInput.value.toLowerCase();

  cards.forEach(card => {
    const text = card.innerText.toLowerCase();

    if (text.includes(value)) {
      card.style.display = "flex";
    } else {
      card.style.display = "none";
    }

  }); 
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
