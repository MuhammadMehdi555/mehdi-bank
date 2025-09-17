<?php
session_start();
if(!isset($_SESSION['emp_id'])|| !isset($_SESSION['email'])){
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
$obj=new connection();
$connect=$obj->connect();
if(isset($_POST['submit'])){
$cu_id=$_POST['cu_id'];
$pkr = $_POST['pkr'];
$remark = $_POST['remark'];
$emp_id= $_SESSION['emp_id'];
$chack_customer = mysqli_query($connect , "SELECT * FROM customers WHERE account_number='$cu_id'");
if(mysqli_num_rows($chack_customer)>0){
  $customer=mysqli_fetch_assoc($chack_customer);
  $cus_id=$customer['id'];
  if($customer['created_by']==$emp_id){
    $old_balance = $customer['deposit'];
    $new_blance = $old_balance+$pkr;

    if($customer['status']==0){
          echo "<script>
        alert('Deposit failed! Your account is closed.');
    </script>";
    }
    else{
    $update=mysqli_query($connect," UPDATE customers SET deposit='$new_blance' WHERE account_number='$cu_id' ");
$insert = mysqli_query($connect,"
  INSERT INTO transfers (account_id, transfar_type, amount, balance_after, related_account, description, created_by, created_at, created_time) 
  VALUES 
  ('$cu_id', 'deposit', '$pkr', '$new_blance', NULL, '$remark', '$emp_id', NOW() , CURTIME())
")or die(mysqli_error($connect));

 
  if($insert&&$update){
        echo "<script>alert('Deposit successful! Old Balance: $old_balance | New Balance: $new_blance');
        window.location.href='transactions-deposit.php';
        </script>".mysqli_error($connect);  }
  
  else{
 
    echo "<script>
        alert('Deposit failed! Error: " . mysqli_error($connect) . "');
    </script>";
  }
 }
 }
else{
        echo "<script>alert('You are not allowed to deposit for this customer!');</script>";

}
}

  else {
    echo "<script>alert('Customer not found!');</script>";
    exit();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Deposit • Staff Banking Panel</title>
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
        <span class="me-3 small text-muted d-none d-md-inline">Signed in as <strong><?php echo $_SESSION['email'];?></strong></span>
        <a class="btn btn-outline-primary btn-sm me-2" href="profile.php"><i class="fa-regular fa-user me-1"></i> Profile</a>
        <form method="POST">
        <button class="btn btn-primary btn-sm" type="submit" name="logout" ><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</button>
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
          <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> Deposit</h1>

<div class="card">
  <div class="card-header">
    <h2 class="h6 mb-0"><i class="fa-solid fa-circle-arrow-down me-2 text-primary"></i> Deposit</h2>
  </div>
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Customer Account Number</label>
          <input type="text" class="form-control" name="cu_id" placeholder="ac_877138649404" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Amount (PKR)</label>
          <input type="number" class="form-control" name="pkr" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Date</label>
          <input type="date" class="form-control" disabled>
        </div>
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <input type="text" name="remark" class="form-control" placeholder="Optional note">
        </div>
      </div>
      <div class="d-flex gap-2 mt-4">
        <button href="transactions-history.php" type="submit" name="submit" class="btn btn-primary">Submit Deposit</button>
        </form>
        <a href="dashboard.php" name="cancel" class="btn btn-outline-secondary">Cancel</a>
      </div>
    
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
