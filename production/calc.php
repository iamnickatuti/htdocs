<?php
include '../parts/header.php';
?>

<div class="card">
        <div class="card-body">
            <h4 class="card-title"></h4>
            <p class="card-subtitle mb-4">Choose a date range to show data (Currently showing last 7 days)</p>
            <div class="table-responsive">
                <table id="basic-datatable" class="table table-striped nowrap" style="font-size: 11px;">
                    <thead>
                    <tr>
                        <th>Count</th>
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
                                    echo "<td>".$count++."</td>";
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
        </div> <!-- end card -->
    </div><!-- end col-->