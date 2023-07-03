<?php
// Assuming you have a database connection established
include '../cradle_config.php';

// Execute the SQL query
$query = "SELECT
  production_lines.name AS 'Production_Line',
  skus1.name AS 'Product',
  skus1.description AS 'Product Description',
  CASE
    WHEN skus.name LIKE 'WP%' THEN sub_raw_materials.name
    ELSE skus.name
  END AS 'Raw Material',
  CASE
    WHEN skus.name LIKE 'WP%' THEN sub_raw_materials.description
    ELSE skus.description
  END AS 'RM Description',
  bom_details.quantity AS 'Component Quantity',
  units.name AS 'uom',
  bom_distribution_entries.share AS '%_BOM_Share'
FROM
  bom_details
  LEFT JOIN boms ON boms.id = bom_details.bom_id
  LEFT JOIN production_lines ON production_lines.id = boms.production_line_id
  LEFT JOIN skus AS skus1 ON skus1.id = boms.sku_id
  LEFT JOIN skus ON skus.id = bom_details.sku_id
  LEFT JOIN units ON units.id = skus.unit_id
  LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
  LEFT JOIN bom_distribution_entries ON bom_distribution_entries.bom_id = bom_details.bom_id
  LEFT JOIN skus AS sub_raw_materials ON sub_raw_materials.id = bom_details.sku_id
WHERE
  (
    bom_distribution_entries.bom_distribution_id = (
      SELECT
        MAX(bom_distribution_entries.bom_distribution_id)
      FROM
        bom_distribution_entries
      WHERE
        skus1.description LIKE 'FP%'
    )
    OR skus.name LIKE 'WP%'
  )
GROUP BY
  bom_details.bom_id,
  boms.name,
  production_lines.name,
  skus1.name,
  skus1.description,
  skus.sku_type_id,
  skus.name,
  sub_raw_materials.name,
  skus.description,
  sub_raw_materials.description,
  bom_details.quantity,
  units.name,
  bom_details.status,
  bom_distribution_entries.bom_distribution_id,
  bom_distribution_entries.share";

$result = mysqli_query($conn, $query);

// Fetch the results
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Process the data to replace Raw Materials with Sub Raw Materials
function replaceRawMaterial(&$data, $iteration, $conn) {
    $rawMaterials = array_column($data, 'Raw Material');
    $wpRawMaterials = array_filter($rawMaterials, function ($item) {
        return strpos($item, 'WP') === 0;
    });

    if (empty($wpRawMaterials) || $iteration === 5) {
        return;
    }

    $wpRawMaterialsString = "'" . implode("', '", $wpRawMaterials) . "'";

    $query = "SELECT
      skus.name AS 'Raw Material',
      skus.description AS 'RM Description',
      sub_raw_materials.name AS 'Sub Raw Material',
      sub_raw_materials.description AS 'SRM Description'
    FROM
      skus
      INNER JOIN skus AS sub_raw_materials ON sub_raw_materials.id = skus.sub_raw_material_id
    WHERE
      skus.name IN ($wpRawMaterialsString)";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $rawMaterial = $row['Raw Material'];
        $matchingRawMaterials = array_filter($data, function ($item) use ($rawMaterial) {
            return $item['Raw Material'] === $rawMaterial;
        });

        foreach ($matchingRawMaterials as &$matchingRawMaterial) {
            $matchingRawMaterial['Raw Material'] = $row['Sub Raw Material'];
            $matchingRawMaterial['RM Description'] = $row['SRM Description'];
        }
    }

    replaceRawMaterial($data, $iteration + 1, $conn);
}

replaceRawMaterial($data, 1, $conn);

// Close the database connection
mysqli_close($conn);

// Output the data
foreach ($data as $row) {
    echo implode(', ', $row) . '<br>';
}
?>

