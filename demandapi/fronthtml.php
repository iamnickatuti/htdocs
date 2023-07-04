<?php
function generateTable($data, $level = 1) {
    if ($level > 8) {
        return; // Limit the recursion to 8 levels deep
    }
    foreach ($data as $product => $details) {
        echo '<tr>';
        echo '<td rowspan="' . (count($details['Raw Materials']) + 1) . '">' . $product . '</td>';
        echo '<td rowspan="' . (count($details['Raw Materials']) + 1) . '">' . $details['Product Description'] . '</td>';
        echo '</tr>';

        if (isset($details['Raw Materials'])) {
            foreach ($details['Raw Materials'] as $raw_material) {
                echo '<tr>';
                echo '<td>' . $raw_material['Raw Material'] . '</td>';
                echo '<td>' . $raw_material['RM Description'] . '</td>';
                echo '<td>' . $raw_material['Component Quantity'] . '</td>';
                echo '<td>' . $raw_material['uom'] . '</td>';
                echo '</tr>';

                if (isset($raw_material['Sub Raw Materials'])) {
                    generateTable($raw_material['Sub Raw Materials'], $level + 1);
                }
            }
        }
    }
}

$url = "https://reports.moko.co.ke/demandapi/frontfinished";
$json_data = file_get_contents($url);
$data = json_decode($json_data, true);
?>

<table>
    <tr>
        <th>Product</th>
        <th>Product Description</th>
        <th>Raw Material</th>
        <th>RM Description</th>
        <th>Component Quantity</th>
        <th>uom</th>
    </tr>
    <?php generateTable($data); ?>
</table>
