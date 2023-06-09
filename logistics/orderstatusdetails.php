<?php

include './functions/queueOrders.php';
?>



<?php include '../parts/header.php'?>
<?php
session_start();
if (!isset($_SESSION['id']))

{?>
    <?php include '../notloggedin.php'?>
    <?php  die(); }
?>



<body>
<!-- Begin page -->
<div id="layout-wrapper">
    <div class="main-content">

        <header id="page-topbar">
            <div class="navbar-header">
                <div class="navbar-brand-box d-flex align-items-left">
                    <a href="#" class="logo">
                        <img class="align-middle" src="../assets/logo.svg" height="50px">
                    </a>

                    <button type="button" class="btn btn-sm mr-2 font-size-16 d-lg-none header-item waves-effect waves-light" data-toggle="collapse" data-target="#topnav-menu-content">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center">
                    <div class="dropdown d-inline-block ml-2">
                        <button type="button" class="btn header-item waves-effect waves-light"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="../assets/images/users/pp.png"
                                 alt="Header Avatar">
                            <span class="d-none d-sm-inline-block ml-1"><?php if($_SESSION["name"]) { echo $_SESSION["name"];} ?></span>
                            <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="../auth/logout.php">
                                <span>Log Out</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <?php include '../parts/nav.php';
        ?>
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">Queue Management</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0); ">MoKo
                                        </a></li>
                                    <li class="breadcrumb-item active">Queue Management</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title">Orders</h4>
                                    <p class="card-subtitle mb-4 font-size-13">Transaction period from 21 July to 25 Aug
                                    </p>

                                    <div class="table-responsive">
                                        <table class="table table-centered table-striped table-nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                                <th>Order No</th>
                                                <th>Customer Name</th>
                                                <th>Customer Phone</th>
                                                <th>Agent Name</th>
                                                <th>Agent Email</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>



                                            <?php

                                            $status = $_GET['status']; // Get the status value from the query parameter
                                            $interval = $_GET['interval']; // Get the interval value from the query parameter

                                            // Assuming you have access to the $orders array containing the order data

                                            // Filter the orders based on the status and interval
                                            $filteredOrders = array_filter($orders, function ($order) use ($status, $interval) {
                                                $createdAt = strtotime($order['created_at']);
                                                $elapsedTime = time() - $createdAt;
                                                $elapsedDays = ceil($elapsedTime / (60 * 60 * 24));  // Calculate the elapsed days

                                                if ($order['status'] == $status) {
                                                    switch ($interval) {
                                                        case '<1day':
                                                            return $elapsedDays <= 1;
                                                        case '2days':
                                                            return $elapsedDays == 2;
                                                        case '3days':
                                                            return $elapsedDays == 3;
                                                        case '4days':
                                                            return $elapsedDays == 4;
                                                        case '5days':
                                                            return $elapsedDays == 5;
                                                        case 'Above 5days':
                                                            return $elapsedDays > 5;
                                                        default:
                                                            return false;
                                                    }
                                                }

                                                return false;
                                            });

                                            if (count($filteredOrders) > 0) {


                                                foreach ($filteredOrders as $order) {
                                                    echo "<tr>
            <td>{$order['order_id']}</td>
            <td>{$order['created_at']}</td>
            <td>{$order['status']}</td>
            <td>{$order['order_no']}</td>
            <td>{$order['customer_name']}</td>
            <td>{$order['phone']}</td>
            <td>{$order['first_name']} {$order['last_name']}</td>
            <td>{$order['email']}</td>
            <td>{$order['total']}</td>
        </tr>";
                                                }

                                                echo "</table>";
                                            } else {
                                                echo "<p>No orders found.</p>";
                                            }
                                            ?>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!--end card body-->

                            </div>
                            <!--end card-->
                        </div>
                        <!--end col-->


                    </div>
            </div>
        </div>
</body>




<?php include '../parts/footer.php';?>


