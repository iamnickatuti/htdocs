<?php
/** @noinspection ALL */
include '../../cradle_config.php';
global $conn;
//header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/bomDetails.php');
$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/components.php');
$json1Array = json_decode($json1, true);
$json2Array = json_decode($json2, true);

$products = [];

foreach ($json2Array['products'] as $product) {
    $newProduct = array(
        'Product' => $product['Product'],
        'Product_Description' => $product['Product_Description'],
        'Components' => array()
    );

    foreach ($product['Components'] as $component) {
        if (strpos($component['Component_Part_Number'], 'WP') === 0) {
            // Find subcomponents
            $subComponents = array();

            foreach ($json1Array as $item) {
                if ($item['Target_sku_Part_Number'] === $component['Component_Part_Number']) {
                    $subComponent = array(
                        'Target_sku_Part_Number' => $item['Target_sku_Part_Number'],
                        'Target_sku_Part_Description' => $item['Target_sku_Part_Description'],
                        'Component_part_number' => $item['Component_part_number'],
                        'Component_part_description' => $item['Component_part_description'],
                        'component_quantity' => $item['component_quantity'],
                        'Component_Unit_of_measure' => $item['Component_Unit_of_measure'],
                        '%_bom_share' => $item['%_bom_share']
                    );

                    $subComponents[] = $subComponent;
                }
            }

            $component['Sub_Components'] = $subComponents;
            $newProduct['Components'][] = $component;
        } else {
            $newProduct['Components'][] = $component;
        }
    }

    $products[] = $newProduct;
}

$result = array('products' => $products);
$resultJson = json_encode($result, JSON_PRETTY_PRINT);
echo $resultJson;
?>
