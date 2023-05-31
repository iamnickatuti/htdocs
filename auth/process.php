<?php
session_start();
include './cradle_config.php';
global $conn;
$msg = "";
$msg_class = "";
if(isset($_POST['signin'])){
    if (empty($_POST['unamail']) || empty($_POST['password'])) {
        $msg = "complete fields!";
        $msg_class="alert-danger";
    } else{
        $username = mysqli_real_escape_string($conn,$_POST['unamail']);
        $password = $_POST['password'];
        $query = "SELECT * FROM users WHERE users.email='$username'";
        $result = $conn->query($query);
        if ($result->num_rows<1){
            $msg = "Account does not exist";
            $msg_class = "alert-danger";
        }
        if ($result->num_rows == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if (!password_verify($_POST['password'], $row['password'])) {
                $msg = "Cross-check your password!!";
                $msg_class = "alert-warning";
            }
            else if (password_verify($_POST['password'], $row['password'])) {
                if(is_array($row)) {
                    $_SESSION["id"] = $row['id'];
                    $_SESSION["name"] = $row['last_name'];
                }
                if(isset($_SESSION["id"])) {
                    header('Location:./dashboard');
                }
            }
        }
    }
}

