<?php
header('Content-Type: application/json');
include '../cradle_config.php';
// Execute the SQL query to retrieve the product and raw material details
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

// Check if the query was successful
if ($result) {
    // Fetch the results and store them in an array
    $rawMaterials = array();
    while ($row = $result->fetch_assoc()) {
        $rawMaterials[] = $row;
    }

    // Convert the array to JSON
    $outputData = json_encode($rawMaterials);

    // Set headers for JSON response

    // Output the JSON data
    echo $outputData;
} else {
    echo "Error executing query: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
