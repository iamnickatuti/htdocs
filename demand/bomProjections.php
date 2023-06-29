<?php
session_start ();
include '../parts/header.php';
?>
<?php
$jsonUrl = "https://reports.moko.co.ke/demand/api/bomProjection";
$jsonData = file_get_contents($jsonUrl);
$data = json_decode($jsonData, true);
?>

<body>
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
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">
                                Bom Projection
                            </h4>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php
                                if (isset($data['products']) && !empty($data['products'])) {
                                     foreach ($data['products'] as $product) {
                                        echo '<br/>';
                                        echo '<h4>Product: ' . $product['Product'] . '</h4>';
                                        echo '<table>';
                                        echo '<tr>';
                                        echo '<th>Component Part Number</th>';
                                        echo '<th>Component Part Description</th>';
                                        echo '<th>Component Quantity</th>';
                                        echo '<th>Component Unit of Measure</th>';
                                        echo '<th>% BOM Share</th>';
                                        echo '</tr>';
                                        foreach ($product['Components'] as $component) {
                                            echo '<tr>';
                                            echo '<td>' . $component['Component_Part_Number'] . '</td>';
                                            echo '<td>' . $component['Component_Part_Description'] . '</td>';
                                            echo '<td>' . $component['Component_Quantity'] . '</td>';
                                            echo '<td>' . $component['Component_Unit_of_Measure'] . '</td>';
                                            echo '<td>' . $component['%_BOM_Share'] . '</td>';
                                            echo '</tr>';
                                        }

                                        echo '</table>';
                                    }
                                } else {
                                    echo 'No products found.';
                                }
                                ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</body>
<?php include '../parts/footer.php'; ?>