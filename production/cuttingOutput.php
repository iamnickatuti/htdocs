<?php
session_start ();
include '../parts/header.php';

include '../cradle_config.php';
include './sql/sqlBlockCount.php';

?>
    <body style="color: black;">
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
<?php include '../parts/nav.php'; ?>

        <div class="page-content">
            <div class="container-fluid">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <!--            <h4 class="card-title">Basic example</h4>-->
                            <!--            <p class="card-subtitle mb-4"> For basic styling—light padding and only horizontal-->
                            <!--                dividers—add the base class <code>.table</code> to any-->
                            <!--                <code>&lt;table&gt;</code>.-->
                            <!--            </p>-->
                            <div class="table-responsive">
                                <table id="basic-datatable" class="table nowrap" style="font-size: 11px;">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Block ID</th>
                                        <th>Block SKU</th>
                                        <th>Block Category</th>
                                        <th>Cut SKU Part Number</th>
                                        <th>Cut SKU Part Description</th>
                                        <th>Cut SKU Category</th>
                                        <th>Cut Qty</th>
                                        <th>Cut Weights</th>
                                        <th>Average SKU Weights</th>
                                        <th>Cummulative SKU weight per block</th>
                                        <th>Dry Block Weight</th>
                                        <th>SKU Cut Weight</th>
                                        <th>Recorded Recycle Weight</th>
                                        <th>Expected Recycle Weight</th>
                                        <th>Recycle Variance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php include './functions/test.php';?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card -->

                </div>
            </div>
        </div>
    </body>
<?php include '../parts/footer.php'; ?>



