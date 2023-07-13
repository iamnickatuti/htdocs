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
                            <tbody>
                            <?php
                            $json_data = file_get_contents('http://localhost/production/functions/finalTest.php');
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
                                            echo "<td>".(($length*$width*$height)/61023.7)."</td>";
                                            echo "<td>".(($length*$width*$height*$cut_sku_qty)/61023.7)."</td>";
                                            echo "<td>".$cut_sku_weight_avg/(($length*$width*$height)/61023.7)."</td>";
                                            echo "<td>".($cut_sku_weight_avg * $cut_sku_qty)/(($length*$width*$height*$cut_sku_qty)/61023.7)."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>


