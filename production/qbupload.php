<!DOCTYPE html>
<html>
<head>
    <title>Data Table</title>
</head>
<body>
<?php
// Check if the start and end dates are provided
$start = isset($_GET["start"]) ? $_GET["start"] : "";
$end = isset($_GET["end"]) ? $_GET["end"] : "";

$url1 = 'https://reports.moko.co.ke/production/api/qtest1.php';
$json = file_get_contents($url1);
$data = json_decode($json, true);

$keys = array_keys($data[0]);
$startIndex = 8;

$filteredData = array_filter($data, function($record) use ($start, $end) {
    $cuttingDate = isset($record["Cutting Date"]) ? $record["Cutting Date"] : "";
    return $cuttingDate >= $start && $cuttingDate <= $end;
});

?>

<div>
    <form method="GET">
        <label for="start-date">Start Date:</label>
        <input type="date" id="start-date" name="start" value="<?php echo $start; ?>">
        <label for="end-date">End Date:</label>
        <input type="date" id="end-date" name="end" value="<?php echo $end; ?>">
        <button type="submit">Filter</button>
    </form>
</div>

<div class="table-responsive">
    <table id="myTable" class="table activate-select dt-responsive nowrap" style="font-size: 11px;">
        <thead>
        <tr>
            <th>Cushion</th>
            <th>Part Number</th>
            <th>Raw Material</th>
            <th>Qty</th>
            <th>Quantity Cut</th>
            <th>Cumulative Volume</th>
            <th>Total Consumption</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($filteredData as $record) {
            $cuttingDate = isset($record["Cutting Date"]) ? $record["Cutting Date"] : "";
            $partNumber = isset($record["Part Number"]) ? $record["Part Number"] : "";
            $cutSKUQuantity = isset($record["Cut SKU Quantity"]) ? $record["Cut SKU Quantity"] : "";
            $totalVolume = isset($record["Volume"]) ? $record["Volume"] : 0;
            $category = isset($record["BOM Category"]) ? $record["BOM Category"] : 0;

            for ($i = $startIndex; $i < count($keys); $i++) {
                $key = $keys[$i];
                $value = $record[$key];

                echo '<tr>
                                <td>' . $category . '</td>
                                <td>' . $partNumber . '</td>
                                <td>' . $key . '</td>
                                <td>' . ($value / $cutSKUQuantity) . '</td>
                                <td>' . $cutSKUQuantity . '</td>
                                <td>' . number_format($totalVolume, 4) . '</td>
                                <td>' . $value . '</td>
                            </tr>';
            }
        }
        ?>
        </tbody>
    </table>
</div>

