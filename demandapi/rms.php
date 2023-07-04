<?php
include '../cradle_config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Recursive function to substitute raw materials starting with WP
function substituteRawMaterial($conn, $rawMaterial, $depth = 1)
{
    // Execute the SQL query for Sub Raw Materials
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

    // Check if the query was successful
    if ($result) {
        // Fetch the results
        $subRawMaterials = array();
        while ($row = $result->fetch_assoc()) {
            $subRawMaterial = $row;

            // Check if Sub Raw Material starts with WP and depth is less than or equal to 7
            if (strpos($row['Sub Raw Material'], 'WP') === 0 && $depth <= 8) {
                // Call the function recursively to substitute the Sub Raw Material and increment the depth
                $subSubRawMaterials = substituteRawMaterial($conn, $row['Sub Raw Material'], $depth + 1);

                // Add the sub-sub raw materials to the sub raw material
                $subRawMaterial['Sub-Sub Raw Materials'] = $subSubRawMaterials;
            }

            $subRawMaterials[] = $subRawMaterial;
        }

        // Return the sub raw materials array
        return $subRawMaterials;
    } else {
        echo "Error executing query: " . $conn->error;
    }
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
global $conn;
$result = $conn->query($sqlStatement);

// Check if the query was successful
if ($result) {
    // Fetch the results
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $product = $row;

        // Check if Raw Material starts with WP
        if (strpos($row['Raw Material'], 'WP') === 0) {
            // Call the function to substitute the Raw Material
            $subRawMaterials = substituteRawMaterial($conn, $row['Raw Material']);

            // Add the sub raw materials to the product
            $product['Sub Raw Materials'] = $subRawMaterials;
        }

        $products[] = $product;
    }
} else {
    echo "Error executing query: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product and Raw Material Details</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        ul ul {
            margin-left: 20px;
        }
    </style>
</head>
<body>
<h1>Product and Raw Material Details</h1>
<table>
    <thead>
    <tr>
        <th>Production Line</th>
        <th>Product</th>
        <th>Product Description</th>
        <th>Raw Material</th>
        <th>RM Description</th>
        <th>Component Quantity</th>
        <th>Unit of Measure</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $product) : ?>
        <tr>
            <td><?php echo $product['Production_Line']; ?></td>
            <td><?php echo $product['Product']; ?></td>
            <td><?php echo $product['Product Description']; ?></td>
            <td><?php echo $product['Raw Material']; ?></td>
            <td><?php echo $product['RM Description']; ?></td>
            <td><?php echo $product['Component Quantity']; ?></td>
            <td><?php echo $product['uom']; ?></td>
                        <?php if (isset($product['Sub Raw Materials'])) : ?>
                        <?php foreach ($product['Sub Raw Materials'] as $subRawMaterial) : ?>
                <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $subRawMaterial['Sub Raw Material']; ?></td>
                            <td><?php echo $subRawMaterial['SRM Description']; ?></td>
                            <td><?php echo $subRawMaterial['Component Quantity']; ?></td>
                            <td><?php echo $subRawMaterial['uom']; ?></td>
                </tr>
                        <?php if (isset($subRawMaterial['Sub-Sub Raw Materials'])) : ?>
                        <?php foreach ($subRawMaterial['Sub-Sub Raw Materials'] as $subSubRawMaterial) : ?>
                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $subSubRawMaterial['Sub Raw Material']; ?></td>
                                        <td><?php echo $subSubRawMaterial['SRM Description']; ?></td>
                                        <td><?php echo $subSubRawMaterial['Component Quantity']; ?></td>
                                        <td><?php echo $subSubRawMaterial['uom']; ?></td>
                    </tr>
                                        <?php if (isset($subSubRawMaterial['Sub-Sub Raw Materials'])) : ?>
                                        <?php foreach ($subSubRawMaterial['Sub-Sub Raw Materials'] as $subSubSubRawMaterial) : ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo $subSubSubRawMaterial['Sub Raw Material']; ?></td>
                                    <td><?php echo $subSubSubRawMaterial['SRM Description']; ?></td>
                                    <td><?php echo $subSubSubRawMaterial['Component Quantity']; ?></td>
                                    <td><?php echo $subSubSubRawMaterial['uom']; ?></td>
                                </tr>
                                                    <?php if (isset($subSubSubRawMaterial['Sub-Sub-Sub Raw Materials'])) : ?>
                                                    <?php foreach ($subSubSubRawMaterial['Sub-Sub-Sub Raw Materials'] as $subSubSubSubRawMaterial) : ?>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><?php echo $subSubSubSubRawMaterial['Sub Raw Material']; ?></td>
                                            <td><?php echo $subSubSubSubRawMaterial['SRM Description']; ?></td>
                                            <td><?php echo $subSubSubSubRawMaterial['Component Quantity']; ?></td>
                                            <td><?php echo $subSubSubSubRawMaterial['uom']; ?></td>
                                        </tr>
                                                       <?php if (isset($subSubSubSubRawMaterial['Sub-Sub-Sub-Sub Raw Materials'])) : ?>
                                                       <?php foreach ($subSubSubSubRawMaterial['Sub-Sub-Sub-Sub Raw Materials'] as $subSubSubSubSubRawMaterial) : ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><?php echo $subSubSubSubSubRawMaterial['Sub Raw Material']; ?></td>
                                                    <td><?php echo $subSubSubSubSubRawMaterial['SRM Description']; ?></td>
                                                    <td><?php echo $subSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                    <td><?php echo $subSubSubSubSubRawMaterial['uom']; ?></td>
                                                </tr>
                                                       <?php if (isset($subSubSubSubSubRawMaterial['Sub-Sub-Sub-Sub-Sub Raw Materials'])) : ?>
                                                       <?php foreach ($subSubSubSubSubRawMaterial['Sub-Sub-Sub-Sub-Sub Raw Materials'] as $subSubSubSubSubSubRawMaterial) : ?>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><?php echo $subSubSubSubSubSubRawMaterial['Sub Raw Material']; ?></td>
                                                            <td><?php echo $subSubSubSubSubSubRawMaterial['SRM Description']; ?></td>
                                                            <td><?php echo $subSubSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                            <td><?php echo $subSubSubSubSubSubRawMaterial['uom']; ?></td>
                                                        </tr>
                                                                <?php if (isset($subSubSubSubSubSubRawMaterial['Sub-Sub-Sub-Sub-Sub-Sub Raw Materials'])) : ?>
                                                                    <?php foreach ($subSubSubSubSubRawMaterial['Sub-Sub-Sub-Sub-Sub-Sub Raw Materials'] as $subSubSubSubSubSubSubRawMaterial) : ?>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td><?php echo $subSubSubSubSubSubSubRawMaterial['Sub Raw Material']; ?></td>
                                                                            <td><?php echo $subSubSubSubSubSubSubRawMaterial['SRM Description']; ?></td>
                                                                            <td><?php echo $subSubSubSubSubSubSubRawMaterial['Component Quantity']; ?></td>
                                                                            <td><?php echo $subSubSubSubSubSubSubRawMaterial['uom']; ?></td>
                                                                        </tr>

                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                         <?php endforeach; ?>
                                                         <?php endif; ?>
                                                                        <?php endforeach; ?>

                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                    <?php endif; ?>
                                                <?php endforeach; ?>

                                        <?php endif; ?>
                                    <?php endforeach; ?>

                            <?php endif; ?>
                        <?php endforeach; ?>

                <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
