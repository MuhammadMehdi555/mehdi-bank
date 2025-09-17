<?php
session_start();

if (!isset($_SESSION['emp_id']) || !isset($_SESSION['email'])) {
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
        alert('Transaction Cancelled!');
        window.location.href='transactions-deposit.php';
      </script>";
}
$emp_email = $_SESSION['emp_id'];
include "connection.php";
$obj = new Connection();
$connect = $obj->connect();
$deposit_sum = mysqli_query($connect, "SELECT deposit FROM customers");
$total_deposit = 0;
while ($deposit_row = mysqli_fetch_assoc($deposit_sum)) {
  $total_deposit += $deposit_row['deposit'];
}
function formatNumber($num)
{
  if ($num >= 1000000000) {
    return round($num / 1000000000, 1) . 'B';
  } elseif ($num >= 1000000) {
    return round($num / 1000000, 1) . 'M';
  } elseif ($num >= 1000) {
    return round($num / 1000, 1) . 'K';
  }
  return $num;
}
$total = mysqli_query($connect, "SELECT * FROM customers");
$row = mysqli_num_rows($total);
$status_q = mysqli_query($connect, "SELECT status FROM customers WHERE created_by='$emp_email'");
$chacking = mysqli_fetch_assoc($status_q);
$active = mysqli_query($connect, "SELECT * FROM customers WHERE status='1'");
$active_account = mysqli_num_rows($active);
$active = mysqli_query($connect, "SELECT * FROM customers WHERE status='1'");
$active_account = mysqli_num_rows($active);
$closed = mysqli_query($connect, "SELECT * FROM customers WHERE status='0'");
$closed_account = mysqli_num_rows($closed);
$email = mysqli_query($connect, "SELECT * FROM employees WHERE emp_id='$emp_email' ");
$email_row = mysqli_fetch_assoc($email);

$deposit_show = mysqli_query($connect, "SELECT * FROM transfers WHERE created_by='$emp_email' AND transfar_type='deposit' ORDER BY id DESC LIMIT 1");
$last_deposit = mysqli_fetch_assoc($deposit_show);

$withdraw_show = mysqli_query($connect, "SELECT * FROM transfers WHERE created_by='$emp_email' AND transfar_type='withdraw' ORDER BY id DESC LIMIT 1");
$last_withdraw = mysqli_fetch_assoc($withdraw_show);

$transfer_in_show = mysqli_query($connect, "SELECT * FROM transfers WHERE created_by='$emp_email' AND transfar_type='transfer_in' ORDER BY id DESC LIMIT 1");
$last_transfer_in = mysqli_fetch_assoc($transfer_in_show);

$transfer_out_show = mysqli_query($connect, "SELECT * FROM transfers WHERE created_by='$emp_email' AND transfar_type='transfer_out' ORDER BY id DESC LIMIT 1");
$last_transfer_out = mysqli_fetch_assoc($transfer_out_show);




?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard • Staff Banking Panel</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/styles.css">
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
        <span class="me-3 small text-muted d-none d-md-inline">Signed in as <strong><?php echo $email_row['official_email']; ?></strong></span>
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
          <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> Dashboard</h1>

          <div class="row g-3">
            <!-- Stat cards -->
            <div class="col-12 col-md-6 col-xl-3">
              <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-wrap"><i class="fa-solid fa-users"></i></div>
                  <div>
                    <div class="text-muted small">Total Customers</div>
                    <div class="h4 mb-0"><?php echo $row; ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
              <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-wrap"><i class="fa-solid fa-id-card"></i></div>
                  <div>
                    <div class="text-muted small">Active Accounts</div>
                    <div class="h4 mb-0"><?php echo $active_account; ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
              <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-wrap"><i class="fa-regular fa-circle-check"></i></div>
                  <div>
                    <div class="text-muted small">Closed Accounts</div>
                    <div class="h4 mb-0"><?php echo $closed_account; ?></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
              <div class="card stat-card p-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-wrap"><i class="fa-solid fa-sack-dollar"></i></div>
                  <div>
                    <div class="text-muted small">Total Balance</div>
                    <div class="h4 mb-0"><?php echo formatNumber($total_deposit); ?></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Activity & Notifications -->
            <div class="col-12 col-xl-8">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h2 class="h6 mb-0">Recent Activity</h2>
                  <a href="transactions-history.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                  <table class="table mb-0 align-middle">
                    <thead>
                      <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <?php echo "<td>" . $last_deposit['created_time'] . "</td>" ?>
                        <td><span class="badge bg-primary-subtle text-primary"><i class="fa-solid fa-circle-arrow-down me-1"></i>Deposit</span></td>
                        <?php echo "<td>" . $last_deposit['account_id'] . "</td>" ?>
                        <?php echo "<td>" . $last_deposit['amount'] . "</td>" ?>
                        <?php
                        echo "<td>";
                        if ($chacking['status'] == 1) {
                          echo "<span class='badge bg-success-subtle text-success'>Active</span>";
                        } else {
                          echo "<span class='badge bg-success-subtle text-success'>Closed</span>";
                        }
                        echo "</td>" ?>;
                      </tr>
                      <tr>
                        <?php echo "<td>" . $last_withdraw['created_time'] . "</td>" ?>
                        <td><span class="badge bg-primary-subtle text-primary"><i class="fa-solid fa-circle-arrow-down me-1"></i>Withdraw</span></td>
                        <?php echo "<td>" . $last_withdraw['account_id'] . "</td>" ?>
                        <?php echo "<td>" . $last_withdraw['amount'] . "</td>" ?>
                        <?php
                        echo "<td>";
                        if ($chacking['status'] == 1) {
                          echo "<span class='badge bg-success-subtle text-success'>Active</span>";
                        } else {
                          echo "<span class='badge bg-success-subtle text-success'>Closed</span>";
                        }
                        echo "</td>" ?>;
                      </tr>
                      <tr>
                        <?php echo "<td>" . $last_transfer_in['created_time'] . "</td>" ?>
                        <td><span class="badge bg-primary-subtle text-primary"><i class="fa-solid fa-circle-arrow-down me-1"></i>Transfer_in</span></td>
                        <?php echo "<td>" . $last_transfer_in['account_id'] . "</td>" ?>
                        <?php echo "<td>" . $last_transfer_in['amount'] . "</td>" ?>
                        <?php
                        echo "<td>";
                        if ($chacking['status'] == 1) {
                          echo "<span class='badge bg-success-subtle text-success'>Active</span>";
                        } else {
                          echo "<span class='badge bg-success-subtle text-success'>Closed</span>";
                        }
                        echo "</td>" ?>;
                      </tr>
                      <tr>
                        <?php echo "<td>" . $last_transfer_out['created_time'] . "</td>" ?>
                        <td><span class="badge bg-primary-subtle text-primary"><i class="fa-solid fa-circle-arrow-down me-1"></i>Transfer_out</span></td>
                        <?php echo "<td>" . $last_transfer_out['account_id'] . "</td>" ?>
                        <?php echo "<td>" . $last_transfer_out['amount'] . "</td>" ?>
                        <?php
                        echo "<td>";
                        if ($chacking['status'] == 1) {
                          echo "<span class='badge bg-success-subtle text-success'>Active</span>";
                        } else {
                          echo "<span class='badge bg-success-subtle text-success'>Closed</span>";
                        }
                        echo "</td>" ?>;
                      </tr>

                    </tbody>
                  </table>
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