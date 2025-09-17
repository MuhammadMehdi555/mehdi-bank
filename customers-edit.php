<?php
session_start();
if(!isset($_SESSION["emp_id"])|| !isset( $_SESSION["email"])){
    echo "<script>
            alert('Please login first!');
            window.location.href='login.php';
          </script>";
  exit();
}
if (isset($_POST['logout'])) {
  session_destroy();
  session_unset();
  echo "<script>
        alert('You have been logged out successfully!');
        window.location.href='login.php';
      </script>";
}
$emp_id = $_SESSION["emp_id"];
include "connection.php";
$obj = new Connection();
$connect = $obj->connect();

$email = mysqli_query($connect,"SELECT * FROM employees WHERE emp_id='$emp_id' ");
$email_row = mysqli_fetch_assoc($email);

$id = $_GET["id"];
$select_row = mysqli_query($connect,"SELECT * FROM customers WHERE id='$id' ");
$row= mysqli_fetch_assoc($select_row);
if(isset($_POST["update"])){
$name = !empty($_POST['name'])? $_POST['name']:$row['full_name'];
$cnic = !empty($_POST['cnic'])? $_POST['cnic']:$row['cnic'];
$account_type = !empty($_POST['account_type'])? $_POST['account_type']:$row['account_type'];
$status = isset($_POST['status']) ? $_POST['status'] : $row['status'];
$phone = !empty($_POST['phone'])? $_POST['phone']:$row['phone'];
$email = !empty($_POST['email'])? $_POST['email']:$row['email'];
$address = !empty($_POST['address'])? $_POST['address']:$row['address'];
$select_cnic = mysqli_query($connect,"SELECT * FROM customers WHERE cnic='$cnic' AND id !='$id'");
$chack_cnic= mysqli_num_rows($select_cnic);
$select_email = mysqli_query($connect,"SELECT * FROM customers WHERE email='$email' AND id !='$id'");
$chack_email= mysqli_num_rows($select_email);
$select_phone = mysqli_query($connect,"SELECT * FROM customers WHERE phone='$phone' AND id !='$id'");
$chack_phone= mysqli_num_rows($select_phone);
if($chack_cnic>0 && $chack_email>0 && $chack_phone> 0){
echo "<script>
        alert('Email,CNIC or Phone is already registered!');
        window.location.href='customers-edit.php?id=".$id."';
      </script>";
    exit();
}
if($chack_cnic>0 && $chack_phone> 0){
echo "<script>
        alert('CNIC or Phone is already registered!');
        window.location.href='customers-edit.php?id=".$id."';
      </script>";
    exit();
}
if($chack_cnic>0 && $chack_email> 0){
echo "<script>
        alert('Email or CNIC is already registered!');
        window.location.href='customers-edit.php?id=".$id."';
      </script>";
    exit();
}
if($chack_email>0 && $chack_phone>0){
echo "<script>
        alert('Email, Phone is already registered!');
        window.location.href='customers-edit.php?id=".$id."';
      </script>";
    exit();
}
if($chack_cnic>0){
echo "<script>
        alert('CNIC is already registered!');
        window.location.href='customers-edit.php?id=".$id."';
      </script>";
    exit();
}
if($chack_email>0){
echo "<script>
        alert('Email is already registered!');
        window.location.href='customers-edit.php?id=".$id."';
      </script>";
    exit();
}
if($chack_phone>0){
echo "<script>
        alert('Phone is already registered!');
        window.location.href='customers-edit.php?id=".$id."';
      </script>";
    exit();
}
$update = mysqli_query($connect,"UPDATE customers SET `full_name`='$name', `cnic`='$cnic', `account_type`='$account_type', `status`='$status', `phone`='$phone', `email`='$email', `address`='$address', `updated_at`=NOW() WHERE `id`='$id' ");
if($update){
      echo "<script>
        alert('Customer data updated successfully!');
        window.location.href='customers-list.php'; 
    </script>";
}
else{
      echo "<script>
        alert('Update failed! Please try again.');
        window.location.href='customers-edit.php?id".$id."';
    </script>";
}

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Customer • Staff Banking Panel</title>
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
  <!--
  Reusable Header & Sidebar
  - Header: brand + quick actions
  - Sidebar: section navigation
  -->
  <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold text-primary" href="dashboard.php">
        <i class="fa-solid fa-building-columns me-2"></i> Staff Banking
      </a>
      <div class="d-flex align-items-center">
        <span class="me-3 small text-muted d-none d-md-inline">Signed in as <strong><?php echo $email_row['official_email'];?></strong></span>

          <form method="POST">
            <button class="btn btn-primary btn-sm" type="submit" name="logout"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</button>
          </form>
      </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row flex-nowrap">
      <aside class="col-12 col-md-3 col-lg-2 bg-white border-end min-vh-100 p-0 sidebar">
        <nav class="list-group list-group-flush rounded-0">
          <a href="dashboard.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</a>
          <div class="list-group-item bg-light fw-semibold text-uppercase small py-2">Customers</div>
          <a href="customers-add.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-user-plus me-2"></i>Add New</a>
          <a href="customers-list.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-users me-2"></i>All Customers</a>
          <div class="list-group-item bg-light fw-semibold text-uppercase small py-2">Transactions</div>
          <a href="transactions-deposit.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-circle-arrow-down me-2"></i>Deposit</a>
          <a href="transactions-withdraw.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-circle-arrow-up me-2"></i>Withdraw</a>
          <a href="transactions-transfer.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-right-left me-2"></i>Fund Transfer</a>
          <a href="transactions-history.php" class="list-group-item list-group-item-action py-3"><i class="fa-regular fa-clock me-2"></i>Transaction History</a>
          <a href="reports.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-chart-line me-2"></i>Reports & Analytics</a>
          <a href="profile.php" class="list-group-item list-group-item-action py-3"><i class="fa-regular fa-id-badge me-2"></i>Profile / Settings</a>
        </nav>
      </aside>
      <main class="col py-4 content-area">
        <div class="container-fluid">
          <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> Edit Customer</h1>

<div class="card">
  <div class="card-header">
    <h2 class="h6 mb-0"><i class="fa-regular fa-pen-to-square me-2 text-primary"></i> Edit Customer</h2>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['full_name']); ?>" >
        </div>
        <div class="col-md-6">
          <label class="form-label">CNIC</label>
          <input type="text" name="cnic" class="form-control" value="<?php echo htmlspecialchars($row['cnic']); ?>" >
        </div>
        <div class="col-md-4">
          <label class="form-label">Account Type</label>
          <select class="form-select" name="account_type">
            <option value="Business" <?= $row['account_type'] == 'Business' ? 'selected' : '' ?>>Business</option>
            <option value="Savings" <?= $row['account_type'] == 'Savings' ? 'selected' : '' ?>>Savings</option>
            <option value="Current" <?= $row['account_type'] == 'Current' ? 'selected' : '' ?>>Current</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select class="form-select" name="status">
<option value="1" <?= $row['status'] == '1' ? 'selected' : '' ?>>Active</option>
<option value="0" <?= $row['status'] == '0' ? 'selected' : '' ?>>Closed</option>


          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone</label>
          <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Address</label>
          <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($row['address']); ?>">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button name="update"class="btn btn-primary">Update</button>
        <a href="customers-list.php" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
        </div>
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
