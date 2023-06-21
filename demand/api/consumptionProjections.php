<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomProjection.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/finishedProducts.php');
// Decode the JSON data
$data1 = json_decode($json1, true);
$data2 = json_decode($json2, true);

// Extract the necessary values
$product = $data1['products'][0]['Product'];
$description = $data1['products'][0]['Product_Description'];
$component = $data1['products'][0]['Components'][0];
$quantity = $component['Component_Quantity'];

// Perform the multiplication
$result = array();
foreach ($data2 as $item) {
    $multipliedValues = array();
    foreach ($item as $key => $value) {
        if ($key !== "Parent Category" && $key !== "Sub Category" && $key !== "Part Number" && $key !== "Part Description" && $key !== "UOM") {
            $multipliedValues[$key] = $value * $quantity;
        } else {
            $multipliedValues[$key] = $value;
        }
    }

    $component['Multiplied_Values'] = $multipliedValues;
    $result[] = $component;
}

// Construct the final result
$finalResult = array(
    "Product" => $product,
    "Product_Description" => $description,
    "Components" => $result
);

// Encode the final result back to JSON
$jsonResult = json_encode($finalResult, JSON_PRETTY_PRINT);
echo $jsonResult;
?>


