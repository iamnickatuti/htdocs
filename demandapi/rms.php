<?php
// Assuming you have a database connection established
include '../cradle_config.php';

function replaceRawMaterial($data, $bomDistributionId, $conn)
{
    $updatedData = $data;

    foreach ($updatedData as &$row) {
        if (strpos($row['Raw Material'], 'WP') === 0) {
            $rawMaterial = $row['Raw Material'];

            $query = "SELECT skus.name AS 'Raw Material',
                             skus.description AS 'RM Description',
                             bom_details.quantity AS 'Component Quantity',
                             units.name AS 'uom',
                             bom_distribution_entries.share AS '%_BOM_Share'
                      FROM bom_details
                      LEFT JOIN boms ON boms.id = bom_details.bom_id
                      LEFT JOIN skus ON skus.id = bom_details.sku_id
                      LEFT JOIN units ON units.id = skus.unit_id
                      LEFT JOIN bom_distribution_entries ON bom_distribution_entries.bom_id = bom_details.bom_id
                      WHERE bom_details.bom_id = (SELECT bom_id
                                                 FROM bom_distribution_entries
                                                 WHERE bom_distribution_id = ?)
                        AND skus.description LIKE CONCAT('SRM%', SUBSTRING(?, 3))
                      GROUP BY bom_details.bom_id, skus.name, skus.description, bom_details.quantity, units.name, bom_details.status, bom_distribution_entries.bom_distribution_id, bom_distribution_entries.share";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'is', $bomDistributionId, $rawMaterial);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $row['Component Quantity'] *= $updatedData[0]['Component Quantity'];
                    $row['%_BOM_Share'] *= $updatedData[0]['%_BOM_Share'];
                    $updatedData[] = $row;
                }
            } else {
                // Display the MySQL error message
                echo 'MySQL Error: ' . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        }
    }

    return $updatedData;
}

// Execute Statement 1
$query1 = "SELECT production_lines.name AS 'Production_Line',
                  skus1.name AS 'Product',
                  skus1.description AS 'Product Description',
                  skus.name AS 'Raw Material',
                  skus.description AS 'RM Description',
                  bom_details.quantity AS 'Component Quantity',
                  units.name AS 'uom',
                  bom_distribution_entries.share AS '%_BOM_Share',
                  bom_distribution_entries.bom_distribution_id
          FROM bom_details
          LEFT JOIN boms ON boms.id = bom_details.bom_id
          LEFT JOIN production_lines ON production_lines.id = boms.production_line_id
          LEFT JOIN skus AS skus1 ON skus1.id = boms.sku_id
          LEFT JOIN skus ON skus.id = bom_details.sku_id
          LEFT JOIN units ON units.id = skus.unit_id
          LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
          LEFT JOIN bom_distribution_entries ON bom_distribution_entries.bom_id = bom_details.bom_id
          GROUP BY bom_details.bom_id, boms.name, production_lines.name, skus1.name, skus1.description, skus.sku_type_id, skus.name, skus.description, bom_details.quantity, units.name, bom_details.status, bom_distribution_entries.bom_distribution_id, bom_distribution_entries.share
          HAVING bom_distribution_entries.bom_distribution_id = (
            SELECT MAX(bom_distribution_entries.bom_distribution_id)
            FROM bom_distribution_entries
            WHERE skus1.description LIKE 'FP%'
          )";

$result1 = mysqli_query($conn, $query1);

if ($result1) {
    // Fetch results from Statement 1
    $data1 = [];
    while ($row = mysqli_fetch_assoc($result1)) {
        $data1[] = $row;
    }

    // Process the data to recursively replace Raw Materials with Sub Raw Materials
    $processedData = $data1;
    $bomDistributionId = $data1[0]['bom_distribution_id'];

    while (count($processedData) > 0) {
        $processedData = replaceRawMaterial($processedData, $bomDistributionId, $conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Display the MySQL error message
    echo 'MySQL Error: ' . mysqli_error($conn);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Result Table</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <th>Production Line</th>
        <th>Product</th>
        <th>Product Description</th>
        <th>Raw Material</th>
        <th>RM Description</th>
        <th>Component Quantity</th>
        <th>uom</th>
        <th>%_BOM_Share</th>
    </tr>
    <?php foreach ($processedData as $row): ?>
        <tr>
            <td><?php echo $row['Production_Line']; ?></td>
            <td><?php echo $row['Product']; ?></td>
            <td><?php echo $row['Product Description']; ?></td>
            <td><?php echo $row['Raw Material']; ?></td>
            <td><?php echo $row['RM Description']; ?></td>
            <td><?php echo $row['Component Quantity']; ?></td>
            <td><?php echo $row['uom']; ?></td>
            <td><?php echo $row['%_BOM_Share']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
