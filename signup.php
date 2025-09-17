<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Employee Registration • Staff Banking Panel</title>
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

    <section class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="container" style="max-width: 920px;">
            <div class="card p-4 p-md-5">
                <h1 class="h4 mb-4"><i class="fa-regular fa-id-card-clip text-primary me-2"></i> Employee Registration</h1>
                <!-- SIGNUP FORM (HTML/CSS only) -->
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input class="form-control" type="text" name="name" placeholder="e.g., Muhammadm Mehdi" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CNIC</label>
                            <input class="form-control" type="text" name="cnic" placeholder="35202-1234567-1
                            " required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Branch Name</label>
                            <input class="form-control" type="text" name="branch" placeholder="Gulberg Branch" required>
                        </div>
                        <div class="col-md-6">
    <label class="form-label">Position / Job Title</label>
    <select class="form-control" name="position" required>
        <option value="">-- Select Position --</option>
        <option value="relationship_manager">Relationship Manager</option>
        <option value="cashier">Cashier</option>
        <option value="branch_manager">Branch Manager</option>
        <option value="account_officer">Account Officer</option>
        <option value="customer_service">Customer Service Officer</option>
        <option value="it_officer">IT Officer</option>
    </select>
</div>

                        <div class="col-md-6">
                            <label class="form-label">Official Email</label>
                            <input class="form-control" type="email" name="email" placeholder="name@bank.com" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Confirm Password</label>
                            <input class="form-control" type="password" name="c_password" placeholder="••••••••" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Profile Picture</label>
                            <input class="form-control" type="file" name="img">
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary" type="submit" name="creat_account">Create Account</button>
                        <a href="login.php" class="btn btn-outline-secondary">Back to Login</a>
                    </div>
                </form>
            </div>
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

<?php
include("connection.php");
$obj= new Connection();
$connect= $obj->connect();
if(isset($_POST['creat_account'])){
    $name=$_POST['name'];
    $cnic=$_POST['cnic'];
    $branch=$_POST['branch'];
    $position=$_POST['position'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $c_password=$_POST['c_password'];
    $hash_password=password_hash($password,PASSWORD_DEFAULT);
    $imgname=$_FILES['img']['name'];
    $imgtmp=$_FILES['img']['tmp_name'];
    $unique=uniqid()."_".$imgname;
    move_uploaded_file($imgtmp,"images/".$unique);

    $prefix = "STB_";
    
    do{
        $emp_id=$prefix;
        for($i=0; $i<12; $i++){
            $emp_id.=rand(0,9);
        }
    $select_emp_id=mysqli_query($connect,"SELECT * FROM employees WHERE emp_id = '$emp_id'");
    $result_emp_id=mysqli_num_rows($select_emp_id);
    }
    while($result_emp_id>0);

$select_cnic = mysqli_query($connect,"SELECT * FROM employees WHERE cnic='$cnic'");
$reslut_cnic = mysqli_num_rows($select_cnic);

$select_email = mysqli_query($connect,"SELECT * FROM employees WHERE official_email='$email'");
$reslut_email = mysqli_num_rows($select_email);
if(strlen($cnic)!=13){
        echo "<script>
            alert('CNIC must be exactly 13 digits!');
            window.history.back();
              </script>";
    exit();
}
if(strlen($password)>8 || strlen($password)<4){
      echo "<script>
            alert('Password must be between 4 and 8 characters!');
            window.history.back();
          </script>";
              exit();
}
if($password!== $c_password){
    echo "<script>
            alert('Password and Confirm Password do not match!');
            window.history.back();
          </script>";
    exit();
}
if($reslut_cnic > 0 && $reslut_email > 0){
    echo "<script>alert('CNIC and Email are already registered!');
    window.history.back();
    </script>";
    exit();
} elseif($reslut_cnic > 0){
    echo "<script>alert('CNIC is already registered!');
    window.history.back();
    </script>";
    exit();
} elseif($reslut_email > 0){
    echo "<script>alert('Email is already registered!');
    window.history.back();
    </script>";
    exit();
}
else{
$insert = mysqli_query($connect, "INSERT INTO employees 
(emp_id, full_name, cnic, branch_name, position, official_email, password, profile_picture, created_at, updated_at) 
VALUES 
('$emp_id', '$name', '$cnic', '$branch', '$position', '$email', '$hash_password', '$unique', NOW(), '')");

if($insert){
    $_SESSION["emp_id"] =$emp_id;
    $_SESSION["email"] =$email;
    echo "<script>alert('Add Account successfully!'); window.location='dashboard.php';</script>";
} else {
    echo "<script>alert('Error: ".mysqli_error($connect)."');</script>";
}
}



}


?>