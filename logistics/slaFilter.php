<?php
session_start();
if (!isset($_SESSION['id']))
{?>
    <?php include '../notloggedin.php'?>
    <?php  die(); }
?>

<?php
include './sla_meet.php';
include '../parts/header.php';
include './funcSLA/funcFilter.php';
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

        <?php include '../parts/nav.php';?>
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">Orders</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <script type="text/javascript">
                                        function checkDates() {
                                            const fromDate = new Date(document.getElementById("fromDate").value);
                                            const toDate = new Date(document.getElementById("toDate").value);
                                            const submitButton = document.getElementById("submitButton");

                                            if (toDate < fromDate) {
                                                submitButton.style.display = "none";
                                            } else {
                                                submitButton.style.display = "block";
                                            }
                                        }
                                    </script>

                                    <form action="slaFilter.php" method="post">
                                        <div class="row">
                                            <div class="col-5">
                                                <input type="date" name="start_date" class="form-control date d-inline p-3" id="fromDate" onchange="checkDates()" data-toggle="daterangepicker" data-single-date-picker="true" required>
                                            </div>
                                            <div class="col-5">
                                                <input type="date" name="end_date" class="form-control date d-inline p-3" id="toDate" onchange="checkDates()" data-toggle="daterangepicker" data-single-date-picker="true" required>
                                            </div>
                                            <div class="col-2">
                                                <button type="submit" name="search" id="submitButton" class="btn btn-primary waves-effect waves-light style="display:none;">  <i class="fa fa-filter"></i></button>
                                            </div>

                                        </div>
                                    </form>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                <div id="barchart_material" style="width: 900px; height: 500px;"></div>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <span class="badge badge-soft-primary float-right">Delivered</span>
                                    <h5 class="card-title mb-0">Orders</h5>
                                </div>
                                <div class="row d-flex align-items-center mb-3">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            <?php echo $rowDelivered; ?>
                                        </h2>
                                    </div>
                                    <div class="col-4 text-right">
                                        <span class="text-muted"><?php echo  $percentage_d; ?>%</span>
                                    </div>
                                </div>

                                <div class="progress shadow-sm" style="height: 5px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo  $percentage_d; ?>%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <div class="text-center">
                                        <h5 class="card-title mb-2">Percentage SLA (3 days)</h5>
                                        <input data-plugin="knob" data-width="65" data-height="65"
                                               data-linecap=round data-fgColor="#7a08c2" value="<?php echo slapercentage();?>"
                                               data-skin="tron" data-angleOffset="180" data-readOnly=true
                                               data-thickness=".15" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Average Lead Days </h5>
                                </div>
                                <div class="row d-flex align-items-center mb-3">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            <?php echo diffdatesorder(); ?>
                                        </h2>
                                    </div>
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
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Placed', 'Met Sla', 'Not Met SLA', 'Far From SLA','Alarming'],
            ['Verified', <?php echo $countVerifiedG;?>,<?php echo $countVerifiedO;?>, <?php echo $countVerifiedR;?>, <?php echo $countVerifiedB;?>],
            ['Picklisted', <?php echo $countPicklistedG;?>,<?php echo $countPicklistedO;?>, <?php echo $countPicklistedR;?>, <?php echo $countPicklistedB;?>],
            ['Picked',<?php echo $countPickedG;?>,<?php echo $countPickedO;?>, <?php echo $countPickedR;?>, <?php echo $countPickedB;?>],
            ['Packed',<?php echo $countPackedG;?>,<?php echo $countPackedO;?>, <?php echo $countPackedR;?>,<?php echo $countPackedB;?>],
            ['Invoiced', <?php echo $countInvoicedG;?>, <?php echo $countInvoicedO;?>, <?php echo $countInvoicedR;?>,<?php echo $countInvoicedB;?>],
            ['Loaded',<?php echo $countLoadedG;?>, <?php echo $countLoadedO;?>, <?php echo $countLoadedR;?>,<?php echo $countLoadedB;?>]
        ]);

        var options = {
            chart: {
                title: 'SLA Perfomance',
                subtitle: 'Year 2023',
            },
            bars: 'vertical', // Required for Material Bar Charts.
            hAxis: {format: 'decimal'},
            height: 500,
            colors: ['#1b9e77', '#d95f02', '#b00b31','#000000']
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>


<?php include '../parts/footer.php';?>
