<?php
class Connection{
    function connect(){
        $_SESSION['connect']=mysqli_connect('sql210.infinityfree.com','if0_39965381','kSh4i9C94R','if0_39965381_staff_bank');
        return $_SESSION['connect'];
    }
}
?>