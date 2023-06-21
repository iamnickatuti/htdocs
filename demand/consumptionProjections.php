<?php include '../parts/footer.php'; ?>
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomProjection.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/finishedProducts.php');

$json1Array = json_decode($json1, true);
$json2Array = json_decode($json2, true);

$jsonOutput = [];

if (isset($json1Array['products']) && isset($json2Array)) {
    foreach ($json1Array['products'] as $product) {
        $productNumber = $product['Product'];
        $components = $product['Components'];

        $productOutput = [
            'Product' => $productNumber,
            'Product_Description' => $product['Product_Description'],
            'Components' => [],
        ];

        foreach ($components as $component) {
            if (isset($component['Component_Part_Number']) && isset($component['Component_Quantity'])) {
                $componentNumber = $component['Component_Part_Number'];
                $componentQuantity = (float) $component['Component_Quantity'];
                $componentOutput = $component;

                $matchingItem = null;
                foreach ($json2Array as $item) {
                    if (isset($item['Part Number']) && $item['Part Number'] === $productNumber) {
                        $matchingItem = $item;
                        break;
                    }
                }

                if ($matchingItem !== null) {
                    $multipliedValues = [];
                    foreach ($matchingItem as $key => $value) {
                        if ($key !== 'Part Number' && $key !== 'Part Description' && $key !== 'UOM') {
                            if (is_numeric($value)) {
                                $multipliedValues[$key] = round($value * $componentQuantity, 2);
                            } else {
                                $multipliedValues[$key] = $value;
                            }
                        }
                    }
                    $componentOutput['Multiplied_Values'] = $multipliedValues;
                } else {
                    $componentOutput['Multiplied_Values'] = [];
                }

                $productOutput['Components'][] = $componentOutput;
            }
        }

        $jsonOutput[] = $productOutput;
    }
}

$output = json_encode($jsonOutput, JSON_PRETTY_PRINT);

echo $output;