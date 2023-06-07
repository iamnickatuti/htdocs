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
                            $json_data = file_get_contents('https://reports.moko.co.ke/production/api/dailyCount.php');
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
                                        <button onclick="exportTableToExcel()">Export to Excel</button>
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

                                    <h5 class="card-title mb-0">Daily Count</h5>
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


                            <script src="https://unpkg.com/tableexport@5.2.0/dist/js/tableexport.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

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