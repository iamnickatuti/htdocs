<?php

include '../cradle_config.php';
// Recursive function to build nested array structure
function buildHierarchy($conn, $rawMaterial)
{
$hierarchy = array();

// Execute the SQL query to retrieve the raw material's details
$sqlStatement = "SELECT
production_lines.name AS 'Production_Line',
skus1.name AS 'Raw Material',
skus1.description AS 'RM Description',
skus.name AS 'Sub Raw Material',
skus.description AS 'SRM Description',
bom_details.quantity AS 'Component Quantity',
units.name AS 'uom',
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
bom_distribution_entries.bom_distribution_id = (
SELECT MAX(bom_distribution_entries.bom_distribution_id)
FROM bom_distribution_entries
)
AND skus1.name = '" . $rawMaterial . "'
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
bom_distribution_entries.share";

$result = $conn->query($sqlStatement);

if ($result) {
while ($row = $result->fetch_assoc()) {
$component = array(
'Production_Line' => $row['Production_Line'],
'Raw Material' => $row['Raw Material'],
'RM Description' => $row['RM Description'],
'Sub Raw Material' => $row['Sub Raw Material'],
'SRM Description' => $row['SRM Description'],
'Component Quantity' => $row['Component Quantity'],
'uom' => $row['uom'],
'%_BOM_Share' => $row['%_BOM_Share']
);

// Recursively build the hierarchy for sub raw materials
$subRawMaterial = $row['Sub Raw Material'];
if (strpos($subRawMaterial, 'WP') === 0) {
$subHierarchy = buildHierarchy($conn, $subRawMaterial);
$component['Sub Raw Materials'] = $subHierarchy;
}

$hierarchy[] = $component;
}
} else {
echo "Error executing query: " . $conn->error;
}

return $hierarchy;
}

// Execute the SQL query for Products and Raw Materials
$sqlStatement = "SELECT
production_lines.name AS 'Production_Line',
skus1.name AS 'Product',
skus1.description AS 'Product Description',
skus.name AS 'Raw Material',
skus.description AS 'RM Description',
bom_details.quantity AS 'Component Quantity',
units.name AS 'uom',
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
bom_distribution_entries.bom_distribution_id = (
SELECT MAX(bom_distribution_entries.bom_distribution_id)
FROM bom_distribution_entries
WHERE skus1.description LIKE 'FP%'
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
bom_distribution_entries.share";

$result = $conn->query($sqlStatement);

if ($result) {
$products = array();
while ($row = $result->fetch_assoc()) {
$product = $row;

$rawMaterial = $row['Raw Material'];

// Recursively build the hierarchy for raw materials
if (strpos($rawMaterial, 'WP') === 0) {
$rawMaterialHierarchy = buildHierarchy($conn, $rawMaterial);
$product['Sub Raw Materials'] = $rawMaterialHierarchy;
}

$products[] = $product;
}
} else {
echo "Error executing query: " . $conn->error;
}

$conn->close();
