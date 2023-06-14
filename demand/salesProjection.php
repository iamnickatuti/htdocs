<?php

// JSON URL
$jsonUrl = "https://reports.moko.co.ke/demand/api/finishedProducts.php";

// Get the JSON data from the URL
$jsonData = file_get_contents($jsonUrl);

// Decode the JSON data into an associative array
$data = json_decode($jsonData, true);

?>

<!DOCTYPE html>
<html>
<head>
    <title>JSON to HTML Table</title>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <?php foreach (array_keys($data[0]) as $header) { ?>
            <th><?php echo $header; ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $item) { ?>
        <tr>
            <?php foreach ($item as $value) { ?>
                <td><?php echo $value; ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
