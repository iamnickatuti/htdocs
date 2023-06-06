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
                                <span>Price equivalent coming soon</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row-->
                <div class="row">
                    <div class="col-12">
                        <div class="card">


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
                               <div class="card-body">
                                   <div class="mb-4">
                                       <div class="float-right">
                                       <select name="duration" id="durationSelect" class="form-control form-control-md">
                                           <option value="">All Durations</option>
                                           <?php foreach ($durations as $duration) { ?>
                                               <option value="<?php echo $duration; ?>" <?php echo ($duration === $selectedDuration) ? 'selected' : ''; ?>>
                                                   <?php echo $duration; ?>
                                               </option>
                                           <?php } ?>
                                       </select>
                                       </div>

                                       <h5 class="card-title mb-0">Monthly Stocktake</h5>
                                       <br>
                                       <p class="card-subtitle mb-4">Choose from the drop-down to display data</p>

                                   </div>

                                <table id= "myTable" class="table table-centered table-striped mb-0" style="font-size: 11px">
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

                                    <tbody id="tableBody">
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

                                            var totalOpeningBalance = 0;
                                            var totalCages = 0;
                                            var totalYards = 0;
                                            var totalRebonding = 0;
                                            var totalConsumption = 0;
                                            var totalExpectedClosingBalance = 0;
                                            var totalClosingBalance = 0;
                                            var totalVariance = 0;
                                            var totalTotalQuantity = 0;
                                            var totalActualBalance = 0;
                                            var totalTotalRebonding = 0;

                                            filteredRows.forEach(function(row) {
                                                var tr = document.createElement('tr');

                                                var tdPartNumber = document.createElement('td');
                                                tdPartNumber.textContent = row['Part Number'];

                                                var tdName = document.createElement('td');
                                                tdName.textContent = row['Part Description'];

                                                var tdcReceipts = document.createElement('td');
                                                tdcReceipts.textContent = (row['Cages']);
                                                totalCages += (parseFloat(row['Cages']));

                                                var tdyReceipts = document.createElement('td');
                                                tdyReceipts.textContent = row['Yards'];
                                                totalYards += parseFloat(row['Yards']);

                                                var tdTotalRebonding = document.createElement('td');
                                                tdTotalRebonding.textContent = row.totalRebonding;
                                                totalTotalRebonding += parseFloat(row.totalRebonding);

                                                var tdConsumption = document.createElement('td');
                                                tdConsumption.textContent = row['Consumption'].toFixed(2);
                                                totalConsumption += parseFloat(row['Consumption']);

                                                var tdClosingBalance = document.createElement('td');
                                                tdClosingBalance.textContent = row['Closing Balance'];
                                                totalClosingBalance += parseFloat(row['Closing Balance']);

                                                var tdOpeningBalance = document.createElement('td');
                                                tdOpeningBalance.textContent = row['Opening Balance'];
                                                totalOpeningBalance += parseFloat(row['Opening Balance']);

                                                var tdTotalQuantity = document.createElement('td');
                                                tdTotalQuantity.textContent = row.total_quantity;
                                                totalTotalQuantity += parseFloat(row.total_quantity);

                                                var tdActualBalance = document.createElement('td');
                                                tdActualBalance.textContent = row.actualBalance;
                                                totalActualBalance += parseFloat(row.actualBalance);

                                                var tdRebonding = document.createElement('td');
                                                var openingBalance = parseFloat(row['Opening Balance']);
                                                var cageReceipts = parseFloat(row['Cages']);
                                                var yardReceipts = parseFloat(row['Yards']);
                                                var rebonding = openingBalance + cageReceipts + yardReceipts;
                                                tdRebonding.textContent = rebonding.toFixed(2);
                                                totalRebonding += rebonding;

                                                var tdExpectedClosingBalance = document.createElement('td');
                                                var consumption = parseFloat(row['Consumption']);
                                                var expectedClosingBalance = rebonding - consumption;
                                                tdExpectedClosingBalance.textContent = expectedClosingBalance.toFixed(2);
                                                totalExpectedClosingBalance += expectedClosingBalance;

                                                var tdVariance = document.createElement('td');
                                                var closing = parseFloat(row['Closing Balance']);
                                                var variance = expectedClosingBalance - closing;
                                                tdVariance.textContent = variance.toFixed(2);
                                                totalVariance += variance;

                                                tr.appendChild(tdPartNumber);
                                                tr.appendChild(tdName);
                                                tr.appendChild(tdOpeningBalance);
                                                tr.appendChild(tdcReceipts);
                                                tr.appendChild(tdyReceipts);
                                                tr.appendChild(tdRebonding);
                                                tr.appendChild(tdConsumption);
                                                tr.appendChild(tdExpectedClosingBalance);
                                                tr.appendChild(tdClosingBalance);
                                                tr.appendChild(tdVariance);
                                                tr.appendChild(tdTotalQuantity);
                                                tr.appendChild(tdActualBalance);
                                                tr.appendChild(tdTotalRebonding);
                                                tableBody.appendChild(tr);
                                            });

                                            // Create the row for displaying totals
                                            var trTotal = document.createElement('tr');
                                            trTotal.classList.add('total-row');

                                            var tdTotalLabel = document.createElement('td');
                                            tdTotalLabel.textContent = 'Total';
                                            tdTotalLabel.style.fontWeight = 'bold';


                                            var tdTotalOpeningBalance = document.createElement('td');
                                            tdTotalOpeningBalance.textContent = totalOpeningBalance.toFixed(2);
                                            tdTotalOpeningBalance.style.fontWeight = 'bold';


                                            var tdTotalCages = document.createElement('td');
                                            tdTotalCages.textContent = totalCages.toFixed(2);
                                            tdTotalCages.style.fontWeight = 'bold';



                                            var tdTotalYards = document.createElement('td');
                                            tdTotalYards.textContent = totalYards.toFixed(2);
                                            tdTotalYards.style.fontWeight = 'bold';


                                            var tdTotalRebonding = document.createElement('td');
                                            tdTotalRebonding.textContent = totalRebonding.toFixed(2);
                                            tdTotalRebonding.style.fontWeight = 'bold';


                                            var tdTotalConsumption = document.createElement('td');
                                            tdTotalConsumption.textContent = totalConsumption.toFixed(2);
                                            tdTotalConsumption.style.fontWeight = 'bold';


                                            var tdTotalExpectedClosingBalance = document.createElement('td');
                                            tdTotalExpectedClosingBalance.textContent = totalExpectedClosingBalance.toFixed(2);
                                            tdTotalExpectedClosingBalance.style.fontWeight = 'bold';


                                            var tdTotalClosingBalance = document.createElement('td');
                                            tdTotalClosingBalance.textContent = totalClosingBalance.toFixed(2);
                                            tdTotalClosingBalance.style.fontWeight = 'bold';


                                            var tdTotalVariance = document.createElement('td');
                                            tdTotalVariance.textContent = totalVariance.toFixed(2);
                                            tdTotalVariance.style.fontWeight = 'bold';


                                            var tdTotalTotalQuantity = document.createElement('td');
                                            tdTotalTotalQuantity.textContent = totalTotalQuantity.toFixed(2);
                                            tdTotalTotalQuantity.style.fontWeight = 'bold';


                                            var tdTotalActualBalance = document.createElement('td');
                                            tdTotalActualBalance.textContent = totalActualBalance.toFixed(2);
                                            tdTotalActualBalance.style.fontWeight = 'bold';


                                            var tdTotalTotalRebonding = document.createElement('td');
                                            tdTotalTotalRebonding.textContent = totalTotalRebonding.toFixed(2);
                                            tdTotalTotalRebonding.style.fontWeight = 'bold';


                                            trTotal.appendChild(tdTotalLabel);
                                            trTotal.appendChild(document.createElement('td')); // Empty cell for Part Description
                                            trTotal.appendChild(tdTotalOpeningBalance);
                                            trTotal.appendChild(tdTotalCages);
                                            trTotal.appendChild(tdTotalYards);
                                            trTotal.appendChild(tdTotalRebonding);
                                            trTotal.appendChild(tdTotalConsumption);
                                            trTotal.appendChild(tdTotalExpectedClosingBalance);
                                            trTotal.appendChild(tdTotalClosingBalance);
                                            trTotal.appendChild(tdTotalVariance);
                                            tableBody.appendChild(trTotal);
                                        }

                                        // Update the table when the duration is changed
                                        document.getElementById('durationSelect').addEventListener('change', updateTable);

                                        // Initial table population
                                        updateTable();




                                    </script>


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
