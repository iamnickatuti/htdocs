<?php
// Assuming you have established a database connection
include '../../cradle_config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// The SQL query
$query = "select
  bom_details.bom_id,
  boms.name as 'BOM_Name',
  production_lines.name as 'Production_Line',
  skus1.name as 'Target_sku_Part_Number',
  skus1.description as 'Target_sku_Part_Description',
  skus.sku_type_id,
  skus.name as 'Component_Part_Number',
  skus.description as 'Component_Part_Description',
  bom_details.quantity as 'Component_Quantity',
  units.name as 'Component_Unit_of_Measure',
  bom_details.status,
  bom_distribution_entries.bom_distribution_id as 'bom_distribution_id',
  bom_distribution_entries.share as '%_BOM_Share'
from
  (
    (
      (
        (
          (
            (
              (
                bom_details
                left join skus on skus.id = bom_details.sku_id
              )
              left join boms on boms.id = bom_details.bom_id
            )
            left join production_lines on production_lines.id = boms.production_line_id
          )
          left join skus as skus1 on skus1.id = boms.sku_id
        )
        left join units on units.id = skus.unit_id
      )
      left join sku_types on sku_types.id = skus.sku_type_id
    )
    left join bom_distribution_entries on bom_distribution_entries.bom_id = bom_details.bom_id
  )
group by
  bom_details.bom_id,
  boms.name,
  production_lines.name,
  skus1.name,
  skus1.description,
  skus.sku_type_id,
  skus.name,
  skus.description,
  bom_details.quantity,
  units.name,
  bom_details.status,
  bom_distribution_entries.bom_distribution_id,
  bom_distribution_entries.share
Having
  bom_distribution_id = (
    select
      max(bom_distribution_entries.bom_distribution_id)
    from
      bom_distribution_entries
      
      where skus1.description  like 'WP%'
  );";

// Execute the query
$result = mysqli_query($conn, $query);

// Store the results in an associative array
$results = array();

// Loop through each row of the query result
while ($row = mysqli_fetch_assoc($result)) {
    // Create an associative array for each row
    $resultRow = array(
        'Target_sku_Part_Number' => $row['Target_sku_Part_Number'],
        'Target_sku_Part_Description' => $row['Target_sku_Part_Description'],
        'Component_Part_Number' => $row['Component_Part_Number'],
        'Component_Part_Description' => $row['Component_Part_Description'],
        'Component_Quantity' => $row['Component_Quantity'],
        'Component_Unit_of_Measure' => $row['Component_Unit_of_Measure'],
        '%_BOM_Share' => $row['%_BOM_Share']
    );

    // Add the row to the results array
    $results[] = $resultRow;
}

// Convert the results array to JSON
$jsonResult = json_encode($results);

// Output the JSON result
echo $jsonResult;

// Close the database connection
mysqli_close($conn);
?>
