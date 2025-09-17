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
if(isset($_POST['reset'])){
        echo "<script>
        window.location.href='transactions-history.php';
      </script>";
}

include("connection.php");
$obj= new Connection();
$connect=$obj->connect();
$emp_id=$_SESSION['emp_id'];
$where="created_by='$emp_id'";
$transfer_type=isset($_POST['transfer_type'])?$_POST['transfer_type']:'';
$account_number=!empty($_POST['account_number'])?$_POST['account_number']:'';
if(isset($_POST['submit'])){
$transfer_type=isset($_POST['transfer_type'])?$_POST['transfer_type']:'';

$from_date=!empty($_POST['from_date'])?$_POST['from_date']:'';
$to_date=!empty($_POST['to_date'])?$_POST['to_date']:'';
if($transfer_type!==''){
$where.=" AND transfar_type='$transfer_type'";
}
if($account_number!==''){
  $where.=" AND account_id='$account_number'";
}
if($from_date !=='' && $to_date){
  $where.=" AND DATE(created_at) BETWEEN '$from_date'AND'$to_date'";
}
else if($from_date!==''&& $to_date==''){
  $today=date("y-m-d");
  $where.=" AND DATE(created_at) BETWEEN '$from_date'AND'$today'";
}

}
$select=mysqli_query($connect,"SELECT * FROM transfers WHERE $where");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Transaction History • Staff Banking Panel</title>
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
          <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> Transaction History</h1>

<div class="card">
  <div class="card-header">
    <h2 class="h6 mb-0"><i class="fa-regular fa-clock me-2 text-primary"></i> Transaction History</h2>
  </div>
  <form method="POST">
  <div class="card-body">
    <div class="row g-2 mb-3">
      
            <div class="col-12 col-md-3">
        <select class="form-select" name="transfer_type">
          <option disabled>Transfer Type</option>
          <option value="" <?php if($transfer_type===''){echo 'selected';}?>>Type: All</option>
          <option value="deposit" <?= $transfer_type == 'deposit' ? 'selected' : '' ?>>Deposit</option>
          <option value="withdraw"<?php if($transfer_type==='withdraw'){echo 'selected';} ?>>Withdraw</option>
          <option value="transfer_in"<?php if($transfer_type==='transfer_in'){echo 'selected';} ?>>Transfer in</option>
          <option value="transfer_out"<?php if($transfer_type==='transfer_out'){echo 'selected';} ?>>Transfer Out</option>
        </select>
      </div>
            <div class="col-12 col-md-3">
        <input type="text" class="form-control" name="account_number"  placeholder="<?php if($account_number===''){echo'Filter By This Account Number';} else{
          echo$account_number;        } ?>">
      </div>
      <div class="col-12 col-md-3" style="display: flex;">
        <input type="date" class="form-control" name="from_date" placeholder="From date" style="width: 50%;">
        <input type="date" class="form-control" name="to_date" placeholder="To date" style="width: 50%;">
      </div>
      <div class="col-12 col-md-3" style="display: flex;">
        <input type="submit" class="form-control" value="Submit" name="submit" style="width: 50%;background-color:blue; color:white;">
        <input type="submit" class="form-control" name="reset" value="Reset" style="width: 50%;background-color:blue; color:white;">
      </div>

    </div>
    </form>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Customer/Account</th>
            <th>Related/Account</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if (mysqli_num_rows($select) > 0) {
                        while ($row = mysqli_fetch_assoc($select)) {
                          echo "<tr>";
                          echo "<td>" . date("Y-m-d", strtotime($row['created_at'])) . "</td>";
                          echo "<td>" . $row['transfar_type'] . "</td>";
                          echo "<td>" . $row['account_id'] . "</td>";
                          echo "<td>" . $row['related_account'] . "</td>";
                          echo "<td>" . $row['amount'] . "</td>";}}
                          ?>
        </tbody>
      </table>
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