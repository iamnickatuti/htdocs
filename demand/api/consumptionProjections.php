<?php

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
$data = json_decode($output, true);

if (is_array($data)) {
    foreach ($data as $product) {
        echo "<h2>Product: " . $product['Product'] . "</h2>";
        echo "<p>Product Description: " . $product['Product_Description'] . "</p>";

        echo "<table>";
        echo "<tr>";
        echo "<th>Component Part Number</th>";
        echo "<th>Component Part Description</th>";
        echo "<th>Component Quantity</th>";
        echo "<th>Component Unit of Measure</th>";
        echo "<th>% BOM Share</th>";
        echo "<th>Parent Category</th>";
        echo "<th>Sub Category</th>";
        echo "<th>July 2022</th>";
        echo "<th>August 2022</th>";
        echo "<th>September 2022</th>";
        echo "<th>October 2022</th>";
        echo "<th>November 2022</th>";
        echo "<th>December 2022</th>";
        echo "<th>January 2023</th>";
        echo "<th>February 2023</th>";
        echo "<th>March 2023</th>";
        echo "<th>April 2023</th>";
        echo "<th>May 2023</th>";
        echo "<th>June 2023</th>";
        echo "</tr>";

        foreach ($product['Components'] as $component) {
            echo "<tr>";
            echo "<td>" . $component['Component_Part_Number'] . "</td>";
            echo "<td>" . $component['Component_Part_Description'] . "</td>";
            echo "<td>" . $component['Component_Quantity'] . "</td>";
            echo "<td>" . $component['Component_Unit_of_Measure'] . "</td>";
            echo "<td>" . $component['%_BOM_Share'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['Parent Category'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['Sub Category'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['July/2022'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['August/2022'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['September/2022'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['October/2022'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['November/2022'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['December/2022'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['January/2023'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['February/2023'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['March/2023'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['April/2023'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['May/2023'] . "</td>";
            echo "<td>" . $component['Multiplied_Values']['June/2023'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
} else {
    echo "Invalid JSON string.";
}
?>

</body>
</html>


