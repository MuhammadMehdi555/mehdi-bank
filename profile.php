<?php

session_start();
if(!isset($_SESSION["emp_id"]) || !isset($_SESSION["email"] )){
      echo "<script>
            alert('Please login first!');
            window.location.href='login.php';
          </script>";
    exit();
}
if(isset($_POST['logout'])){
  session_unset();
  session_destroy();
  echo "<script>
        alert('You have been logged out successfully!');
        window.location.href='login.php';
      </script>";
}

include("connection.php");
$obj= new Connection();
$connect= $obj->connect();
$emp_id= $_SESSION["emp_id"];
$select=mysqli_query($connect,"SELECT * FROM employees WHERE emp_id='$emp_id'");
$row=mysqli_fetch_assoc($select);
if(isset($_POST['update_save'])){
  $name = !empty($_POST['full_name'])?$_POST['full_name']:$row['full_name'];
  $email = !empty($_POST['email'])?$_POST['email']:$row['official_email'];
  $branch = !empty($_POST['branch'])?$_POST['branch']:$row['branch_name'];
  $position = !empty($_POST['position'])?$_POST['position']:$row['position'];
  // $img_name = $_FILES['update_img']['name'];
  // $img_tmp = $_FILES['update_img']['tmp_name'];
  // $unique = uniqid()."_".$img_name;
  // move_uploaded_file ($img_tmp,"images/".$unique);
  $old_img = $row['profile_picture'];
  
  if(isset($_FILES['update_img'])&&$_FILES['update_img']['error']===0){
    $new_img = $_FILES['update-img']['name'];
    $new_tmp = $_FILES['update_img']['tmp_name'];
    $unique = uniqid()."_".$new_img;
    move_uploaded_file($new_tmp,"images/".$unique);
    if(!empty($old_img)&&file_exists("images/".$old_img)){
      unlink("images/".$old_img);
    }
    $final_img = $unique;

  }
  else{
    $final_img=$old_img;
  }
  $update = mysqli_query($connect,"UPDATE employees SET  `full_name`='$name', `official_email`='$email', branch_name='$branch', `position`='$position', `profile_picture`='$final_img', `updated_at`=NOW() ");
  if($update){
          echo "<script>
        alert('Customer data updated successfully!');
        window.location.href='profile.php'; 
    </script>";
  }
  else{
          echo "<script>
        alert('Update failed! Please try again.');
        window.location.href='profile.php';
    </script>";
  }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile / Settings • Staff Banking Panel</title>
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
        <span class="me-3 small text-muted d-none d-md-inline">Signed in as <strong><?php echo $row['official_email'];?></strong></span>
        <form method="POST">
        <button class="btn btn-primary btn-sm" name="logout"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</button>
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
          <h1 class="page-title h3 fw-bold"><i class="fa-solid fa-circle-dot text-primary"></i> Profile / Settings</h1>

<div class="row g-3">
  <div class="col-12 col-lg-4">
    <div class="card p-3 text-center">
      <img src="images/<?php echo $row['profile_picture'];?>" class="rounded-circle mx-auto mb-3"  style="width:120px;height:120px;object-fit:cover;">
      <h2 class="h6 mb-0"><?php echo $row['full_name'];?></h2>
      <div class="text-muted small"><?php echo $row['position']; ?></div>
            <div class="text-muted small"><?php echo $row['emp_id']; ?></div>
      <div class="text-muted small"><?php echo $row['branch_name'];?></div>
    </div>
  </div>
  <div class="col-12 col-lg-8">
    <div class="card">
      <div class="card-header">
        <h2 class="h6 mb-0"><i class="fa-regular fa-id-badge me-2 text-primary"></i> Profile / Settings</h2>
      </div>
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data"> 
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input class="form-control" name="full_name" type="text" placeholder="<?php echo $row ['full_name'];?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Official Email</label>
              <input class="form-control" name="email" type="email" placeholder="<?php echo $row ['official_email'];?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Branch</label>
              <input class="form-control" name="branch" type="text" placeholder="<?php echo $row ['branch_name'];?>">
            </div>
            <div class="col-md-6">
                  <label class="form-label">Position / Job Title</label>
    <select class="form-control" name="position">
        <option value="relationship_manager"<?= $row['position'] == 'relationship_manager' ? 'selected' : '' ?>>Relationship Manager</option>
        <option value="cashier" <?= $row['position'] == 'cashier' ? 'selected' : '' ?>>Cashier</option>
        <option value="branch_manager" <?= $row['position'] == 'branch_manager' ? 'selected' : '' ?>>Branch Manager</option>
        <option value="account_officer" <?= $row['position'] == 'account_officer' ? 'selected' : '' ?>>Account Officer</option>
        <option value="customer_service" <?= $row['position'] == 'customer_service' ? 'selected' : '' ?>>Customer Service Officer</option>
        <option value="it_officer" <?= $row['position'] == 'it_officer' ? 'selected' : '' ?>>IT Officer</option>
    </select>
            </div>
            <div class="col-12">
              <label class="form-label">Profile Picture</label>
              <input class="form-control" type="file" name="update_img">
            </div>
          </div>
          <hr class="my-4">
          <div class="d-flex gap-2 mt-4">
            <button name="update_save" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
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
