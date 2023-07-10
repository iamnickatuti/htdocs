<?php
header('Content-Type: application/json');
include '../cradle_config.php';
global $conn;
// Execute the SQL query to retrieve the product and raw material details
$sqlStatement = "SELECT
    skus1.name AS 'Raw Material',
    skus1.description AS 'RM Description',
    skus.name AS 'Sub Raw Material',
    skus.description AS 'SRM Description',
    bom_details.quantity AS 'Component Quantity',
    units.name AS 'uom'
FROM
    bom_details
LEFT JOIN skus ON skus.id = bom_details.sku_id
LEFT JOIN boms ON boms.id = bom_details.bom_id
LEFT JOIN skus AS skus1 ON skus1.id = boms.sku_id
LEFT JOIN units ON units.id = skus.unit_id
LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
LEFT JOIN bom_distribution_entries ON bom_distribution_entries.bom_id = bom_details.bom_id
WHERE
    bom_distribution_entries.bom_distribution_id = (
        SELECT MAX(bom_distribution_entries.bom_distribution_id)
        FROM bom_distribution_entries
    )
GROUP BY
    bom_details.bom_id,
    boms.name,
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

// Check if the query was successful
if ($result) {
    // Fetch the results and store them in an array
    $rawMaterials = array();
    while ($row = $result->fetch_assoc()) {
        $rawMaterials[] = $row;
    }
}
    // Convert the array to JSON
    $outputData = json_encode($rawMaterials);

   echo $outputData;

// Close the database connection
$conn->close();
?>
