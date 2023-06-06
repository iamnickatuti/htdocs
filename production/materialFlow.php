<?php
session_start ();
include '../parts/header.php';
include 'sql/sqlOpening.php';
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
<!--                                <form action="prodCountFilter.php" method="post">-->
<!--                                    <div class="row">-->
<!--                                        <div class="col-9">-->
<!--                                            <select class="form-control mb-3" name = "dur">-->
<!--                                                <option >Choose Month</option>-->
<!--                                                <option value="2023 Jan%">January 2023</option>-->
<!--                                                <option value="2023 Feb%">February 2023</option>-->
<!--                                                <option value="2023 Mar%">March 2023</option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                        <div class="col-3">-->
<!--                                            <input type="submit" class="btn btn-warning" value="Get" name="search">-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </form>-->
                                <span>Filter <b>Month</b> coming soon</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row-->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">   <?php
                                    $today = date('Y-m-d');
                                    $date = date_create_from_format('Y-m-d', $today);
                                    $formatted_date = date_format($date, 'Y M');
                                    $thisMonth = $formatted_date." Stocktake";

                                    echo $thisMonth;
                                    ?></h4>
                                <p class="card-subtitle mb-4">
                                    Monthly.
                                </p>
                                <table class="table table-centered table-striped mb-0" style="font-size: 11px">
                                    <thead>
                                    <tr>
                                        <th style="width: 130px;">Part Number</th>
                                        <th style="width: 300px; font-size: 11px;">Name</th>
                                        <th style="font-size: 11px;">Opening Balance (Kgs)</th>
                                        <th style="font-size: 11px;">Cage Receipts</th>
                                        <th style="font-size: 11px;">Yard Receipts</th>
                                        <th style="font-size: 11px;">Total Available for Rebonding (Kgs)</th>
                                        <th style="font-size: 11px;">Consumption (Kgs)</th>
                                        <th style="font-size: 11px;">Expected Closing Balance (Kgs)</th>
                                        <th style="font-size: 11px;">Actual Closing Balance (Kgs)</th>
                                        <th style="font-size: 11px;">Variance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $json_data = file_get_contents('https://reports.moko.co.ke/production/api/materialFlow.php');
                                    $data = json_decode($json_data, true);

                                    // Get unique "duration" values
                                    $durations = array_unique(array_column($data, 'Duration'));

                                    // Filter the data based on the selected "duration" value
                                    $selectedDuration = isset($_GET['duration']) ? $_GET['duration'] : null;

                                    // Prepare the filtered data for JavaScript usage
                                    $filteredData = array_filter($data, function ($row) use ($selectedDuration) {
                                        return $selectedDuration === null || $row['Duration'] === $selectedDuration;
                                    });
                                    $filteredDataJSON = json_encode($filteredData);
                                    ?>

                                    <!-- Display the dropdown to select the "duration" -->
                                    <select name="duration" id="durationSelect">
                                        <option value="">All Durations</option>
                                        <?php foreach ($durations as $duration) { ?>
                                            <option value="<?php echo $duration; ?>" <?php echo ($duration === $selectedDuration) ? 'selected' : ''; ?>>
                                                <?php echo $duration; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <!-- Display the filtered data in the table -->
                                    <script>
                                        var filteredData = <?php echo $filteredDataJSON; ?>;

                                        function updateTable() {
                                            var durationSelect = document.getElementById('durationSelect');
                                            var selectedDuration = durationSelect.value;
                                            var filteredRows = '';

                                            if (selectedDuration === '') {
                                                filteredRows = filteredData;
                                            } else {
                                                filteredRows = filteredData.filter(function(row) {
                                                    return row.Duration === selectedDuration;
                                                });
                                            }

                                            var tableBody = document.getElementById('tableBody');
                                            tableBody.innerHTML = '';

                                            var totalQuantity = 0;
                                            var cageReceipts = 0;
                                            var totalRebonding = 0;
                                            var bomConsumption = 0;
                                            var closingBalance = 0;
                                            var openingBalance = 0;
                                            var actualBalance = 0;
                                            var variance = 0;

                                            filteredRows.forEach(function(row) {
                                                var tr = document.createElement('tr');

                                                var tdPartNumber = document.createElement('td');
                                                tdPartNumber.textContent = row['Part Number'];

                                                var tdName = document.createElement('td');
                                                tdName.textContent = row['Part Description'];

                                                var tdcReceipts = document.createElement('td');
                                                tdcReceipts.textContent = row['Cages'];

                                                var tdTotalRebonding = document.createElement('td');
                                                tdTotalRebonding.textContent = row.totalRebonding;

                                                var tdConsumption = document.createElement('td');
                                                tdConsumption.textContent = row.bomConsumption;

                                                var tdClosingBalance = document.createElement('td');
                                                tdClosingBalance.textContent = row['Closing Balance'];

                                                var tdOpeningBalance = document.createElement('td');
                                                tdOpeningBalance.textContent = row['Opening Balance'];

                                                var tdTotalQuantity = document.createElement('td');
                                                tdTotalQuantity.textContent = row.total_quantity;

                                                var tdActualBalance = document.createElement('td');
                                                tdActualBalance.textContent = row.actualBalance;

                                                var tdVariance = document.createElement('td');
                                                tdVariance.textContent = row.variancee;

                                                tr.appendChild(tdPartNumber);
                                                tr.appendChild(tdName);
                                                tr.appendChild(tdOpeningBalance);
                                                tr.appendChild(tdcReceipts);
                                                tr.appendChild(tdTotalRebonding);
                                                tr.appendChild(tdConsumption);
                                                tr.appendChild(tdClosingBalance);
                                                tr.appendChild(tdTotalQuantity);
                                                tr.appendChild(tdActualBalance);
                                                tr.appendChild(tdVariance);

                                                tableBody.appendChild(tr);

                                                totalQuantity += parseFloat(row.total_quantity);
                                                cageReceipts += parseFloat(row['Cages']);
                                                totalRebonding += parseFloat(row.totalRebonding);
                                                bomConsumption += parseFloat(row.bomConsumption);
                                                closingBalance += parseFloat(row['Closing Balance']);
                                                openingBalance += parseFloat(row['Opening Balance']);
                                                actualBalance += parseFloat(row.actualBalance);
                                                variance += parseFloat(row.variancee);
                                            });

                                            // Add total amount row
                                            var trTotal = document.createElement('tr');
                                            var tdTotalLabel = document.createElement('td');
                                            tdTotalLabel.textContent = 'Total Amount';

                                            var tdTotalcReceipts = document.createElement('td');
                                            tdTotalcReceipts.textContent = cageReceipts.toFixed(2);

                                            var tdTotalRebonding = document.createElement('td');
                                            tdTotalRebonding.textContent = totalRebonding.toFixed(2);

                                            var tdTotalConsumption = document.createElement('td');
                                            tdTotalConsumption.textContent = bomConsumption.toFixed(2);

                                            var tdTotalClosingBalance = document.createElement('td');
                                            tdTotalClosingBalance.textContent = closingBalance.toFixed(2);

                                            var tdTotalOpeningBalance = document.createElement('td');
                                            tdTotalOpeningBalance.textContent = openingBalance.toFixed(2);

                                            var tdTotalQuantity = document.createElement('td');
                                            tdTotalQuantity.textContent = totalQuantity.toFixed(2);

                                            var tdTotalActualBalance = document.createElement('td');
                                            tdTotalActualBalance.textContent = actualBalance.toFixed(2);

                                            var tdTotalVariance = document.createElement('td');
                                            tdTotalVariance.textContent = variance.toFixed(2);

                                            trTotal.appendChild(tdTotalLabel);
                                            trTotal.appendChild(document.createElement('td'));
                                            trTotal.appendChild(tdTotalOpeningBalance);
                                            trTotal.appendChild(tdTotalcReceipts);
                                            trTotal.appendChild(tdTotalRebonding);
                                            trTotal.appendChild(tdTotalConsumption);
                                            trTotal.appendChild(tdTotalClosingBalance);
                                            trTotal.appendChild(tdTotalQuantity);
                                            trTotal.appendChild(tdTotalActualBalance);
                                            trTotal.appendChild(tdTotalVariance);

                                            tableBody.appendChild(trTotal);
                                        }

                                        // Update the table when the duration is changed
                                        document.getElementById('durationSelect').addEventListener('change', updateTable);

                                        // Initial table population
                                        updateTable();

                                    </script>


                                    <tbody id="tableBody"></tbody>
                                    </tbody>
                                </table>


                                <style>
                                    tr th:nth-child(3) {
                                        background-color:#bdffbf;
                                        color: #000;
                                    }
                                    tr td:nth-child(3) {
                                        background-color: #bdffbf;
                                        color: #000;
                                    }
                                    tr th:nth-child(4) {
                                        background-color:#e3e3e3;
                                        color: #000;
                                    }
                                    tr td:nth-child(4) {
                                        background-color: #e3e3e3;
                                        color: #000;
                                    }
                                    tr th:nth-child(7) {
                                        background-color:#ffbdbd;
                                        color: #000;
                                    }
                                    tr td:nth-child(7) {
                                        background-color: #ffbdbd;
                                        color: #000;
                                    }
                                    tr th:nth-child(8) {
                                        background-color:#ffedbd;
                                        color: #000;
                                    }
                                    tr td:nth-child(8) {
                                        background-color: #ffedbd;
                                        color: #000;
                                    }
                                </style>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
                <!-- end row-->
            </div>
        </div>
    </div>
</div>
</body>
<?php include '../parts/footer.php';?>
