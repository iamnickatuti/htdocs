<?php
include './auth/process.php'
;?>
<?php
if (isset($_SESSION['id']))
{?>
<?php include './loggedin.php'?>
<?php  die(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/theme.min.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-6">
                <div class="d-flex align-items-center min-vh-100">
                    <div class="w-100 d-block bg-white shadow-lg rounded my-5">
                        <div class="row">
<!--                            <div class="col-lg-5 d-none d-lg-block bg-login rounded-left"></div>-->
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center mb-5">
                                        <a href="#" class="text-dark font-size-22 font-family-secondary">
                                             <img class="align-middle" src="./assets/logo.svg" height="60px">
                                        </a>
                                    </div>
                                    <h1 class="h5 mb-1">Welcome Back!</h1>
                                    <p class="text-muted mb-4">Enter your email address and password to access analytics panel.</p>
                                    <form class="user" method="post">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" name="unamail">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" name="password">
                                        </div>
                                        <input class="btn btn-warning btn-block waves-effect waves-light" name="signin" type="submit" value="Login">

                                        <?php if (!empty($msg)): ?>
                                            <div class="mt-2 alert <?php echo $msg_class ?>"><?php echo $msg; ?>
                                            </div>
                                        <?php endif; ?>

                                    </form>
                                    <!-- end row -->
                                </div> <!-- end .padding-5 -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- end .w-100 -->
                </div> <!-- end .d-flex -->
            </div> <!-- end col-->
        </div> <!-- end row -->
    </div>
    <!-- end container -->
</div>

</body>