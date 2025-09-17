<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include("connection.php");
$obj = new Connection();
$connect = $obj->connect();
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $select = mysqli_query($connect, "SELECT * FROM employees WHERE emp_id='$email' OR official_email='$email'");
  $result = mysqli_num_rows($select);
  if ($result > 0) {
    $row = mysqli_fetch_assoc($select);
    if (password_verify($password, $row["password"])) {
      $_SESSION['emp_id'] = $row['emp_id'];
      $_SESSION['email'] = $row['official_email'];
      echo "<script>
                alert('Login successful!');
                window.location.href='dashboard.php';
            </script>";
    } else {
      echo "<script>
                alert('Incorrect password!');
                window.history.back();
            </script>";
    }
  } else {
    echo "<script>
            alert('Employee ID or Email not found!');
            window.history.back();
        </script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login • Staff Banking Panel</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <section class="d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="card p-4 p-md-5" style="max-width: 480px; width: 100%;">
      <div class="text-center mb-4">
        <div class="display-6 fw-extrabold text-primary mb-2"><i class="fa-solid fa-building-columns"></i></div>
        <h1 class="h4 mb-1">Staff Login</h1>
        <p class="text-muted small">Authorized bank employees only</p>
      </div>
      <!-- LOGIN FORM (HTML/CSS only; backend handles auth) -->
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Employee ID / Official Email</label>
          <input type="text" class="form-control" placeholder="e.g., EMP-1023 or name@bank.com" name="email" required>
        </div>
        <div class="mb-3">
          <label class="form-label d-flex justify-content-between">
            <span>Password</span>
            <a href="#" class="small">Forgot?</a>
          </label>
          <input type="password" class="form-control" placeholder="••••••••" name="password" required>
        </div>
        <div class="d-grid mt-4">
          <button type="submit" name="login" class="btn btn-primary btn-lg">Login</button>
        </div>
        <div class="text-center mt-3 small">
          New hire? <a href="signup.php">Register as Employee</a>
        </div>
      </form>
    </div>
  </section>
  </main>
  </div>
  </div>

  <footer class="footer border-top py-3 bg-white">
    <div class="container-fluid text-center text-muted small">
      © 2025 Staff Banking Panel. UI for internal employee use only.
    </div>
  </footer>
  <!-- Bootstrap JS (optional, used only for components that need it; no custom JS is required) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>