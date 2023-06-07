<?php
// Fetch the JSON data from the URL
$jsonData = file_get_contents('https://reports.moko.co.ke/production/api/conversion');

// Decode the JSON data
$data = json_decode($jsonData, true);

?>

<!-- HTML table to display the data -->
<table>
    <tr>
        <th>Block Type</th>
        <th>Count</th>
        <th>Parent SKU</th>
        <th>SKU Weight</th>
        <th>Block Type Weight</th>
        <th>Distribution</th>
    </tr>

    <?php foreach ($data as $item): ?>
        <tr>
            <td><?php echo $item['Block Type']; ?></td>
            <td><?php echo $item['Count']; ?></td>
            <td><?php echo $item['Parent SKU']; ?></td>
            <td><?php echo $item['SKU Weight']; ?></td>
            <td><?php echo $item['Block Type Weight']; ?></td>
            <td><?php echo $item['Distribution']; ?></td>
        </tr>
    <?php endforeach; ?>

</table>
