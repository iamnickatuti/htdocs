<?php
session_start();
if (!isset($_SESSION['id']))
{?>
    <?php include '../notloggedin.php'?>
    <?php  die(); }
function getOrderNumber(){
    $url = 'https://reports.moko.co.ke/logistics/api/onlineRetail.php'; // Replace with your actual URL
    $jsonArray= file_get_contents($url);
    $array = json_decode($jsonArray, true);
    $rowCount = count($array);
    return $rowCount;
}
function getOrderDelivered(){
    $url = 'https://reports.moko.co.ke/logistics/api/onlineRetail.php'; // Replace with your actual URL
    $jsonArray = file_get_contents($url);
    $array = json_decode($jsonArray, true);

    $rowCount = 0;
    foreach ($array as $item) {
        if ($item['Order_Status'] === 'Delivered') {
            $rowCount++;
        }
    }

    return $rowCount;
}
function getOrderNotDelivered(){
    $url = 'https://reports.moko.co.ke/logistics/api/onlineRetail.php'; // Replace with your actual URL
    $jsonArray = file_get_contents($url);
    $array = json_decode($jsonArray, true);

    $rowCount = 0;
    foreach ($array as $item) {
        if ($item['Order_Status'] !== 'Delivered') {
            $rowCount++;
        }
    }

    return $rowCount;
}
function getStatuses()
{
    $url = 'https://reports.moko.co.ke/logistics/api/onlineRetail.php'; // Replace with your actual URL
    $jsonArray = file_get_contents($url);
    $array = json_decode($jsonArray, true);

    // Group and count by Order_Status
    $counts = array();
    foreach ($array as $item) {
        $orderStatus = $item['Order_Status'];
        if (isset($counts[$orderStatus])) {
            $counts[$orderStatus]++;
        } else {
            $counts[$orderStatus] = 1;
        }
    }

    $html = '';
    foreach ($counts as $orderStatus => $count) {
        // Add a hyperlink to show orders for each count value
        $html .= "<tr>
                      <td>{$orderStatus}</td>
                      <td><a href=\"onlineRetail.php?status={$orderStatus}\" class='btn btn-block btn-warning'>{$count}</a></td>
                  </tr>";
    }

    return $html;
}



?>


<?php
include '../parts/header.php';
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
                            <h4 class="mb-0 font-size-18">Online <Retail></Retail></h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
<!--                                    sdfdghf-->
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title">Orders Queue</h4>
                                <!--                                <p class="card-subtitle mb-4 font-size-13">Transaction period from 21 July to 25 Aug-->
                                <!--                                </p>-->

                                <div class="table-responsive">
                                    <table class="table table-centered table-striped table-nowrap mb-0">
                                        <tbody>
                                        <?php echo getStatuses();?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!--end card body-->

                        </div>
                        <!--end card-->
                    </div>
                    <div class="col-9">
                        <div class="row">
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <!--                                    <span class="badge badge-soft-primary float-right">All Orders</span>-->
                                            <h5 class="card-title mb-0">All Orders</h5>
                                        </div>
                                        <div class="row d-flex align-items-center mb-3">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    <?php echo getOrderNumber();?>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <!--                                    <span class="badge badge-soft-primary float-right">All Orders</span>-->
                                            <h5 class="card-title mb-0">Incomplete Orders</h5>
                                        </div>
                                        <div class="row d-flex align-items-center mb-3">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    <?php echo getOrderNotDelivered();?>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <!--end col-->

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>

<?php include '../parts/footer.php';?>

































