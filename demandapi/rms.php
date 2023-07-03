<?php

include '../cradle_config.php';

// Recursive function to substitute raw materials starting with WP
function substituteRawMaterial($conn, $rawMaterial)
{
    // Execute the SQL query (Statement 2) for Sub Raw Materials
    $sqlStatement2 = "SELECT
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

    $resultStatement2 = $conn->query($sqlStatement2);

    // Check if Statement 2 query was successful
    if ($resultStatement2) {
        // Fetch the results
        $subRawMaterials = array();
        while ($subRow = $resultStatement2->fetch_assoc()) {
            $subRawMaterial = $subRow;

            // Check if Sub Raw Material starts with WP
            if (strpos($subRow['Sub Raw Material'], 'WP') === 0) {
                // Call the function recursively to substitute the Sub Raw Material
                $subSubRawMaterials = substituteRawMaterial($conn, $subRow['Sub Raw Material']);

                // Add the sub-sub raw materials to the sub raw material
                $subRawMaterial['Sub-Sub Raw Materials'] = $subSubRawMaterials;
            }

            $subRawMaterials[] = $subRawMaterial;
        }

        // Return the sub raw materials array
        return $subRawMaterials;
    } else {
        echo "Error executing Statement 2: " . $conn->error;
    }
}

// Execute the SQL query (Statement 1) for Products and Raw Materials
$sqlStatement1 = "SELECT
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

$resultStatement1 = $conn->query($sqlStatement1);

// Check if Statement 1 query was successful
if ($resultStatement1) {
    // Fetch the results
    $products = array();
    while ($row = $resultStatement1->fetch_assoc()) {
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
    echo "Error executing Statement 1: " . $conn->error;
}

// Close the database connection
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Raw Materials</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Product Raw Materials</h1>
<table>
    <tr>
        <th>Product</th>
        <th>Product Description</th>
        <th>Raw Material</th>
        <th>RM Description</th>
        <th>Component Quantity</th>
        <th>Unit of Measure</th>
        <th>% BOM Share</th>
        <th>Sub Raw Materials</th>
    </tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['Product']; ?></td>
            <td><?php echo $product['Product Description']; ?></td>
            <td><?php echo $product['Raw Material']; ?></td>
            <td><?php echo $product['RM Description']; ?></td>
            <td><?php echo $product['Component Quantity']; ?></td>
            <td><?php echo $product['uom']; ?></td>
            <td><?php echo $product['%_BOM_Share']; ?></td>
            <td>
                <?php if (isset($product['Sub Raw Materials'])): ?>
                    <table>
                        <tr>
                            <th>Sub Raw Material</th>
                            <th>SRM Description</th>
                            <th>Component Quantity</th>
                            <th>Unit of Measure</th>
                            <th>% BOM Share</th>
                            <th>Sub-Sub Raw Materials</th>
                        </tr>
                        <?php foreach ($product['Sub Raw Materials'] as $subRawMaterial): ?>
                            <tr>
                                <td><?php echo $subRawMaterial['Sub Raw Material']; ?></td>
                                <td><?php echo $subRawMaterial['SRM Description']; ?></td>
                                <td><?php echo $subRawMaterial['Component Quantity']; ?></td>
                                <td><?php echo $subRawMaterial['uom']; ?></td>
                                <td><?php echo $subRawMaterial['%_BOM_Share']; ?></td>
                                <td>
                                    <?php if (isset($subRawMaterial['Sub-Sub Raw Materials'])): ?>
                                        <table>
                                            <tr>
                                                <th>Sub-Sub Raw Material</th>
                                                <th>SSRM Description</th>
                                                <th>Component Quantity</th>
                                                <th>Unit of Measure</th>
                                                <th>% BOM Share</th>
                                            </tr>
                                            <?php foreach ($subRawMaterial['Sub-Sub Raw Materials'] as $subSubRawMaterial): ?>
                                                <tr>
                                                    <td><?php echo $subSubRawMaterial['Sub Raw Material']; ?></td>
                                                    <td><?php echo $subSubRawMaterial['SRM Description']; ?></td>
                                                    <td><?php echo $subSubRawMaterial['Component Quantity']; ?></td>
                                                    <td><?php echo $subSubRawMaterial['uom']; ?></td>
                                                    <td><?php echo $subSubRawMaterial['%_BOM_Share']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
