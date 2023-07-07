<?php
$jsonData= file_get_contents('https://reports.moko.co.ke/test.php');

// Convert JSON to associative array
$data = json_decode($jsonData, true);?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Table</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #dddddd;
        }

        .indent {
            padding-left: 20px;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Raw Material</th>
        <th>Description</th>
        <th>Component Quantity</th>
        <th>UOM</th>
        <th>July/2023</th>
        <th>August/2023</th>
        <th>September/2023</th>
        <th>October/2023</th>
        <th>November/2023</th>
        <th>December/2023</th>
        <th>January/2024</th>
        <th>February/2024</th>
        <th>March/2024</th>
        <th>April/2024</th>
        <th>May/2024</th>
        <th>June/2024</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item): ?>
        <?php foreach ($item['Raw Materials'] as $rawMaterial): ?>
            <?php if (isset($rawMaterial['Sub Raw Materials']) && substr($rawMaterial['Raw Material'], 0, 2) === 'wp'): ?>
                <?php foreach ($rawMaterial['Sub Raw Materials'] as $subRawMaterial): ?>
                    <tr>
                        <td><?php echo $subRawMaterial['Raw Material']; ?></td>
                        <td><?php echo $subRawMaterial['RM Description']; ?></td>
                        <td><?php echo $subRawMaterial['Component Quantity']; ?></td>
                        <td><?php echo $subRawMaterial['uom']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['July/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['August/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['September/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['October/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['November/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['December/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['January/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['February/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['March/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['April/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['May/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['June/2024']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td><?php echo $rawMaterial['Raw Material']; ?></td>
                    <td><?php echo $rawMaterial['RM Description']; ?></td>
                    <td><?php echo $rawMaterial['Component Quantity']; ?></td>
                    <td><?php echo $rawMaterial['uom']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['July/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['August/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['September/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['October/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['November/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['December/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['January/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['February/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['March/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['April/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['May/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['June/2024']; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>


</body>
</html>
