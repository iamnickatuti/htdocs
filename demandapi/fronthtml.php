<?php
$url = 'https://reports.moko.co.ke/demandapi/frontfinished';
$json_data = file_get_contents($url);
$data = json_decode($json_data, true);

$html = '<table>';
    $html .= '<tr><th>Product</th><th>Product Description</th></tr>';

    foreach ($data as $product => $details) {
    $html .= '<tr>';
        $html .= '<td>' . $product . '</td>';
        $html .= '<td>' . $details['Product Description'] . '</td>';
        $html .= '</tr>';

    if (isset($details['Raw Materials'])) {
    $html .= '<tr><th>Raw Material</th><th>RM Description</th><th>Component Quantity</th><th>uom</th></tr>';

    foreach ($details['Raw Materials'] as $raw_material) {
    $html .= '<tr>';
        $html .= '<td>' . $raw_material['Raw Material'] . '</td>';
        $html .= '<td>' . $raw_material['RM Description'] . '</td>';
        $html .= '<td>' . $raw_material['Component Quantity'] . '</td>';
        $html .= '<td>' . $raw_material['uom'] . '</td>';
        $html .= '</tr>';
    }
    }
    }

    $html .= '</table>';

echo $html;

