<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// Fetch JSON data from URL 1
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, 'https://reports.moko.co.ke/demand/api/bomProjection.php');
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
$json1 = curl_exec($ch1);
curl_close($ch1);

// Fetch JSON data from URL 2
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, 'https://reports.moko.co.ke/demand/api/finishedProducts.php');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
$json2 = curl_exec($ch2);
curl_close($ch2);

// Decode JSON data
$json1Array = json_decode($json1, true);
$json2Array = json_decode($json2, true);

$products = $json1Array['products'];
$jsonOutput = [];

foreach ($products as $product) {
    $productNumber = $product['Product'];
    $components = $product['Components'];

    foreach ($json2Array as $item) {
        if ($item['Part Number'] === $productNumber) {
            foreach ($components as &$component) {
                $componentQuantity = (float) $component['Component_Quantity'];
                $multipliedValues = [];

                foreach ($item as $key => $value) {
                    if ($key !== 'Part Number' && $key !== 'Part Description' && $key !== 'UOM') {
                        $multipliedValues[$key] = $value * $componentQuantity;
                    } else {
                        $multipliedValues[$key] = $value;
                    }
                }

                $component['Multiplied_Values'] = $multipliedValues;
            }
        }
    }

    $jsonOutput[] = $product;
}

$output = json_encode($jsonOutput, JSON_PRETTY_PRINT);
echo $output;
?>
