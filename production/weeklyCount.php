<?php
session_start ();
include '../parts/header.php';
?>
    <body style="color: black;">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <div class="main-content">
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="navbar-brand-box d-flex align-items-left">
                        <a href="#" class="logo">
                            <img class="align-middle" src="../assets/logo.svg" height="50px">
                        </a>
                        <button type="button" class="btn btn-sm mr-2 font-size-16 d-lg-none header-item waves-effect waves-light" data-toggle="collapse" data-target="#topnav-menu-content">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn header-item waves-effect waves-light"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="../assets/images/users/pp.png"
                                     alt="Header Avatar">
                                <span class="d-none d-sm-inline-block ml-1"><?php if($_SESSION["name"]) { echo $_SESSION["name"];} ?></span>
                                <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="../auth/logout.php">
                                    <span>Log Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <?php include '../parts/nav.php';?>

            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 font-size-18">
                                    Material Flow 2023
                                </h4>
                                <div class="page-title-right">
                                    <span>Price equivalent coming soon</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row-->
                    <div class="row">
                        <div class="col-9">
                            <div class="card">
                                <?php
                                $json_data = file_get_contents('https://reports.moko.co.ke/production/api/weeklyCount.php');
                                $data = json_decode($json_data, true);

                                // Get unique "Tag" and "issuance_team_id" values
                                $tags = array_unique(array_column($data, 'Tag'));
                                $issuanceTeams = array_unique(array_column($data, 'issuance_team_id'));

                                // Filter the data based on the selected "Tag" and "issuance_team_id"
                                $selectedTag = isset($_GET['tag']) ? $_GET['tag'] : null;
                                $selectedTeam = isset($_GET['team']) ? $_GET['team'] : null;

                                // Prepare the filtered data for JavaScript usage
                                $filteredData = array_filter($data, function ($row) use ($selectedTag, $selectedTeam) {
                                    return ($selectedTag === null || $row['Tag'] === $selectedTag)
                                        && ($selectedTeam === null || $row['issuance_team_id'] === $selectedTeam);
                                });
                                $filteredDataJSON = json_encode($filteredData);
                                ?>

                                <div class="card-body">
                                    <div class="mb-4">
                                        <div class="float-right mr-4 ml-2" >
                                            <button onclick="exportTableToExcel()" class="btn btn-warning">Export to Excel</button>
                                        </div>
                                        <div class="float-right mr-4 ml-4" >
                                            <select name="team" id="teamSelect" class="form-control form-control-md">
                                                <option value="">All Teams</option>
                                                <?php foreach ($issuanceTeams as $team) { ?>
                                                    <option value="<?php echo $team; ?>" <?php echo ($team === $selectedTeam) ? 'selected' : ''; ?>>
                                                        <?php echo $team; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="float-right">
                                            <select name="tag" id="tagSelect" class="form-control form-control-md">
                                                <option value="">All Tags</option>
                                                <?php foreach ($tags as $tag) { ?>
                                                    <option value="<?php echo $tag; ?>" <?php echo ($tag === $selectedTag) ? 'selected' : ''; ?>>
                                                        <?php echo $tag; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <h5 class="card-title mb-0">Weekly Count</h5>
                                        <br>
                                        <p class="card-subtitle mb-4">Choose from the drop-downs to display data</p>
                                    </div>
                                    <table id="resultTable" class="table table-centered table-striped mb-0" style="font-size: 11px;">
                                        <thead>
                                        <tr>
                                            <th>Tag</th>
                                            <th>Location</th>
                                            <!--                                        <th>issuance_team_id</th>-->
                                            <th>SKU Description</th>
                                            <th>Part Number</th>
                                            <th>Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                        <!-- Add the export button -->

                                        <script>
                                            var filteredData = <?php echo $filteredDataJSON; ?>;

                                            function updateTable() {
                                                var tagSelect = document.getElementById('tagSelect');
                                                var teamSelect = document.getElementById('teamSelect');
                                                var selectedTag = tagSelect.value;
                                                var selectedTeam = teamSelect.value;

                                                var filteredRows = filteredData.filter(function(row) {
                                                    return (selectedTag === '' || row.Tag === selectedTag) && (selectedTeam === '' || row.issuance_team_id === selectedTeam);
                                                });

                                                var tableBody = document.getElementById('tableBody');
                                                tableBody.innerHTML = '';

                                                var totalQty = 0; // Variable to store the total quantity

                                                filteredRows.forEach(function(row) {
                                                    var tr = document.createElement('tr');

                                                    var tdTag = document.createElement('td');
                                                    tdTag.textContent = row.Tag;

                                                    var tdLocation = document.createElement('td');
                                                    tdLocation.textContent = row.Location;

                                                    var tdTeam = document.createElement('td');
                                                    tdTeam.textContent = row.issuance_team_id;

                                                    var tdPartNumber = document.createElement('td');
                                                    tdPartNumber.textContent = row['Part Number'];

                                                    var tdQty = document.createElement('td');
                                                    tdQty.textContent = row.Qty;

                                                    var tdSkuDescription = document.createElement('td');
                                                    tdSkuDescription.textContent = row['SKU Description'];

                                                    tr.appendChild(tdTag);
                                                    tr.appendChild(tdLocation);
                                                    tr.appendChild(tdPartNumber);
                                                    tr.appendChild(tdSkuDescription);
                                                    tr.appendChild(tdQty);
                                                    tableBody.appendChild(tr);

                                                    totalQty += parseInt(row.Qty); // Accumulate the quantity
                                                });

                                                // Display the total quantity
                                                var totalQtyElement = document.getElementById('totalQty');
                                                totalQtyElement.textContent = totalQty;
                                            }


                                            // Update the table when the dropdowns are changed
                                            document.getElementById('tagSelect').addEventListener('change', updateTable);
                                            document.getElementById('teamSelect').addEventListener('change', updateTable);

                                            // Initial table population
                                            updateTable();
                                        </script>
                                        </tbody>
                                    </table>
                                    <script src="https://unpkg.com/tableexport@5.2.0/dist/js/tableexport.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
                                </div>

                                <script>
                                    function exportTableToExcel() {
                                        var table = document.getElementById('resultTable');
                                        var rows = table.getElementsByTagName('tr');
                                        var rowData = [];

                                        for (var i = 0; i < rows.length; i++) {
                                            var row = [], cols = rows[i].querySelectorAll('td, th');

                                            for (var j = 0; j < cols.length; j++)
                                                row.push(cols[j].innerText);

                                            rowData.push(row.join('\t'));
                                        }

                                        var excelData = rowData.join('\n');
                                        var blob = new Blob([excelData], { type: 'application/vnd.ms-excel' });

                                        var link = document.createElement('a');
                                        link.href = URL.createObjectURL(blob);
                                        link.download = 'Daily.xls';
                                        link.click();
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-4">
                                        <!--                                    <span class="badge badge-soft-primary float-right"></span>-->
                                        <h5 class="card-title mb-0">Total</h5>
                                    </div>
                                    <div class="row d-flex align-items-center mb-4">
                                        <div class="col-7">
                                            <h2 id="totalQty" class="d-flex align-items-center mb-0"></h2>
                                        </div>
                                    </div>
                                </div>
                                <!--end card body-->
                            </div><!-- end card-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
<?php include '../parts/footer.php';?>




<?php
/** @noinspection ALL */
include '../../cradle_config.php';
global $conn;
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
// The SQL query
$query = "SELECT
  bom_details.bom_id,
  boms.name AS 'BOM_Name',
  production_lines.name AS 'Production_Line',
  skus1.name AS 'Target_sku_Part_Number',
  skus1.description AS 'Target_sku_Part_Description',
  skus.sku_type_id,
  skus.name AS 'Component_Part_Number',
  skus.description AS 'Component_Part_Description',
  bom_details.quantity AS 'Component_Quantity',
  units.name AS 'Component_Unit_of_Measure',
  bom_details.status,
  bom_distribution_entries.bom_distribution_id AS 'bom_distribution_id',
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
  skus1.description LIKE 'FP%'
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
  bom_distribution_entries.share
HAVING
  bom_distribution_id = (
    SELECT
      MAX(bom_distribution_entries.bom_distribution_id)
    FROM
      bom_distribution_entries
    WHERE
      skus1.description LIKE 'FP%'
  );";

// Execute the query
$result = mysqli_query($conn, $query);

// Store the results in an associative array
$products = array();

// Loop through each row of the query result
while ($row = mysqli_fetch_assoc($result)) {
    // Create an associative array for each row
    $resultRow = array(
        'bom_id' => $row['bom_id'],
        'BOM_Name' => $row['BOM_Name'],
        'Production_Line' => $row['Production_Line'],
        'Target_sku_Part_Number' => $row['Target_sku_Part_Number'],
        'Target_sku_Part_Description' => $row['Target_sku_Part_Description'],
        'sku_type_id' => $row['sku_type_id'],
        'Component_Part_Number' => $row['Component_Part_Number'],
        'Component_Part_Description' => $row['Component_Part_Description'],
        'Component_Quantity' => $row['Component_Quantity'],
        'Component_Unit_of_Measure' => $row['Component_Unit_of_Measure'],
        'status' => $row['status'],
        'bom_distribution_id' => $row['bom_distribution_id'],
        '%_BOM_Share' => $row['%_BOM_Share']
    );

    // Add the row to the results array
    $products[] = $resultRow;
}

foreach ($data as $item) {
    $productKey = $item['Target_sku_Part_Number'];

    // Check if the product exists in the products array
    $existingProduct = array_filter($products, function ($product) use ($productKey) {
        return $product['Product'] === $productKey;
    });

    // If the product exists, add the component to its Components array
    if (!empty($existingProduct)) {
        $existingProductKey = array_keys($existingProduct)[0];
        $products[$existingProductKey]['Components'][] = [
            'Component_Part_Number' => $item['Component_Part_Number'],
            'Component_Part_Description' => $item['Component_Part_Description'],
            'Component_Quantity' => $item['Component_Quantity'],
            'Component_Unit_of_Measure' => $item['Component_Unit_of_Measure'],
            'Status' => $item['status'],
            'BOM_Distribution_ID' => $item['bom_distribution_id'],
            '%_BOM_Share' => $item['%_BOM_Share']
        ];
    } else {
        // If the product doesn't exist, create a new product object and add it to the products array
        $products[] = [
            'Product' => $item['Target_sku_Part_Number'],
            'Product_Description' => $item['Target_sku_Part_Description'],
            'Components' => [
                [
                    'Component_Part_Number' => $item['Component_Part_Number'],
                    'Component_Part_Description' => $item['Component_Part_Description'],
                    'Component_Quantity' => $item['Component_Quantity'],
                    'Component_Unit_of_Measure' => $item['Component_Unit_of_Measure'],
                    'Status' => $item['status'],
                    'BOM_Distribution_ID' => $item['bom_distribution_id'],
                    '%_BOM_Share' => $item['%_BOM_Share']
                ]
            ]
        ];
    }
}

$result = [
    'products' => $products
];

$output = json_encode($result, JSON_PRETTY_PRINT);

echo $output;

