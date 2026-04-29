<?php
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - Rasheed</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="style.css">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Inter", sans-serif;
      background: linear-gradient(to bottom, #eef8f2, #e4f4eb);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      width: 100%;
      max-width: 500px;
      padding: 20px;
    }

    .card {
      background: white;
      border-radius: 18px;
      padding: 30px 28px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      text-align: center;
    }

    .logo {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      font-weight: 800;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .logo i {
      color: #22a06b;
    }

    .logo span {
      background: linear-gradient(270deg, #2563eb, #22a06b, #2563eb);
      background-size: 200% 200%;
      -webkit-background-clip: text;
      background-clip: text;      
      -webkit-text-fill-color: transparent;
    }

    h2 {
      margin: 10px 0 4px;
      font-size: 20px;
      font-weight: 700;
    }

    .subtitle {
      font-size: 14px;
      color: #6b7280;
      margin-bottom: 22px;
    }

    .form-group {
      text-align: left;
      margin-bottom: 16px;
    }

    .form-group label {
      font-size: 13px;
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
      color: #374151;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #d1d5db;
      font-family: inherit;
      font-size: 14px;
      transition: 0.2s;
    }

    .form-group input:focus {
      outline: none;
      border-color: #22a06b;
      box-shadow: 0 0 0 3px rgba(34,160,107,0.1);
    }

    .btn {
      width: 100%;
      margin-top: 10px;
      padding: 12px;
      border-radius: 10px;
      border: none;
      background: #22a06b;
      color: white;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: 0.2s;
    }

    .btn:hover {
      background: #1d8b5d;
      transform: translateY(-2px);
    }

    .login-link {
      margin-top: 14px;
      font-size: 13px;
      color: #6b7280;
    }

    .login-link a {
      color: #22a06b;
      font-weight: 600;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }
    .logo img {
        width: auto;
        height: 80px;
        margin-bottom: 10px;
    }

  </style>
</head>

<body>

  <div class="container">
    <div class="card">

      <div class="logo">
        <img src="images/logo.png" alt="Rasheed Logo">
      </div>

      <h2>Create your account</h2>
      <p class="subtitle">Join Rasheed and start reporting</p>

      <!-- 🔥 هنا التعديل -->
      <form id="registerForm" method="POST" action="process_register.php">

        <div style="display: flex; gap: 10px;">

        <div class="form-group" style="flex: 1;">
            <label>First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="First name">
        </div>

        <div class="form-group" style="flex: 1;">
            <label>Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="Last name">
        </div>

        </div>

        <div class="form-group">
          <label>Phone Number</label>
          <input type="text" id="phone" name="phoneNumber" placeholder="05XXXXXXXX">
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" id="password" name="password" placeholder="Create a password">
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password">
        </div>

        <?php if ($error): ?>
          <p style="color:red; font-size:13px; margin-top:5px;">
            <?php echo htmlspecialchars($error); ?>
          </p>
        <?php endif; ?>

        <p id="errorMsg" style="color: red; font-size: 13px; display: none; margin-top: 5px;">
            Please fill all fields
        </p>

        <button type="submit" class="btn">Create Account</button>

      </form>

      <div class="login-link">
        Already have an account? <a href="login.php">Log in</a>
      </div>

    </div>
  </div>

<script>
  const form = document.getElementById("registerForm");
  const errorMsg = document.getElementById("errorMsg");

  form.addEventListener("submit", function (e) {

    const firstName = document.getElementById("firstName").value.trim();
    const lastName = document.getElementById("lastName").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    errorMsg.style.display = "block";

    if (
      firstName === "" ||
      lastName ==="" ||
      phone === "" ||
      password === "" ||
      confirmPassword === ""
    ) {
      e.preventDefault();
      errorMsg.textContent = "Please fill all fields";
      return;
    }

    const hasUppercase = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);

    if (password.length < 8 || !hasUppercase || !hasNumber) {
      e.preventDefault();
      errorMsg.textContent =
        "Password must be at least 8 characters and include a capital letter and a number";
      return;
    }

    if (password !== confirmPassword) {
      e.preventDefault();
      errorMsg.textContent = "Passwords do not match";
      return;
    }

    errorMsg.style.display = "none";
  });
</script>

</body>
</html>