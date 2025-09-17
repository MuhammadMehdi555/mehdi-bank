<?php
session_start();
if(!isset($_SESSION["emp_id"])|| !isset( $_SESSION["email"])){
      echo "<script>
            alert('Please login first!');
            window.location.href='login.php';
          </script>";
    exit();
}
if(isset($_POST['logout'])){
  session_destroy();
  session_unset();
    echo "<script>
        alert('You have been logged out successfully!');
        window.location.href='login.php';
      </script>";
}
include("connection.php");
$obj= new connection();
$connect = $obj->connect();
$emp_id = $_SESSION["emp_id"];
$email = mysqli_query($connect,"SELECT * FROM employees WHERE emp_id='$emp_id' ");
$email_row = mysqli_fetch_assoc($email);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add customers • Staff Banking Panel</title>
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
        <a class="btn btn-outline-primary btn-sm me-2" href="profile.php"><i class="fa-regular fa-user me-1"></i> Profile</a>
        <form method="POST">
        <button class="btn btn-primary btn-sm" type="submit" name="logout" ><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</button>
     </form> </div>
    </div>
  </header>

  <div class="container-fluid">
    <div class="row flex-nowrap">
      <aside class="col-12 col-md-3 col-lg-2 bg-white border-end min-vh-100 p-0 sidebar">
        <nav class="list-group list-group-flush rounded-0">
          <a href="dashboard.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</a>
          <div class="list-group-item bg-light fw-semibold text-uppercase small py-2">customers</div>
          <a href="customers-add.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-user-plus me-2"></i>Add New</a>
          <a href="customers-list.php" class="list-group-item list-group-item-action py-3"><i class="fa-solid fa-users me-2"></i>All customers</a>
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
          <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> Add customers</h1>

<div class="card">
  <div class="card-header">
    <h2 class="h6 mb-0"><i class="fa-solid fa-user-plus me-2 text-primary"></i> Add New customers</h2>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" placeholder="e.g., Muhammad Mehdi" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">CNIC</label>
          <input type="text" class="form-control" name="cnic" placeholder="35202-1234567-8" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Account Type</label>
          <select class="form-select" name="account_type">
            <option>Current</option>
            <option>Savings</option>
            <option>Business</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Initial Deposit (PKR)</label>
          <input type="number" class="form-control" name="deposit" placeholder="0.00">
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone</label>
          <input type="tel" class="form-control" name="phone" placeholder="+92xxxxxxxxxx">
        </div>
        <div class="col-12">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" name="email" placeholder="customers@email.com">
        </div>
        <div class="col-12">
          <label class="form-label">Address</label>
          <input type="text" class="form-control" name="addres" placeholder="Street, City, Postal Code">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button name="add"type="submit" class="btn btn-primary">Save customers</button>
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
<?php
$emp_id = $_SESSION['emp_id'];
if(isset($_POST['add'])){
$name= $_POST['name'];
$cnic= $_POST['cnic'];
$account_type= $_POST['account_type'];
$deposit=$_POST['deposit'];
$phone = $_POST['phone'];
$email =$_POST['email'];
$addres = $_POST['addres'];


$select_cnic=mysqli_query($connect,"SELECT * FROM customers WHERE cnic='$cnic'");
$result_cnic=mysqli_num_rows($select_cnic);
$select_email=mysqli_query($connect,"SELECT * FROM customers WHERE email='$email'");
$result_email=mysqli_num_rows($select_email);
$prefix="ac_";
do{
  $account_number=$prefix;
  for($i=0 ; $i<12; $i++){
    $account_number.=rand(0,9);
  }
  $select_account_number=mysqli_query($connect,"SELECT * FROM customers WHERE account_number='$account_number'");
  $result_account_number=mysqli_num_rows($select_account_number);
}
while($result_account_number>0);
if(strlen($cnic)!=13){
          echo "<script>
            alert('CNIC must be exactly 13 digits!');
            window.history.back();
              </script>";
}

if($result_cnic>0 && $result_email>0){
   echo "<script>alert('CNIC and Email are already registered!');
    window.history.back();
    </script>";
}
else if($result_cnic>0){
      echo "<script>alert('CNIC is already registered!');
    window.history.back();
    </script>";
    exit();
}
else if($result_email>0){
      echo "<script>alert('Email is already registered!');
    window.history.back();
    </script>";
    exit();
}
else{
$insert = mysqli_query($connect,
"INSERT INTO customers (full_name, cnic, account_type, deposit, phone, email, address, account_number, status, created_by, created_at) 
VALUES ('$name','$cnic','$account_type','$deposit','$phone','$email','$addres','$account_number','1', '$emp_id',NOW() ) ");

  if($insert){
        echo "<script>alert('Add Account successfully!'); window.location='dashboard.php';</script>";
  }
  else{
        echo "<script>alert('Error: ".mysqli_error($connect)."');</script>";
  }
}
}
?>
