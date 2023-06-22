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
    echo "<label for='partNumberSelect'>Select Part Number:</label>";
    echo "<select id='partNumberSelect' onchange='filterTable()'>";
    echo "<option value='all'>All</option>";

    // Collect unique part numbers
    $uniquePartNumbers = array();
    foreach ($data as $product) {
        foreach ($product['Components'] as $component) {
            $partNumber = $component['Component_Part_Number'];
            if (!in_array($partNumber, $uniquePartNumbers)) {
                $uniquePartNumbers[] = $partNumber;
            }
        }
    }

    // Generate dropdown options
    foreach ($uniquePartNumbers as $partNumber) {
        echo "<option value='" . $partNumber . "'>" . $partNumber . "</option>";
    }

    echo "</select>";

    echo "<table id='componentTable' class='table table-striped'>";
    echo "<tr>";
    echo "<th>Component Part Number</th>";
    echo "<th>Component Part Description</th>";
    echo "<th>Component Quantity</th>";
    echo "<th>Component Unit of Measure</th>";
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

    // Display the original table
    foreach ($data as $product) {
        foreach ($product['Components'] as $component) {
            echo "<tr>";
            echo "<td>" . $component['Component_Part_Number'] . "</td>";
            echo "<td>" . $component['Component_Part_Description'] . "</td>";
            echo "<td>" . $component['Component_Quantity'] . "</td>";
            echo "<td>" . $component['Component_Unit_of_Measure'] . "</td>";
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
    }
    echo "</table>";

    echo "<script>
    function filterTable() {
        var select = document.getElementById('partNumberSelect');
        var table = document.getElementById('componentTable');
        var rows = table.getElementsByTagName('tr');
        var filterValue = select.value;
      
        for (var i = 1; i < rows.length; i++) {
            var row = rows[i];
            var partNumber = row.cells[0].innerHTML;
      
            if (filterValue === 'all' || partNumber === filterValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
    </script>";
} else {
    echo "Error: Failed to parse JSON data.";
}
?>
