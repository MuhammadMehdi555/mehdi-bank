<?php
session_start();
if (!isset($_SESSION["emp_id"]) || !isset($_SESSION['email'])) {
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


$where = "created_by='$emp_id'";
$status = isset($_GET['status']) ? $_GET['status'] : '';
$all_account=isset($_GET['types']) ? $_GET['types'] : '';
if(isset($_POST['submit'])){
$name_cnic=isset($_POST['name_cnic'])?$_POST['name_cnic']:'';
if($name_cnic!==''){
  $where.="AND account_number='$name_cnic'";

}
}

if(isset($_POST['reset'])){
  echo"<script>window.location.href='customers-list.php';
  </script>
  ";
}

if($status!==''){
  $where.="AND status='$status'";
}
if($all_account !== ''){
  $where.="AND account_type='$all_account'";
}

  $select=mysqli_query($connect,"SELECT * FROM customers WHERE $where");
$select=mysqli_query($connect,"SELECT * FROM customers WHERE $where");
$email = mysqli_query($connect,"SELECT * FROM employees WHERE emp_id='$emp_id' ");
$email_row = mysqli_fetch_assoc($email);





?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All Customers • Staff Banking Panel</title>
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
            <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> All Customers</h1>

            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h6 mb-0"><i class="fa-solid fa-users me-2 text-primary"></i> All Customers</h2>
                <a class="btn btn-sm btn-primary" href="customers-add.php"><i class="fa-solid fa-user-plus me-1"></i> Add New</a>
              </div>
              <div class="card-body">
                <div class="row g-2 mb-3">
                  <div class="col-12 col-md-3">
                    <form method="POST">
                    <input type="text" name="name_cnic" class="form-control" placeholder="Search By Account Number">
                    
                  </div>
                  <div class="col-12 col-md-3" style="display: flex;"><button  type="submit" name="submit" class="form-control" style="background-color: blue; color:white; width: 50%;">Search</button>
                  <button type="submit" name="reset" class="form-control" style="background-color: blue; color:white; width:50%;">Reset</button>
                </div>
                  </form>
                  <div class="col-12 col-md-3">
                    
                    <select class="form-select" id="types">
                      <option disabled>All Account Types</option>
                      <option value="" <?php if($all_account===''){echo'selected';}?>>All Account</option>
                      <option value="Current" <?php if($all_account==='Current'){echo'selected';}?> >Current</option>
                      <option value="Savings" <?php if($all_account==='Savings'){echo'selected';}?> >Savings</option>
                      <option value="Business" <?php if($all_account==='Business'){echo'selected';}?> >Business</option>
                    </select>
                  
                  </div>
                  <div class="col-12 col-md-3">
                   <select class="form-select" id="statusFilter">
  <option disabled>Status</option>
  <option value="" <?php if($status===''){echo 'selected';} ?>>Status: All</option>
  <option value="1" <?php if($status==='1'){echo 'selected';} ?>>Active</option>
  <option value="0" <?php if($status==='0'){echo 'selected';} ?>>Closed</option>
</select>

                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table align-middle">
                    <thead>
                      <tr>
                        <th>NAME</th>
                        <th>Account_number</th>
                        <th>CNIC</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th class="text-end">Actions</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      if (mysqli_num_rows($select) > 0) {
                        while ($row = mysqli_fetch_assoc($select)) {
                          echo "<tr>";
                          echo "<td>" . $row['full_name'] . "</td>";
                          echo "<td>" . $row['account_number'] . "</td>";
                          echo "<td>" . $row['cnic'] . "</td>";
                          echo "<td>" . $row['account_type'] . "</td>";
                          echo "<td>";
                          if ($row['status'] == 1) {
                            echo "Active";
                          } else {
                            echo "Close";
                          }
                          echo "</td>";
                          echo "<td>" . $row['phone'] . "</td>";
                          echo "<td>" . $row['email'] . "</td>";
                          echo "<td class='text-end'>
                <a href='customers-edit.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-primary'>Edit</a>";
                if($row['status']==0){
                echo "<a href='active.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-danger'>Active</a>";}
                else{
                  echo"<a href='closed.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-danger'>Close</a>";
                }
              echo "</td>";
                          echo "</tr>";
                        }
                      }
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
    <script>
      document.getElementById("types").addEventListener("change", function(){
    let types = this.value;
    let status = document.getElementById("statusFilter").value;
    let url = "customers-list.php?";

    if(types !== ""){
        url += "types=" + types + "&";
    }
    if(status !== ""){
        url += "status=" + status;
    }

    window.location.href = url;
});

document.getElementById("statusFilter").addEventListener("change", function(){
    let status = this.value;
    let types = document.getElementById("types").value;
    let url = "customers-list.php?";

    if(types !== ""){
        url += "types=" + types + "&";
    }
    if(status !== ""){
        url += "status=" + status;
    }

    window.location.href = url;
});

    </script>
  </body>
</php>