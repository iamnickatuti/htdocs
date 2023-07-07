<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// JSON URLs
$json1Url = "https://reports.moko.co.ke/demandapi/projmix.php";
$json2Url = "https://reports.moko.co.ke/demandapi/processed.json";

// Retrieve JSON data
$json1 = file_get_contents($json1Url);
$json2 = file_get_contents($json2Url);

// Convert JSON data to associative arrays
$data1 = json_decode($json1, true);
$data2 = json_decode($json2, true);

// Recursive function to calculate multiplied values
function calculateMultipliedValues($rawMaterial, $multipliers, $level = 1) {
$componentQuantity = $rawMaterial['Component Quantity'];
$multipliedValues = array();
foreach ($multipliers as $key => $value) {
if (strpos($key, '/') !== false) {
$month = str_replace('/', '', $key);
$multipliedValue = floatval($componentQuantity) * floatval($value);
$multipliedValues[$key] = $multipliedValue;
}
}
if ($level < 4 && isset($rawMaterial['Sub Raw Materials']) && is_array($rawMaterial['Sub Raw Materials'])) {
$subRawMaterials = $rawMaterial['Sub Raw Materials'];
foreach ($subRawMaterials as &$subRawMaterial) {
$subRawMaterial['Multiplied_Values'] = calculateMultipliedValues($subRawMaterial, $multipliers, $level + 1);
if (isset($subRawMaterial['Sub Raw Materials'])) {
$subSubRawMaterials = $subRawMaterial['Sub Raw Materials'];
foreach ($subSubRawMaterials as &$subSubRawMaterial) {
$subSubRawMaterial['Multiplied_Values'] = calculateMultipliedValues($subSubRawMaterial, $multipliers, $level + 2);
if (isset($subSubRawMaterial['Sub Raw Materials'])) {
$subSubSubRawMaterials = $subSubRawMaterial['Sub Raw Materials'];
foreach ($subSubSubRawMaterials as &$subSubSubRawMaterial) {
$subSubSubRawMaterial['Multiplied_Values'] = calculateMultipliedValues($subSubSubRawMaterial, $multipliers, $level + 3);
}
$subSubRawMaterial['Sub Raw Materials'] = $subSubSubRawMaterials;
}
}
$subRawMaterial['Sub Raw Materials'] = $subSubRawMaterials;
}
}
$multipliedValues['Sub Raw Materials'] = $subRawMaterials;
}
return $multipliedValues;
}

// Process data from json2 and update json1
foreach ($data1 as &$item) {
$partNumber = $item['Part Number'];
if (isset($data2[$partNumber])) {
$rawMaterials = $data2[$partNumber]['Raw Materials'];
$multipliers = $item;
foreach ($rawMaterials as &$rawMaterial) {
$rawMaterial['Multiplied_Values'] = calculateMultipliedValues($rawMaterial, $multipliers);
if (isset($rawMaterial['Sub Raw Materials'])) {
$subRawMaterials = $rawMaterial['Sub Raw Materials'];
foreach ($subRawMaterials as &$subRawMaterial) {
$subRawMaterial['Multiplied_Values'] = calculateMultipliedValues($subRawMaterial, $multipliers);
if (isset($subRawMaterial['Sub Raw Materials'])) {
$subSubRawMaterials = $subRawMaterial['Sub Raw Materials'];
foreach ($subSubRawMaterials as &$subSubRawMaterial) {
$subSubRawMaterial['Multiplied_Values'] = calculateMultipliedValues($subSubRawMaterial, $multipliers);
if (isset($subSubRawMaterial['Sub Raw Materials'])) {
$subSubSubRawMaterials = $subSubRawMaterial['Sub Raw Materials'];
foreach ($subSubSubRawMaterials as &$subSubSubRawMaterial) {
$subSubSubRawMaterial['Multiplied_Values'] = calculateMultipliedValues($subSubSubRawMaterial, $multipliers);
}
$subSubRawMaterial['Sub Raw Materials'] = $subSubSubRawMaterials;
}
}
$subRawMaterial['Sub Raw Materials'] = $subSubRawMaterials;
}
}
$rawMaterial['Sub Raw Materials'] = $subRawMaterials;
}
}
$item['Raw Materials'] = $rawMaterials;
}
}

// Convert the updated data to JSON
$outputData = json_encode($data1, JSON_PRETTY_PRINT);

// Output the JSON data
echo $outputData;
