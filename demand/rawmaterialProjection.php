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
                                Raw Material Projection
                            </h4>
                            <div class="page-title-right">
                                <span>#</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row-->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Monthly Stocktake</h5>
                                    <br>
                                    <p class="card-subtitle mb-4">Choose from the drop-down to display data</p>
                                </div>
<div class="table-responsive">
                                <?php
                                header('Access-Control-Allow-Origin: *');
                                header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                                header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

                                $json1 = file_get_contents('https://reports.moko.co.ke/demand/api/bomProjection.php');
                                $json2 = file_get_contents('https://reports.moko.co.ke/demand/api/finishedProducts.php');

                                $json1Array = json_decode($json1, true);
                                $json2Array = json_decode($json2, true);

                                $jsonOutput = [];

                                if (isset($json1Array['products']) && isset($json2Array)) {
                                    foreach ($json1Array['products'] as $product) {
                                        $productNumber = $product['Product'];
                                        $components = $product['Components'];

                                        $productOutput = [
                                            'Product' => $productNumber,
                                            'Product_Description' => $product['Product_Description'],
                                            'Components' => [],
                                        ];

                                        foreach ($components as $component) {
                                            if (isset($component['Component_Part_Number']) && isset($component['Component_Quantity'])) {
                                                $componentNumber = $component['Component_Part_Number'];
                                                $componentQuantity = (float) $component['Component_Quantity'];
                                                $componentOutput = $component;

                                                $matchingItem = null;
                                                foreach ($json2Array as $item) {
                                                    if (isset($item['Part Number']) && $item['Part Number'] === $productNumber) {
                                                        $matchingItem = $item;
                                                        break;
                                                    }
                                                }

                                                if ($matchingItem !== null) {
                                                    $multipliedValues = [];
                                                    foreach ($matchingItem as $key => $value) {
                                                        if ($key !== 'Part Number' && $key !== 'Part Description' && $key !== 'UOM') {
                                                            if (is_numeric($value)) {
                                                                $multipliedValues[$key] = round($value * $componentQuantity, 2);
                                                            } else {
                                                                $multipliedValues[$key] = $value;
                                                            }
                                                        }
                                                    }
                                                    $componentOutput['Multiplied_Values'] = $multipliedValues;
                                                } else {
                                                    $componentOutput['Multiplied_Values'] = [];
                                                }

                                                $productOutput['Components'][] = $componentOutput;
                                            }
                                        }

                                        $jsonOutput[] = $productOutput;
                                    }
                                }

                                $output = json_encode($jsonOutput, JSON_PRETTY_PRINT);
                                $data = json_decode($output, true);

