<html>
<head>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>

<?php
$url1 = 'https://reports.moko.co.ke/production/api/qtest1.php';

$json = file_get_contents($url1);

$data = json_decode($json, true);

$keys = array_keys($data[0]);
$startIndex = 7;

echo '<table id="myTable">
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
        <tbody>';

foreach ($data as $record) {
    $partNumber = isset($record["Part Number"]) ? $record["Part Number"] : "";
    $cutSKUQuantity = isset($record["Cut SKU Quantity"]) ? $record["Cut SKU Quantity"] : "";
    $TotalVolume = isset($record["Volume"]) ? $record["Volume"] : 0;
    $Category = isset($record["BOM Category"]) ? $record["BOM Category"] : 0;

    for ($i = $startIndex; $i < count($keys); $i++) {
        $key = $keys[$i];
        $value = $record[$key];

        echo '<tr>
                <td>' . $Category. '</td>
                <td>' . $partNumber . '</td>
                <td>' . $key . '</td>
                <td>' . $cutSKUQuantity . '</td>
                <td>' . $value/$cutSKUQuantity . '</td>
                <td>' . number_format($TotalVolume,4). '</td>
                <td>' . $value. '</td>
              </tr>';
    }
}

echo '</tbody>
      </table>';
?>


<button onclick="exportToExcel()">Export to Excel</button>

<script>
  function exportToExcel() {
      // Get the HTML table element
      var table = document.getElementById("myTable");

      // Create a new Workbook
      var wb = XLSX.utils.table_to_book(table);

      // Generate the Excel file
      var wbout = XLSX.write(wb, { bookType: "xlsx", type: "binary" });

    // Convert the Excel file to a Blob
    function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xff;
      return buf;
    }

    var blob = new Blob([s2ab(wbout)], { type: "application/octet-stream" });

    // Create a download link and trigger the download
    var link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "table.xlsx";
    link.click();
  }
</script>

</html>