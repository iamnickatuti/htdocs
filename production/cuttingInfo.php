<?php
session_start ();
include '../parts/header.php';
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
                            Cutting Information
                        </h4>
                        <div class="page-title-right">
                            <form method="post" action="">
                                <label for="start_date" style="font-size: 11px">Start Date:</label>
                                <input type="date" id="start_date" name="start_date" required>

                                <label for="end_date"  style="font-size: 11px">End Date:</label>
                                <input type="date" id="end_date" name="end_date" required>

                                <input class="btn btn-warning" type="submit" value="Filter">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row-->
            <div class="row">
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3">
                                <li class="nav-item">
                                    <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                        <i class="mdi mdi-settings-outline d-lg-none d-block"></i>
                                        <span class="d-none d-lg-block">Cut SKU Summary</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link">
                                        <i class="mdi mdi-home-variant d-lg-none d-block"></i>
                                        <span class="d-none d-lg-block">Cut SKU Densities Summary</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#profile" data-toggle="tab" aria-expanded="true" class="nav-link">
                                        <i class="mdi mdi-account-circle d-lg-none d-block"></i>
                                        <span class="d-none d-lg-block">Proposed BOM</span>
                                    </a>
                                </li>

                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane" id="home">
                                    <div class="table-responsive">
                                        <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap" style="font-size: 11px;">
                                            <thead>
                                            <tr>
                                                <th>Cut SKU Category</th>
                                                <th>Finance Key</th>
                                                <th>Part Number</th>
                                                <th>Raw Material</th>
                                                <th>Quantity</th>
                                                <th>Average Unit SKU Weight (Kgs)</th>
                                                <th>Cummulative Cut SKUs Weight (kgs)</th>
                                                <th>Cut SKU Part Description</th>
                                                <th>Dimensions</th>
                                                <th>Unit Volume</th>
                                                <th>Cummulative Volume</th>
                                                <th>Unit Density</th>
                                                <th>Cummulative Density</th>
                                            </tr>
                                            </thead>
                                            </tbody
                                            <?php
                                            $json_data = file_get_contents('https://reports.moko.co.ke/production/functions/finalTest.php');
                                            $data = json_decode($json_data, true);
                                            $groupedData = array_reduce($data, function ($result, $item) {
                                                $cut_sku_part_description = $item['Cut SKU Part Description'];
                                                $category = $item['Cut SKU Category'];
                                                $financeKey = $item['Finance Key'];
                                                $partNumber = $item['Cut SKU Part Number'];
                                                $blockSKU = $item['Block SKU'];
                                                $cut_sku_qty = $item['Cut SKU Quantity'];
                                                $cut_sku_weight = $item['Average Cut SKU Weight'];
                                                if (!isset($result[$category])) {
                                                    $result[$category] = array();
                                                }
                                                if (!isset($result[$category][$financeKey])) {
                                                    $result[$category][$financeKey] = array();
                                                }
                                                if (!isset($result[$category][$financeKey][$partNumber])) {
                                                    $result[$category][$financeKey][$partNumber] = array();
                                                }
                                                if (!isset($result[$category][$financeKey][$partNumber][$blockSKU])) {
                                                    $result[$category][$financeKey][$partNumber][$blockSKU] = array();
                                                }
                                                $result[$category][$financeKey][$partNumber][$blockSKU][] = $item;

                                                return $result;
                                            }, array());

                                            $count = 1;

                                            // Loop through the grouped data and display the items that have common Category, Finance Key, Part Number, and Block SKU
                                            foreach ($groupedData as $category => $financeKeys) {
                                                foreach ($financeKeys as $financeKey => $partNumbers) {
                                                    foreach ($partNumbers as $partNumber => $blockSKUs) {
                                                        foreach ($blockSKUs as $blockSKU => $items) {
                                                            $cut_sku_qty = 0;
                                                            $cut_sku_weight_total = 0; // Initialize total weight
                                                            foreach ($items as $item) {
                                                                $cut_sku_qty += $item['Cut SKU Quantity'];
                                                                $cut_sku_weight_total += $item['Average Cut SKU Weight'] * $item['Cut SKU Quantity'];
                                                            }
                                                            $cut_sku_weight_avg = $cut_sku_qty != 0 ? $cut_sku_weight_total / $cut_sku_qty : 0; // Calculate average weight
                                                            $cut_sku_part_description = $item['Cut SKU Part Description'];


                                                            echo '<tr>';
                                                            echo "<td>".$category."</td>";
                                                            echo "<td>".$financeKey."</td>";
                                                            echo "<td>".$partNumber."</td>";
                                                            echo "<td>".$blockSKU."</td>";
                                                            echo "<td>".$cut_sku_qty."</td>";
                                                            echo "<td>".$cut_sku_weight_avg."</td>";
                                                            echo "<td>".$cut_sku_weight_avg * $cut_sku_qty."</td>";
                                                            echo "<td>".$cut_sku_part_description."</td>"; // Output cut SKU part description
                                                            $pattern = '/(\d+\.?\d*)[xX\*](\d+\.?\d*)[xX\*](\d+\.?\d*)/'; // regular expression pattern to match dimensions and capture each dimension, including decimals
                                                            preg_match($pattern, $cut_sku_part_description, $matches); // search for dimensions in the string and capture each dimension
                                                            $length = isset($matches[1]) ? $matches[1] : ''; // extract the first captured dimension as length
                                                            $width = isset($matches[2]) ? $matches[2] : ''; // extract the second captured dimension as width
                                                            $height = isset($matches[3]) ? $matches[3] : ''; // extract the third captured dimension as height
                                                            echo "<td>".$length."x".$width."x".$height."</td>";
                                                            echo "<td>".(($length*$width*$height)/61020)."</td>";
                                                            echo "<td>".(($length*$width*$height*$cut_sku_qty)/61020)."</td>";
                                                            echo "<td>".$cut_sku_weight_avg/(($length*$width*$height)/61020)."</td>";
                                                            echo "<td>".($cut_sku_weight_avg * $cut_sku_qty)/(($length*$width*$height*$cut_sku_qty)/61020)."</td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                }
                                            }

                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="profile">
                                    <div class="table-responsive">
                                        <table id="state-saving-datatable" class="table activate-select dt-responsive nowrap" style="font-size: 11px;">
                                            <thead>
                                            <tr>
                                                <th>Block SKU </th>
                                                <th>Block SKU Count</th>
                                                <th>Sum Dry Block Weight</th>
                                                <!--                                                <th>Sum Original Rebonded Weight</th>-->
                                                <!--                                                <th>Difference</th>-->
                                                <th>Average Dry Block Weight</th>
                                            </tr>
                                            </thead>
                                            </tbody
                                            <?php include 'functions/funcCutTwo.php'; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane show active" id="settings">
                                    <div class="table-responsive">
                                        <table id="basic-datatable" class="table table-striped nowrap" style="font-size: 11px;">
                                            <thead>
                                            <tr>
                                                <th>Block ID</th>
                                                <th>Block Type</th>
                                                <th>Dry Block Weight</th>
                                                <th>T.Cut SKU Weight</th>
                                                <th>Act. Recycle Weight</th>
                                                <th>Block SKU</th>
                                                <th>Exp.Recycle Weight</th>
                                            </tr>
                                            </thead>
                                            </tbody
                                            <?php include 'functions/funcCut.php'; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
                <!-- end row-->
                <style>
                    p{
                        font-size: 11px;
                    }
                </style>


                <div class="col-md-3 col-xl-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Total Blocks Cut</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center mb-0">
                                                <?php echo number_format($cutblocks,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Total SKUs Cut</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center mb-0">
                                                <?php echo number_format($sumsku); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Total Dry Block Weight Cut (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($sumblocktype,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Cut SKUs Weight (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($totalsku,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Actual Recycle Weight (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($totalactual,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Expected Recycle Weight (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($totalexpected,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Recycle Weight Variance (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-danger mb-0">
                                                <?php echo number_format($totalactual-$totalexpected,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Recycle Variance</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <?php
                                            $recycle = number_format((($totalactual-$totalexpected)/$totalexpected)*100,2);
                                            if ($recycle>5){
                                                $text="text-danger";
                                                $notice = "Check cutting output data";
                                            }
                                            else{$text="text-success";}
                                            ?>
                                            <h6 class="d-flex align-items-center <?php echo $text;?>  mb-0">
                                                <?php echo $recycle; ?>%
                                            </h6>
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
<?php include '../parts/footer.php'; ?>