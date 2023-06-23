<?php
global $output;
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
                                    <h5 class="card-title mb-0">Raw Material Projection</h5>
                                    <br>
                                    <p class="card-subtitle mb-4">Choose a part number from the drop-down to show single component</p>
                                </div>
<div class="table-responsive">
    <?php
    $json = file_get_contents('https://reports.moko.co.ke/demand/consumptionProjections.php');

    $data = json_decode($json, true);

    if (is_array($data)) {
        $combinedRows = [];

        foreach ($data as $product) {
            foreach ($product['Components'] as $component) {
                $componentNumber = $component['Component_Part_Number'];
                $componentQuantity = $component['Component_Quantity'];

                if (isset($combinedRows[$componentNumber])) {
                    $combinedRows[$componentNumber]['Component_Quantity'] += $componentQuantity;

                    foreach ($component['Multiplied_Values'] as $key => $value) {
                        if ($key !== 'Component_Part_Number' && $key !== 'Component_Quantity' && isset($combinedRows[$componentNumber]['Multiplied_Values'][$key])) {
                            $combinedRows[$componentNumber]['Multiplied_Values'][$key] += intval($value);
                        } elseif ($key !== 'Component_Part_Number' && $key !== 'Component_Quantity') {
                            $combinedRows[$componentNumber]['Multiplied_Values'][$key] = intval($value);
                        }
                    }
                } else {
                    $combinedRows[$componentNumber] = $component;
                    foreach ($component['Multiplied_Values'] as $key => $value) {
                        if ($key !== 'Component_Part_Number' && $key !== 'Component_Quantity') {
                            $combinedRows[$componentNumber]['Multiplied_Values'][$key] = intval($value);
                        }
                    }
                }
            }
        }

        // Generate the options for the part number filter dropdown
        $partNumbers = array_keys($combinedRows);
echo "<div class='col-3 float-right'>";
        echo "<select id='partNumberFilter' onchange='applyFilter()' class='form-control'>";
        echo "<option value='all'>All</option>";

        foreach ($partNumbers as $partNumber) {
            echo "<option value='" . $partNumber . "'>" . $partNumber . "</option>";
        }

        echo "</select>";
        echo "</div>";
        echo "<br/>";
        echo "<table class='table table-centered table-striped mb-0' style='font-size: 11px'>";
        echo "<tr>";
        echo "<th>Component Part Number</th>";
        echo "<th>Component Part Description</th>";
        echo "<th>Qty</th>";
        echo "<th>UOM</th>";
        echo "<th>Jul 2022</th>";
        echo "<th>Aug 2022</th>";
        echo "<th>Sep 2022</th>";
        echo "<th>Oct 2022</th>";
        echo "<th>Nov 2022</th>";
        echo "<th>Dec 2022</th>";
        echo "<th>Jan 2023</th>";
        echo "<th>Feb 2023</th>";
        echo "<th>Mar 2023</th>";
        echo "<th>Apr 2023</th>";
        echo "<th>May 2023</th>";
        echo "<th>Jun 2023</th>";
        echo "</tr>";

        foreach ($combinedRows as $component) {
            echo "<tr>";
            echo "<td>" . $component['Component_Part_Number'] . "</td>";
            echo "<td>" . $component['Component_Part_Description'] . "</td>";
            echo "<td>" . $component['Component_Quantity'] . "</td>";
            echo "<td>" . $component['Component_Unit_of_Measure'] . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['July/2022']) ? number_format($component['Multiplied_Values']['July/2022']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['August/2022']) ? number_format($component['Multiplied_Values']['August/2022']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['September/2022']) ? number_format($component['Multiplied_Values']['September/2022']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['October/2022']) ? number_format($component['Multiplied_Values']['October/2022']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['November/2022']) ? number_format($component['Multiplied_Values']['November/2022']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['December/2022']) ? number_format($component['Multiplied_Values']['December/2022']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['January/2023']) ? number_format($component['Multiplied_Values']['January/2023']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['February/2023']) ? number_format($component['Multiplied_Values']['February/2023']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['March/2023']) ? number_format($component['Multiplied_Values']['March/2023']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['April/2023']) ? number_format($component['Multiplied_Values']['April/2023']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['May/2023']) ? number_format($component['Multiplied_Values']['May/2023']) : '') . "</td>";
            echo "<td>" . (isset($component['Multiplied_Values']['June/2023']) ? number_format($component['Multiplied_Values']['June/2023']) : '') . "</td>";
            echo "</tr>";
        }


        echo "</table>";
    } else {
        echo "Invalid JSON string.";
    }
    ?>

    <script>
        function applyFilter() {
            var filter = document.getElementById("partNumberFilter").value;
            var rows = document.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var partNumber = rows[i].getElementsByTagName("td")[0];
                if (partNumber) {
                    var textValue = partNumber.textContent || partNumber.innerText;
                    if (filter === "all" || textValue === filter) {
                        rows[i].style.display = "";
                    } else {
                        rows[i].style.display = "none";
                    }
                }
            }
        }
    </script>

</div>

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
