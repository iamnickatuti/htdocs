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
                        'bom_id' => $item['bom_id'],
                        'BOM_Name' => $item['BOM_Name'],
                        'Production_Line' => $item['Production_Line'],
                        'Target_sku_Part_Number' => $item['Target_sku_Part_Number'],
                        'Target_sku_Part_Description' => $item['Target_sku_Part_Description'],
                        'sku_type_id' => $item['sku_type_id'],
                        'Component_part_number' => $item['Component_part_number'],
                        'Component_part_description' => $item['Component_part_description'],
                        'component_quantity' => $item['component_quantity'],
                        'Component_Unit_of_measure' => $item['Component_Unit_of_measure'],
                        'status' => $item['status'],
                        'bom_distribution_id' => $item['bom_distribution_id'],
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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Components</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<h2>Product Components</h2>
<table>
    <tr>
        <th>Product</th>
        <th>Product Description</th>
        <th>Component Part Number</th>
        <th>Component Part Description</th>
        <th>Component Quantity</th>
        <th>Component Unit of Measure</th>
    </tr>
    <?php
    $data = json_decode($resultJson, true);


    foreach ($data['products'] as $product) { ?>
        <?php foreach ($product['Components'] as $component) { ?>
            <tr>
                <td rowspan="<?php echo isset($component['Sub_Components']) ? count($component['Sub_Components']) + 1 : 1; ?>">
                    <?php echo $product['Product']; ?>
                </td>
                <td rowspan="<?php echo isset($component['Sub_Components']) ? count($component['Sub_Components']) + 1 : 1; ?>">
                    <?php echo $product['Product_Description']; ?>
                </td>
                <td><?php echo $component['Component_Part_Number']; ?></td>
                <td><?php echo $component['Component_Part_Description']; ?></td>
                <td><?php echo $component['Component_Quantity']; ?></td>
                <td><?php echo $component['Component_Unit_of_Measure']; ?></td>
            </tr>
            <?php if (isset($component['Sub_Components'])) { ?>
                <?php foreach ($component['Sub_Components'] as $subComponent) { ?>
                    <tr>
                        <td><?php echo $subComponent['Component_part_number']; ?></td>
                        <td><?php echo $subComponent['Component_part_description']; ?></td>
                        <td><?php echo $subComponent['component_quantity']; ?></td>
                        <td><?php echo $subComponent['Component_Unit_of_measure']; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</table>
</body>
</html>
