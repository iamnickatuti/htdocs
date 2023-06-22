<?php
session_start();
if (!isset($_SESSION['id']))
{?>
    <?php include './notloggedin.php'?>
    <?php  die(); }
?>

<?php
include'./partss/header.php';
include'./dash/funcDash.php';
?>

<body>
<!-- Begin page -->
<div id="layout-wrapper">

    <div class="main-content">
        <?php include './partss/head.php'?>
        <div class="topnav">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                                                <li class="nav-item">
                                                    <a class="nav-link arrow-none" href="./dashboard" id="topnav-charts" aria-expanded="false">
                                                        <i class="mdi mdi-poll"></i>Dashboard
                                                    </a>
                                                </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-charts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-truck-delivery"></i>Logistics <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tables" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Queue Management<div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                            <a href="logistics/queue" class="dropdown-item">Not Delivered</a>
                                            <a href="logistics/queueTimeAnalysis" class="dropdown-item">Delivered</a>
                                        </div>
                                    </div>
                                    <a href="logistics/online" class="dropdown-item">Online Retail</a>
                                    <a href="../logistics/sla" class="dropdown-item">SLA</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-charts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-toolbox"></i>Production <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tables" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Count<div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                            <a href="production/materialFlow" class="dropdown-item">Monthly</a>
                                            <a href="production/weeklyCount" class="dropdown-item">Weekly</a>
                                            <a href="production/dailyCount" class="dropdown-item">Daily </a>
                                        </div>
                                    </div>
<!--                                    <a href="production/cuttingOutput" class="dropdown-item">Cutting Output</a>-->
                                    <a href="production/cuttingInfo" class="dropdown-item">Cutting Info</a>
                                    <a href="production/qbupload" class="dropdown-item">QB Upload</a>
                                    <a href="production/blockFlow" class="dropdown-item">Blocks Flow</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-charts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-toolbox"></i>Demand Planning <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <a href="demand/salesProjection" class="dropdown-item">Sales Projection</a>
                                    <a href="demand/rawmaterialProjection" class="dropdown-item">Raw Materials Projection</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">Dashboard</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">MoKo</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <span class="badge badge-soft-primary float-right">Mon - Sun</span>
                                    <h5 class="card-title mb-0">Delivered Orders</h5>
                                </div>
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-7">
                                        <h2 class="d-flex align-items-center mb-0">
                                           <?php echo thisweekorders(); ?>
                                        </h2>
                                    </div>
                                    <div class="col-5 text-right">
                                        <span class="text-muted">Last Week.<b><?php lastweekorders(); ?></b></span>
                                    </div>
                                </div>

<!--                                <div class="progress shadow-sm" style="height: 5px;">-->
<!--                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 57%;">-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                            <!--end card body-->
                        </div><!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <span class="badge badge-soft-primary float-right">All Time</span>
                                    <h5 class="card-title mb-0">Cradle Users</h5>
                                </div>
                                <div class="row d-flex align-items-center mb-4">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            <?php echo users(); ?>
                                        </h2>
                                    </div>
<!--                                    <div class="col-4 text-right">-->
<!--                                                <span class="text-muted">17.8% <i-->
<!--                                                        class="mdi mdi-arrow-down text-danger"></i></span>-->
<!--                                    </div>-->
                                </div>

<!--                                <div class="progress shadow-sm" style="height: 5px;">-->
<!--                                    <div class="progress-bar bg-info" role="progressbar" style="width: 57%;"></div>-->
<!--                                </div>-->
                            </div>
                            <!--end card body-->
                        </div><!-- end card-->
                    </div> <!-- end col-->
                            </div>
                <!-- end row-->
            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

<?php include'./partss/footer.php'; ?>