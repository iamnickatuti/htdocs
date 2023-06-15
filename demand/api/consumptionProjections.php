<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomDetails.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/components.php');
$data2 = json_decode($json2, true);
$components = [];
foreach ($data2 as $item) {
    if (strpos($item['Component_part_number'], 'WP') === 0) {
        $components[] = [
            'Component_part_number' => $item['Component_part_number'],
            'Component_part_description' => $item['Component_part_description']
        ];
    }
}

$data1 = json_decode($json1, true);
$data1['Target_sku_Part_Number'][0]['Component_part_number'] = array_merge($data1['Target_sku_Part_Number'][0]['Component_part_number'], $components);

$output = json_encode($data1, JSON_PRETTY_PRINT);
echo $output;
