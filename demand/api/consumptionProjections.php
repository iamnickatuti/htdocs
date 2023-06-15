<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
$json = file_get_contents('https://reports.moko.co.ke/demand/api/bomDetails.php');
$json = '[
  {
    "bom_id": "13",
    "BOM_Name": "Finished -1",
    "Production_Line": "Mattresses Finishing",
    "Target_sku_Part_Number": "FP-MT-BM001",
    "Target_sku_Part_Description": "FP:Moko Mattress:74x36x6:Burger",
    "sku_type_id": "1",
    "Component_part_number": "RM-MT-FB019",
    "Component_part_description": "Rm:mattresses:mattress Covering - Moko",
    "component_quantity": "2.2800",
    "Component_Unit_of_measure": "M",
    "status": "1",
    "bom_distribution_id": "2",
    "%_bom_share": "100.00"
  },
  {
    "bom_id": "13",
    "BOM_Name": "Finished -1",
    "Production_Line": "Mattresses Finishing",
    "Target_sku_Part_Number": "FP-MT-BM001",
    "Target_sku_Part_Description": "FP:Moko Mattress:74x36x6:Burger",
    "sku_type_id": "1",
    "Component_part_number": "RM-MT-AC013",
    "Component_part_description": "Rm:mattresses:mattress Labels - Moko",
    "component_quantity": "1.0000",
    "Component_Unit_of_measure": "PC",
    "status": "1",
    "bom_distribution_id": "2",
    "%_bom_share": "100.00"
  },
  {
    "bom_id": "13",
    "BOM_Name": "Finished -1",
    "Production_Line": "Mattresses Finishing",
    "Target_sku_Part_Number": "FP-MT-BM001",
    "Target_sku_Part_Description": "FP:Moko Mattress:74x36x6:Burger",
    "sku_type_id": "1",
    "Component_part_number": "RM-MT-TH005",
    "Component_part_description": "Rm:sofa Materials:sewing Thread 3 Ply",
    "component_quantity": "0.0500",
    "Component_Unit_of_measure": "PC",
    "status": "1",
    "bom_distribution_id": "2",
    "%_bom_share": "100.00"
  }
]';