if (is_array($data)) {
    echo "<label for='partNumberSelect'>Select Part Number:</label>";
    echo "<select id='partNumberSelect' onchange='filterTable()'>";
    echo "<option value='all'>All</option>";

    // Collect unique part numbers
    $uniquePartNumbers = array();
    foreach ($data as $product) {
        foreach ($product['Components'] as $component) {
            $partNumber = $component['Component_Part_Number'];
            if (!in_array($partNumber, $uniquePartNumbers)) {
                $uniquePartNumbers[] = $partNumber;
            }
        }
    }

    // Generate dropdown options
    foreach ($uniquePartNumbers as $partNumber) {
        echo "<option value='" . $partNumber . "'>" . $partNumber . "</option>";
    }

    echo "</select>";

    echo "<table id='componentTable' class='table table-centered table-striped mb-0' style='font-size: 11px'>";
    echo "<tr>";
    echo "<th>Component Part Number</th>";
    echo "<th>Component Part Description</th>";
    echo "<th>Component Quantity</th>";
    echo "<th>Component Unit of Measure</th>";
    echo "<th>July 2022</th>";
    echo "<th>August 2022</th>";
    echo "<th>September 2022</th>";
    echo "<th>October 2022</th>";
    echo "<th>November 2022</th>";
    echo "<th>December 2022</th>";
    echo "<th>January 2023</th>";
    echo "<th>February 2023</th>";
    echo "<th>March 2023</th>";
    echo "<th>April 2023</th>";
    echo "<th>May 2023</th>";
    echo "<th>June 2023</th>";
    echo "</tr>";

    // Group components by part number
    $groupedComponents = array();
    foreach ($data as $product) {
        foreach ($product['Components'] as $component) {
            $partNumber = $component['Component_Part_Number'];
            if (!isset($groupedComponents[$partNumber])) {
                $groupedComponents[$partNumber] = array(
                    'Component_Part_Number' => $partNumber,
                    'Component_Part_Description' => $component['Component_Part_Description'],
                    'Component_Quantity' => 0,
                    'Component_Unit_of_Measure' => $component['Component_Unit_of_Measure'],
                    'Multiplied_Values' => array_fill(0, 12, 0) // Initialize with zeros for each month
                );
            }
            $groupedComponents[$partNumber]['Component_Quantity'] += $component['Component_Quantity'];

            foreach ($component['Multiplied_Values'] as $month => $value) {
                $groupedComponents[$partNumber]['Multiplied_Values'][$month] += $value;
            }
        }
    }

    // Display the grouped components
    foreach ($groupedComponents as $partNumber => $component) {
        echo "<tr>";
        echo "<td>" . $component['Component_Part_Number'] . "</td>";
        echo "<td>" . $component['Component_Part_Description'] . "</td>";
        echo "<td>" . $component['Component_Quantity'] . "</td>";
        echo "<td>" . $component['Component_Unit_of_Measure'] . "</td>";
        foreach ($component['Multiplied_Values'] as $monthValue) {
            echo "<td>" . $monthValue . "</td>";
        }
        echo "</tr>";
    }

    // Calculate column totals
    echo "<tr>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td><b>Total:</b></td>";

    $totals = array_fill(0, 12, 0);
    foreach ($groupedComponents as $partNumber => $component) {
        foreach ($component['Multiplied_Values'] as $index => $value) {
            $totals[$index] += $value;
        }
    }

    foreach ($totals as $total) {
        echo "<td><b>" . $total . "</b></td>";
    }

    echo "</tr>";

    echo "</table>";

    echo "<script>
function filterTable() {
    var select = document.getElementById('partNumberSelect');
    var table = document.getElementById('componentTable');
    var rows = table.getElementsByTagName('tr');
    var filterValue = select.value;

    // Reset column totals
    var totalCells = rows[rows.length - 1].getElementsByTagName('td');
    for (var i = 5; i < totalCells.length; i++) {
        totalCells[i].innerHTML = '';
    }

    // Filter rows and calculate column totals
    for (var i = 1; i < rows.length - 1; i++) {
        var row = rows[i];
        var partNumber = row.cells[0].innerHTML;

        if (filterValue === 'all' || partNumber === filterValue) {
            row.style.display = '';

            // Update column totals
            var cells = row.getElementsByTagName('td');
            for (var j = 5; j < cells.length; j++) {
                var value = parseFloat(cells[j].innerHTML);
                if (!isNaN(value)) {
                    var totalCell = totalCells[j];
                    var totalValue = parseFloat(totalCell.innerHTML);
                    totalCell.innerHTML = isNaN(totalValue) ? value.toFixed(2) : (totalValue + value).toFixed(2);
                }
            }
        } else {
            row.style.display = 'none';
        }
    }

    // Remove 'NaN' from column totals
    for (var i = 5; i < totalCells.length; i++) {
        var totalValue = parseFloat(totalCells[i].innerHTML);
        if (isNaN(totalValue)) {
            totalCells[i].innerHTML = '';
        }
    }
}
</script>";
} else {
    echo "Error: Failed to parse JSON data.";
}
?>


                                </tbody>
                                </table>
</div>

<!--                                <style>-->
<!--                                    tr th:nth-child(3) {-->
<!--                                        background-color:#bdffbf;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                    tr td:nth-child(3) {-->
<!--                                        background-color: #bdffbf;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                    tr th:nth-child(4) {-->
<!--                                        background-color:#e3e3e3;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                    tr td:nth-child(4) {-->
<!--                                        background-color: #e3e3e3;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                    tr th:nth-child(7) {-->
<!--                                        background-color:#ffbdbd;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                    tr td:nth-child(7) {-->
<!--                                        background-color: #ffbdbd;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                    tr th:nth-child(8) {-->
<!--                                        background-color:#ffedbd;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                    tr td:nth-child(8) {-->
<!--                                        background-color: #ffedbd;-->
<!--                                        color: #000;-->
<!--                                    }-->
<!--                                </style>-->
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
