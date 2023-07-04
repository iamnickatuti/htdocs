<?php
function generateTable($data, $level = 1)
{
    if ($level === 1) {
        echo '<table>';
        echo '<tr>';
        echo '<th>Product</th>';
        echo '<th>Product Description</th>';
        echo '</tr>';
    }

    foreach ($data as $product => $details) {
        echo '<tr>';
        echo '<td>' . $product . '</td>';
        echo '<td>' . $details['Product Description'] . '</td>';
        echo '</tr>';

        if (isset($details['Raw Materials'])) {
            if ($level === 1) {
                echo '<tr>';
                echo '<th>Raw Material</th>';
                echo '<th>RM Description</th>';
                echo '<th>Component Quantity</th>';
                echo '<th>uom</th>';
                echo '</tr>';
            }

            foreach ($details['Raw Materials'] as $raw_material) {
                echo '<tr>';
                echo '<td>' . $raw_material['Raw Material'] . '</td>';
                echo '<td>' . $raw_material['RM Description'] . '</td>';
                echo '<td>' . $raw_material['Component Quantity'] . '</td>';
                echo '<td>' . $raw_material['uom'] . '</td>';
                echo '</tr>';

                if (isset($raw_material['Sub Raw Materials']) && $level < 8) {
                    generateTable($raw_material['Sub Raw Materials'], $level + 1);
                }
            }
        }
    }

    if ($level === 1) {
        echo '</table>';
    }
}

$url = "https://reports.moko.co.ke/demandapi/frontfinished";
$json_data = file_get_contents($url);
$data = json_decode($json_data, true);

generateTable($data);
?>
