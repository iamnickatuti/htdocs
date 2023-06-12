<?php include '../parts/header.php'; ?>

<?php
session_start();
if (!isset($_SESSION['id']))

{?>
    <?php include '../notloggedin.php'?>
<?php  die(); } ?>


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
                <?php include "./functions/queLogsTime.php";?>

                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <span class="badge badge-soft-primary float-right">Daily</span>
                                    <h5 class="card-title mb-0">Orders Complete</h5>
                                </div>
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            <?php
                                            global $totalOrders;
                                            echo $totalOrders;?>
                                        </h2>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span class="text-muted">12.5% <i
                                        class="mdi mdi-arrow-up text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!--end card body-->
                        </div><!-- end card-->
                    </div> <!-- end col-->
              <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <span class="float-right"> <label class="switch">
                            <input type="checkbox" id="toggle-all" onchange="toggleAll()">
                            <span class="slider"></span>
                            </label></span>
                        </div>

                        <div id="div1">
                            <h4 class="card-title">Count on delivered Orders</h4>
                            <p class="card-subtitle mb-4 font-size-13">Note: These are orders that have gone through every step the Queue Process
                            </p>
                            <div class="table-responsive">
                                <table class="table table-centered table-striped table-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>Order Statuses</th>
                                        <th><1 Day</th>
                                        <th>1 Day</th>
                                        <th>2 Days</th>
                                        <th>3 Days</th>
                                        <th>4 Days</th>
                                        <th>>5 Days</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                    <?php
                                        global $orderStatus;
                                        foreach ($orderStatus as $status => $intervals) {
                                        echo '<tr>';
                                        echo '<td>' . $status . '</td>';
                                        foreach ($intervals as $index => $count) {
                                        echo '<td>' . $count . '</td>';
                                        }
                                       echo '</tr>';
                                    }
                                    ?>

                                    </tr>

                                    </tbody>
                                </table>
                            </div></div>
                        <div id="div2" style="display: none;">
                            <h4 class="card-title">Percentage on delivered Orders</h4>
                            <p class="card-subtitle mb-4 font-size-13">Note: These are orders that have gone through every step the Queue Process
                            </p>
                            <div class="table-responsive">
                                <table class="table table-centered table-striped table-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>Order Statuses</th>
                                        <th><1 Day</th>
                                        <th>1 Day</th>
                                        <th>2 Days</th>
                                        <th>3 Days</th>
                                        <th>4 Days</th>
                                        <th>>5 Days</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                    <?php
                                        global $orders,$orderStatus;

                                        $totalOrders = count($orders);
                                        foreach ($orderStatus as $status => $intervals) {
                                        echo '<tr>';
                                        echo '<td>' . $status . '</td>';
                                        foreach ($intervals as $index => $count) {
                                        // Calculate the percentage
                                        $percentage = ($count / $totalOrders) * 100;
                                        echo '<td>' . round($percentage, 2) . '%</td>';
                                        }
                                        echo '</tr>';
                                    }
                                    ?>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!--end card body-->
                </div>
            </div>
                </div>
            </div>
        </div>
</body>
<?php include '../parts/footer.php';?>

