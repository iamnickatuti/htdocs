<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

$json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomDetails.php');
$json2 = file_get_contents('https://reports.moko.co.ke/demand/api/components.php');

$json = '{
    "products": [
        {
            "Product": "FP-MT-BM001",
            "Product_Description": "FP:Moko Mattress:74x36x6:Burger",
            "Components": [
                {
                    "Component_Part_Number": "RM-MT-FB019",
                    "Component_Part_Description": "Rm:mattresses:mattress Covering - Moko",
                    "Component_Quantity": "2.2800",
                    "Component_Unit_of_Measure": "M",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "RM-MT-AC013",
                    "Component_Part_Description": "Rm:mattresses:mattress Labels - Moko",
                    "Component_Quantity": "1.0000",
                    "Component_Unit_of_Measure": "PC",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "RM-MT-TH005",
                    "Component_Part_Description": "Rm:sofa Materials:sewing Thread 3 Ply",
                    "Component_Quantity": "0.0500",
                    "Component_Unit_of_Measure": "PC",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "RM-SF-AD002",
                    "Component_Part_Description": "RM:Sofa Materials:Conta",
                    "Component_Quantity": "0.0000",
                    "Component_Unit_of_Measure": "L",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "RM-MT-AC014",
                    "Component_Part_Description": "Rm:mattresses:mattress Piping",
                    "Component_Quantity": "13.2000",
                    "Component_Unit_of_Measure": "M",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "RM-MT-AC012",
                    "Component_Part_Description": "Rm:mattresses:mattress Tubing(Branded)",
                    "Component_Quantity": "0.3900",
                    "Component_Unit_of_Measure": "KG",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "RM-MT-CT001",
                    "Component_Part_Description": "Rm:mattresses:cellotape",
                    "Component_Quantity": "0.1700",
                    "Component_Unit_of_Measure": "PC",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "RM-MT-BL001",
                    "Component_Part_Description": "Rm:wood Products:barcode Labels",
                    "Component_Quantity": "2.0000",
                    "Component_Unit_of_Measure": "PC",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                },
                {
                    "Component_Part_Number": "WP-MT-BM001",
                    "Component_Part_Description": "Wp:mattresses:74X36X6 - B",
                    "Component_Quantity": "1.0000",
                    "Component_Unit_of_Measure": "PC",
                    "Status": "1",
                    "BOM_Distribution_ID": "2",
                    "%_BOM_Share": "100.00"
                }
            ]
        }
    ]
}';

$data = json_decode($json1, true);

$subcomponents = '[
  {
    "bom_id": "1",
    "BOM_Name": "Sub - 1",
    "Production_Line": "Mattresses Finishing",
    "Target_sku_Part_Number": "WP-MT-BM001",
    "Target_sku_Part_Description": "Wp:mattresses:74X36X6 - B",
    "sku_type_id": "1",
    "Component_part_number": "RM-SF-AD002",
    "Component_part_description": "RM:Sofa Materials:Conta",
    "component_quantity": "0.0800",
    "Component_Unit_of_measure": "L",
    "status": "1",
    "bom_distribution_id": "2",
    "%_bom_share": "100.00"
  },
  {
    "bom_id": "1",
    "BOM_Name": "Sub - 1",
    "Production_Line": "Mattresses Finishing",
    "Target_sku_Part_Number": "WP-MT-BM001",
    "Target_sku_Part_Description": "Wp:mattresses:74X36X6 - B",
    "sku_type_id": "3",
    "Component_part_number": "WP-MF-CR001",
    "Component_part_description": "WP:Cores:75x37x5",
    "component_quantity": "1.0000",
    "Component_Unit_of_measure": "PC",
    "status": "1",
    "bom_distribution_id": "2",
    "%_bom_share": "100.00"
  },
  {
    "bom_id": "1",
    "BOM_Name": "Sub - 1",
    "Production_Line": "Mattresses Finishing",
    "Target_sku_Part_Number": "WP-MT-BM001",
    "Target_sku_Part_Description": "Wp:mattresses:74X36X6 - B",
    "sku_type_id": "3",
    "Component_part_number": "WP-MF-BN001",
    "Component_part_description": "WP:Buns:75x37x0.75",
    "component_quantity": "2.0000",
    "Component_Unit_of_measure": "PC",
    "status": "1",
    "bom_distribution_id": "2",
    "%_bom_share": "100.00"
  }
]';

$subcomponentsData = json_decode($json2, true);

foreach ($data['products'] as &$product) {
    foreach ($product['Components'] as &$component) {
        if ($component['Component_Part_Number'] === 'WP-MT-BM001') {
            $component['Components'] = $subcomponentsData;
            break;
        }
    }
}

$result = json_encode($data);

echo $result;
