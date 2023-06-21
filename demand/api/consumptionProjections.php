        <?php
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        $json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomProjection.php');
        $json2 = file_get_contents('https://reports.moko.co.ke/demand/api/finishedProducts.php');
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