<?php
session_start ();
include '../parts/header.php';
include 'sql/sqlOpening.php';
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
        <?php include '../parts/nav.php';?>

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">
                                Material Flow 2023
                            </h4>
                            <div class="page-title-right">
<!--                                <form action="prodCountFilter.php" method="post">-->
<!--                                    <div class="row">-->
<!--                                        <div class="col-9">-->
<!--                                            <select class="form-control mb-3" name = "dur">-->
<!--                                                <option >Choose Month</option>-->
<!--                                                <option value="2023 Jan%">January 2023</option>-->
<!--                                                <option value="2023 Feb%">February 2023</option>-->
<!--                                                <option value="2023 Mar%">March 2023</option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                        <div class="col-3">-->
<!--                                            <input type="submit" class="btn btn-warning" value="Get" name="search">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </form>-->
                                <span>Filter <b>Month</b> coming soon</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row-->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">   <?php
                                    $today = date('Y-m-d');
                                    $date = date_create_from_format('Y-m-d', $today);
                                    $formatted_date = date_format($date, 'Y M');
                                    $thisMonth = $formatted_date." Stocktake";

                                    echo $thisMonth;
                                    ?></h4>
                                <p class="card-subtitle mb-4">
                                    Monthly.
                                </p>
                                <table class="table table-centered table-striped mb-0" style="font-size: 11px">
                                    <thead>
                                    <tr>
                                        <th style=" width: 130px;">Part Number</th>
                                        <th style=" width: 300px; font-size: 11px;" >Name</th>
                                        <th style=" font-size: 11px;">Opening Balance (Kgs)</th>
                                        <th style=" font-size: 11px;">Receipts</th>
                                        <th style=" font-size: 11px;">Total Available for Rebonding (Kgs)</th>
                                            <th style=" font-size: 11px;">Consumption (Kgs)</th>
                                        <th style=" font-size: 11px;">Expected Closing Balance (Kgs)</th>
                                        <th style=" font-size: 11px;">Actual Closing Balance (Kgs)</th>
                                        <th style=" font-size: 11px;">Variance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $json_data = file_get_contents('https://reports.moko.co.ke/production/api/api_flow.php');
                                    $data = json_decode($json_data, true);
                                    ?>
                                    <?php foreach ($data as $row) {?>
                                        <tr>
                                            <td><?php echo $row['partNumber']; ?></td>
                                            <td><?php echo $row['partDescription']; ?></td>
                                            <td><?php echo $row['openingBalance']; ?></td>
                                            <td><?php echo $row['manufacturingReceipts']; ?></td>
                                            <td><?php echo $row['totalRebonding']; ?></td>
                                            <td><?php echo $row['bomConsumption']; ?></td>
                                            <td><?php echo $row['closingBalance']; ?></td>
                                            <td><?php echo $row['actualBalance']; ?></td>
                                            <td><?php echo $row['variancee']; ?></td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>

                                <style>
                                    tr th:nth-child(4) {
                                        background-color:#bdffbf;
                                        color: #000;
                                    }
                                    tr td:nth-child(4) {
                                        background-color: #bdffbf;
                                        color: #000;
                                    }
                                    tr th:nth-child(5) {
                                        background-color:#e3e3e3;
                                        color: #000;
                                    }
                                    tr td:nth-child(5) {
                                        background-color: #e3e3e3;
                                        color: #000;
                                    }
                                    tr th:nth-child(6) {
                                        background-color:#ffbdbd;
                                        color: #000;
                                    }
                                    tr td:nth-child(6) {
                                        background-color: #ffbdbd;
                                        color: #000;
                                    }
                                    tr th:nth-child(7) {
                                        background-color:#ffedbd;
                                        color: #000;
                                    }
                                    tr td:nth-child(7) {
                                        background-color: #ffedbd;
                                        color: #000;
                                    }
                                </style>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end row-->
            </div>
        </div>
    </div>
</div>
</body>
<?php include '../parts/footer.php';?>
