<!DOCTYPE html>
<html>
<head>
    <title>Filter Array by Date</title>
    <script src="script.js"></script>
</head>
<body>
<h1>Filter Array by Date</h1>

<form method="POST" action="">
    <label for="datepicker">Select a Date:</label>
    <input type="date" id="datepicker" name="selectedDate">
    <button type="submit">Filter</button>
</form>

<table id="resultTable">
    <thead>
    <tr>
        <th>Tag</th>
        <th>Location</th>
        <th>issuance_team_id</th>
        <th>Part Number</th>
        <th>Qty</th>
        <th>SKU Description</th>
    </tr>
    </thead>
    <tbody>

    <?php
    // Example array of objects
    $json_data = file_get_contents('https://reports.moko.co.ke/production/api/dailyCount.php');
    $data = json_decode($json_data, true);

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get selected date from the form
        $selectedDate = $_POST['selectedDate'];

        // Filter the array based on the selected date
        $filteredData = array_filter($data, function ($item) use ($selectedDate) {
            return strpos($item['Tag'], $selectedDate) !== false;
        });

        // Display filtered data in the table
        foreach ($filteredData as $row) {
            echo "<tr>";
            echo "<td>" . $row['Tag'] . "</td>";
            echo "<td>" . $row['Location'] . "</td>";
            echo "<td>" . $row['issuance_team_id'] . "</td>";
            echo "<td>" . $row['Part Number'] . "</td>";
            echo "<td>" . $row['Qty'] . "</td>";
            echo "<td>" . $row['SKU Description'] . "</td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>
</body>
</html>
