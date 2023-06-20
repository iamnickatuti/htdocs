<?php
$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomProjection.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/finishedProducts.php');

$json1_decoded = json_decode($json1, true);
$json2_decoded = json_decode($json2, true);

$final_json = [];

foreach ($json1_decoded["products"] as $product) {
    foreach ($product["Components"] as $component) {
        if (isset($component["Sub_Components"])) {
            foreach ($component["Sub_Components"] as $sub_component) {
                if ($product["Product_Name"] == $json2_decoded["Part Number"]) {
                    $multiplied_values = [];
                    foreach ($json2_decoded as $key => $value) {
                        if (!in_array($key, ["Parent Category", "Sub Category", "Part Number", "Part Description", "UOM"])) {
                            $month = str_replace("\\/", "/", $key);
                            $multiplied_value = floatval($sub_component["component_quantity"]) * $value;
                            $multiplied_values[$month] = $multiplied_value;
                        }
                    }
                    $final_entry = [
                        "Product_Name" => $json2_decoded["Part Number"],
                        "Component_Part_Number" => $sub_component["Component_part_number"],
                        "Component_Part_Description" => $sub_component["Component_part_description"],
                        "Parent_Category" => $json2_decoded["Parent Category"],
                        "Sub_Category" => $json2_decoded["Sub Category"],
                        "Part_Number" => $json2_decoded["Part Number"],
                        "Part_Description" => $json2_decoded["Part Description"],
                        "UOM" => $json2_decoded["UOM"],
                        "Values" => $multiplied_values
                    ];
                    $final_json[] = $final_entry;
                }
            }
        }
    }
}

$final_json_encoded = json_encode($final_json, JSON_PRETTY_PRINT);
echo $final_json_encoded;
