
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
                <!-- end row-->
<?php include './functions/queueOrders.php'?>
                <div class="col-xl-12">
                                   <div class="row">
                                        <div class="col-lg-9">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="mb-4">
                                                        <span class="float-right"> <label class="switch">
                                                        <input type="checkbox" id="toggle-mt" onchange="toggleMt()">
                                                        <span class="slider"></span>
                                                    </label></span>
                                                    </div>

                                                    <div id="div11">
                                                        <h4 class="card-title">No of Orders </h4>
                                                        <div class="table-responsive">
                                                            <table class="table table-centered table-striped table-nowrap mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <th><1 day</th>
                                                                    <th>2 days</th>
                                                                    <th>3 days</th>
                                                                    <th>4 days</th>
                                                                    <th>5 days</th>
                                                                    <th>Above 5 days</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                                </thead>

                                                                <tbody>
                                                                <?php
                                                                $totalCount = 0;

                                                                foreach ($statusData as $status => $intervals) {

                                                                    switch ($status) {
                                                                        case 0:
                                                                            $statusLabel = 'Placed';
                                                                            break;
                                                                        case 1:
                                                                            $statusLabel = 'Verified';
                                                                            break;
                                                                        case 2:
                                                                            $statusLabel = 'Picklisted';
                                                                            break;
                                                                        case 9:
                                                                            $statusLabel = 'Picked';
                                                                            break;
                                                                        case 5:
                                                                            $statusLabel = 'Packed';
                                                                            break;
                                                                        case 14:
                                                                            $statusLabel = 'Loaded';
                                                                            break;
                                                                        case 6:
                                                                            $statusLabel = 'Invoiced';
                                                                            break;
                                                                        case 10:
                                                                            $statusLabel = 'Other Status';
                                                                            break;
                                                                        default:
                                                                            $statusLabel = 'Unknown Status';
                                                                            break;
                                                                    }
                                                                    $statusCount = array_sum(array_column($intervals, 'count'));
                                                                    $totalCount += $statusCount;

                                                                    echo "<tr>
            <td>{$statusLabel}</td>
            <td><a href='orderstatusdetails.php?status={$status}&interval=<1day'>{$intervals['<1day']['count']}</a></td>
            <td><a href='orderstatusdetails.php?status={$status}&interval=2days'>{$intervals['2days']['count']}</a></td>
            <td><a href='orderstatusdetails.php?status={$status}&interval=3days'>{$intervals['3days']['count']}</a></td>
            <td><a href='orderstatusdetails.php?status={$status}&interval=4days'>{$intervals['4days']['count']}</a></td>
            <td><a href='orderstatusdetails.php?status={$status}&interval=5days'>{$intervals['5days']['count']}</a></td>
            <td><a href='orderstatusdetails.php?status={$status}&interval=Above 5days'>{$intervals['Above 5days']['count']}</a></td>
            <td>{$statusCount}</td>
        </tr>";

                                                                }


                                                                ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div id="div22"  style="display: none;">
                                                        <h4 class="card-title">Value of Orders (Ksh)</h4>
                                                        <div class="table-responsive">
                                                            <table class="table table-centered table-striped table-nowrap mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <th><1 day</th>
                                                                    <th>2 days</th>
                                                                    <th>3 days</th>
                                                                    <th>4 days</th>
                                                                    <th>5 days</th>
                                                                    <th>Above 5 days</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                                </thead>



                                                                <tbody>
                                                                <?php

                                                                $totalAmount = 0;

                                                                foreach ($statusData as $status => $intervals) {

                                                                    switch ($status) {
                                                                        case 0:
                                                                            $statusLabel = 'Placed';
                                                                            break;
                                                                        case 1:
                                                                            $statusLabel = 'Verified';
                                                                            break;
                                                                        case 2:
                                                                            $statusLabel = 'Picklisted';
                                                                            break;
                                                                        case 9:
                                                                            $statusLabel = 'Picked';
                                                                            break;
                                                                        case 5:
                                                                            $statusLabel = 'Packed';
                                                                            break;
                                                                        case 14:
                                                                            $statusLabel = 'Loaded';
                                                                            break;
                                                                        case 6:
                                                                            $statusLabel = 'Invoiced';
                                                                            break;
                                                                        case 10:
                                                                            $statusLabel = 'Other Status';
                                                                            break;
                                                                        default:
                                                                            $statusLabel = 'Unknown Status';
                                                                            break;
                                                                    }

                                                                    $statusAmount = array_sum(array_column($intervals, 'amount'));
                                                                    $totalAmount += $statusAmount;

                                                                    echo "<tr>
            <td>{$statusLabel}</td>
            <td>{$intervals['<1day']['amount']}</td>
            <td>{$intervals['2days']['amount']}</td>
            <td>{$intervals['3days']['amount']}</td>
            <td>{$intervals['4days']['amount']}</td>
            <td>{$intervals['5days']['amount']}</td>
            <td>{$intervals['Above 5days']['amount']}</td>
            <td>{$statusAmount}</td>
        </tr>";
                                                                }

                                                                ?>


                                                                </tbody>


                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>




                                                <!--end card body-->
                                            </div>
                                            <!--end card-->
                                        </div>

                                       <div class="col-3">

                                           <div class="card">
                                               <div class="card-body">
                                                   <div class="mb-4">
                                                       <span class="badge badge-soft-primary float-right"></span>
                                                       <h5 class="card-title mb-0">Total Orders</h5>
                                                   </div>
                                                   <div class="row d-flex align-items-center mb-4">
                                                       <div class="col-8">
                                                           <h2 class="d-flex align-items-center mb-0">
                                                               <?php echo number_format($totalCount); ?>
                                                           </h2>
                                                       </div>
                                                       <div class="col-4 text-right">
                                                           <span class="text-muted"><?php echo number_format($totalAmount); ?> </span>
                                                       </div>
                                                   </div>
                                               </div>
                                               <!--end card body-->
                                           </div><!-- end card-->
                                       </div>
                            </div>
                </div>
            </div>
        </div>
</body>




<?php include '../parts/footer.php';?>

