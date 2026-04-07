const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("password");
const loginForm = document.getElementById("loginForm");
const phoneInput = document.getElementById("phone");

togglePassword.addEventListener("click", function () {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    togglePassword.textContent = "🙈";
  } else {
    passwordInput.type = "password";
    togglePassword.textContent = "👁";
  }
});

loginForm.addEventListener("submit", function (e) {
  e.preventDefault();

  const phone = phoneInput.value.trim();
  const password = passwordInput.value.trim();

  if (phone === "" || password === "") {
    alert("Please fill in all fields.");
    return;
  }

  if (!/^05\d{8}$/.test(phone)) {
    alert("Phone number must start with 05 and contain 10 digits.");
    return;
  }

  alert("Login successful!");
});

const dashboardData = {
  userName: "tala",
  totalReports: 0,
  pointsEarned: 0,
  badgeStatus: "Locked",
  reports: [
    // Example:
    // {
    //   title: "Water leak in Al Malqa",
    //   location: "Al Malqa, Riyadh",
    //   status: "Pending"
    // }
  ]
};

const welcomeText = document.getElementById("welcomeText");
const totalReports = document.getElementById("totalReports");
const pointsEarned = document.getElementById("pointsEarned");
const badgeStatus = document.getElementById("badgeStatus");
const emptyState = document.getElementById("emptyState");
const reportsList = document.getElementById("reportsList");

function getStatusClass(status) {
  const normalized = status.toLowerCase();

  if (normalized === "pending") return "status-pending";
  if (normalized === "in progress") return "status-progress";
  if (normalized === "completed") return "status-completed";

  return "status-pending";
}

function renderDashboard() {
  welcomeText.textContent = `Welcome back, ${dashboardData.userName}!`;
  totalReports.textContent = dashboardData.totalReports;
  pointsEarned.textContent = dashboardData.pointsEarned;
  badgeStatus.textContent = dashboardData.badgeStatus;

  if (dashboardData.reports.length === 0) {
    emptyState.classList.remove("hidden");
    reportsList.classList.add("hidden");
    return;
  }

  emptyState.classList.add("hidden");
  reportsList.classList.remove("hidden");

  reportsList.innerHTML = "";

  dashboardData.reports.forEach((report) => {
    const item = document.createElement("div");
    item.className = "report-item";

    item.innerHTML = `
      <div class="report-left">
        <h4>${report.title}</h4>
        <p>${report.location}</p>
      </div>
      <div class="report-meta">
        <span class="status-badge ${getStatusClass(report.status)}">${report.status}</span>
      </div>
    `;

    reportsList.appendChild(item);
  });
}

renderDashboard();

// اختيار نوع المشكلة
document.querySelectorAll(".type").forEach(el => {
  el.addEventListener("click", () => {
    document.querySelectorAll(".type").forEach(t => t.classList.remove("active"));
    el.classList.add("active");
  });
});

// اختيار severity
document.querySelectorAll(".severity button").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".severity button").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
  });
});

// عداد الأحرف
const textarea = document.getElementById("desc");
const counter = document.getElementById("counter");

textarea.addEventListener("input", () => {
  counter.textContent = `${textarea.value.length}/20 characters minimum`;
});

// رفع صورة
const uploadBox = document.getElementById("uploadBox");
const fileInput = document.getElementById("fileInput");

uploadBox.addEventListener("click", () => fileInput.click());

fileInput.addEventListener("change", () => {
  if (fileInput.files.length > 0) {
    uploadBox.innerHTML = `✔ Uploaded: ${fileInput.files[0].name}`;
  }
});

/* ---- Register Form-----
const form = document.getElementById("registerForm");
const errorText = document.getElementById("errorText");

form.addEventListener("submit", function(e) {
  e.preventDefault();

  const inputs = form.querySelectorAll("input");
  let isValid = true;

  inputs.forEach(input => {
    if (input.value.trim() === "") {
      isValid = false;
    }
  });

  if (!isValid) {
    errorText.textContent = "Please fill all fields";
    return;
  }

  // Clear error
  errorText.textContent = "";

  // Redirect
  window.location.href = "main.html";
});*/

