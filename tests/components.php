<?php
include '../cradle_config.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
global $conn;
$query = "
SELECT
  skus1.name AS 'Sub Component',
  skus1.description AS 'Sub Component Description',
  skus.name AS 'Component',
  skus.description AS 'Component_Description',
  bom_details.quantity AS 'Component_Quantity',
  units.name AS 'Component_Unit_of_Measure',
  bom_distribution_entries.share AS '%_BOM_Share'
FROM
  bom_details
  LEFT JOIN skus ON skus.id = bom_details.sku_id
  LEFT JOIN boms ON boms.id = bom_details.bom_id
  LEFT JOIN production_lines ON production_lines.id = boms.production_line_id
  LEFT JOIN skus AS skus1 ON skus1.id = boms.sku_id
  LEFT JOIN units ON units.id = skus.unit_id
  LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
  LEFT JOIN bom_distribution_entries ON bom_distribution_entries.bom_id = bom_details.bom_id
WHERE
  skus1.name LIKE 'WP%'
  AND bom_distribution_entries.bom_distribution_id = (
    SELECT
      MAX(bde.bom_distribution_id)
    FROM
      bom_distribution_entries AS bde
    WHERE
      bde.bom_id = bom_details.bom_id
  )
GROUP BY
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
  bom_distribution_entries.share;

";

$result = mysqli_query($conn, $query);
$data = array();
while ($row = mysqli_fetch_assoc($result)) { $data[] = $row; }
echo json_encode($data);
?>
