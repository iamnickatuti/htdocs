<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomProjection.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/finishedProducts.php');
$json1_decoded = json_decode($json1, true);
$json2_decoded = json_decode($json2, true);

$final_json = [];

foreach ($json1_decoded[0]["products"] as $product) {
    foreach ($product["Components"] as $component) {
        if (isset($component["Sub_Components"])) {
            foreach ($component["Sub_Components"] as $sub_component) {
                if ($product["Product"] == $json2_decoded[0]["Part Number"]) {
                    $multiplied_values = [];
                    foreach ($json2_decoded[0]["Values"] as $key => $value) {
                        $month = str_replace("\\/", "/", $key);
                        $multiplied_value = floatval($sub_component["component_quantity"]) * $value;
                        $multiplied_values[$month] = $multiplied_value;
                    }
                    $final_entry = [
                        "Product" => $json2_decoded[0]["Part Number"],
                        "Component_Part_Number" => $sub_component["Component_Part_Number"],
                        "Component_Part_Description" => $sub_component["Component_part_description"],
                        "Parent_Category" => $json2_decoded[0]["Parent Category"],
                        "Sub_Category" => $json2_decoded[0]["Sub Category"],
                        "Part_Number" => $json2_decoded[0]["Part Number"],
                        "Part_Description" => $json2_decoded[0]["Part Description"],
                        "UOM" => $json2_decoded[0]["UOM"],
                        "Values" => $multiplied_values
                    ];
                    $final_json[] = $final_entry;
                }
            }
        } else {
            if ($product["Product"] == $json2_decoded[0]["Part Number"]) {
                $multiplied_values = [];
                foreach ($json2_decoded[0]["Values"] as $key => $value) {
                    $month = str_replace("\\/", "/", $key);
                    $multiplied_value = floatval($component["Component_Quantity"]) * $value;
                    $multiplied_values[$month] = $multiplied_value;
                }
                $final_entry = [
                    "Product" => $json2_decoded[0]["Part Number"],
                    "Component_Part_Number" => $component["Component_Part_Number"],
                    "Component_Part_Description" => $component["Component_Part_Description"],
                    "Parent_Category" => $json2_decoded[0]["Parent Category"],
                    "Sub_Category" => $json2_decoded[0]["Sub Category"],
                    "Part_Number" => $json2_decoded[0]["Part Number"],
                    "Part_Description" => $json2_decoded[0]["Part Description"],
                    "UOM" => $json2_decoded[0]["UOM"],
                    "Values" => $multiplied_values
                ];
                $final_json[] = $final_entry;
            }
        }
    }
}