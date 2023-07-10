<?php
session_start ();
include '../../partss/header.php'; ?>

    <body>
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

<?php include '../../parts/nav.php';
?>
    <div class="page-content">
    <div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Queue Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0); ">MoKo
                            </a></li>
                        <li class="breadcrumb-item active">Queue Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-12">
    <div class="card">
    <div class="card-body">

    <h4 class="card-title">Orders</h4>
    <p class="card-subtitle mb-4 font-size-13">Transaction period from 21 July to 25 Aug
    </p>

    <div class="table-responsive">
    <table class="table table-centered table-striped table-nowrap mb-0">
    <thead>
    <tr>
        <th>Raw Material</th>
        <th>Description</th>
        <th>Component Quantity</th>
        <th>UOM</th>
        <th>July/2023</th>
        <th>August/2023</th>
        <th>September/2023</th>
        <th>October/2023</th>
        <th>November/2023</th>
        <th>December/2023</th>
        <th>January/2024</th>
        <th>February/2024</th>
        <th>March/2024</th>
        <th>April/2024</th>
        <th>May/2024</th>
        <th>June/2024</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $jsonData= file_get_contents('https://reports.moko.co.ke/demandapi/matresses/test.php');
    // Convert JSON to associative array
    $data = json_decode($jsonData, true);?>
    <?php foreach ($data as $item): ?>
        <?php foreach ($item['Raw Materials'] as $rawMaterial): ?>
            <?php if (isset($rawMaterial['Sub Raw Materials'])): ?>
                <?php foreach ($rawMaterial['Sub Raw Materials'] as $subRawMaterial): ?>
                    <tr>
                        <td><?php echo $subRawMaterial['Raw Material']; ?></td>
                        <td><?php echo $subRawMaterial['RM Description']; ?></td>
                        <td><?php echo $subRawMaterial['Component Quantity']; ?></td>
                        <td><?php echo $subRawMaterial['uom']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['July/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['August/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['September/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['October/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['November/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['December/2023']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['January/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['February/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['March/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['April/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['May/2024']; ?></td>
                        <td><?php echo $subRawMaterial['Multiplied_Values']['June/2024']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td><?php echo $rawMaterial['Raw Material']; ?></td>
                    <td><?php echo $rawMaterial['RM Description']; ?></td>
                    <td><?php echo $rawMaterial['Component Quantity']; ?></td>
                    <td><?php echo $rawMaterial['uom']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['July/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['August/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['September/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['October/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['November/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['December/2023']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['January/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['February/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['March/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['April/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['May/2024']; ?></td>
                    <td><?php echo $rawMaterial['Multiplied_Values']['June/2024']; ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>
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
<?php include '../parts/footer.php';?>