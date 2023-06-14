<?php
session_start ();
include '../parts/header.php';
?>
<?php

// JSON URL
$jsonUrl = "https://reports.moko.co.ke/demand/api/finishedProducts.php";

// Get the JSON data from the URL
$jsonData = file_get_contents($jsonUrl);

// Decode the JSON data into an associative array
$data = json_decode($jsonData, true);

?>
<body>
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
        <?php include '../parts/nav.php';?>
        <div class="page-content">
            <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        Sales Projection
                    </h4>

                </div>
            </div>
        </div>
        <div class="row">
        <div class="card">
            <div class="card-body">
<table class="table table-striped dt-responsive nowrap" style="font-size: 11px;">
    <thead>
    <tr>
        <?php foreach (array_keys($data[0]) as $header) { ?>
            <th><?php echo $header; ?></th>
        <?php } ?>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item) { ?>
        <tr>
            <?php
            $total = 0;
            foreach ($item as $value) {
                $total += floatval($value);
                ?>
                <td><?php echo is_numeric($value) ? ceil($value) : $value; ?></td>
            <?php } ?>
            <td><b><?php echo ceil($total);  ?></b></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
</body>
<?php include '../parts/footer.php'; ?>



























