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
$emp_id=$_SESSION['emp_id'];
$deposit=mysqli_query( $connect,"SELECT * FROM transfers WHERE created_by='$emp_id' ");
$total_deposit='0';
$total_withdraw='0';
$monthly_data = [];

while($row_deposit=mysqli_fetch_assoc($deposit)){
  $month_year = date("F Y", strtotime($row_deposit['created_at']));

    if(!isset($monthly_data[$month_year])){
      $monthly_data[$month_year] = ["deposit" => 0, "withdraw" => 0];
    }
if($row_deposit['transfar_type']=='deposit'||$row_deposit['transfar_type']=='transfer_in'){
  $monthly_data[$month_year]['deposit'] += $row_deposit['amount'];
}
if($row_deposit['transfar_type']=='withdraw'||$row_deposit['transfar_type']=='transfer_out'){
  $monthly_data[$month_year]['withdraw'] += $row_deposit['amount'];
}


}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reports & Analytics • Staff Banking Panel</title>
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
          <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> Reports & Analytics</h1>

<div class="row g-3">
  <div class="col-12 col-lg-4">

  </div>
  <div class="col-12 col-lg-4">

  </div>
</div>
  </div>

  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h2 class="h6 mb-0">Monthly Summary</h2>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table mb-0 align-middle">
            <thead>
              <tr>
                <th>Month</th>
                <th>Total Deposits (PKR)</th>
                <th>Total Withdrawals (PKR)</th>
                <th>Net Change (PKR)</th>
              </tr>
            </thead>
            <tbody>
<?php
foreach($monthly_data as $month_year => $row_deposit){
    $net_change = $row_deposit['deposit'] - $row_deposit['withdraw'];
    echo "<tr>";
    echo "<td>".$month_year."</td>";
    echo "<td>".$row_deposit['deposit']."</td>";
    echo "<td>".$row_deposit['withdraw']."</td>";

    echo "<td>".$net_change."</td>";
    echo "</tr>";
}
?>
            </tbody>
          </table>
        </div>
      </div>
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
