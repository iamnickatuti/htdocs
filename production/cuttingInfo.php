<?php
session_start ();
include '../parts/header.php';
?>
    <body style="color: black;">
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
                            Cutting Information
                        </h4>
                        <div class="page-title-right">
                            <form method="post" action="">
                                <div class="row">
                                    <label for="start_date" style="font-size: 11px">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required>

                                    <label for="end_date"  style="font-size: 11px">End Date:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" required>

                                    <input class="btn btn-warning" type="submit" value="Filter">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row-->
            <div class="row">
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatable" class="table table-striped nowrap" style="font-size: 11px;">
                                    <thead>
                                    <tr>
                                        <th>Block ID</th>
                                        <th>Block Type</th>
                                        <th>Dry Block Weight</th>
                                        <th>T.Cut SKU Weight</th>
                                        <th>Act. Recycle Weight</th>
                                        <th>Block SKU</th>
                                        <th>Exp.Recycle Weight</th>
                                    </tr>
                                    </thead>
                                    </tbody
                                    <?php include 'functions/funcCut.php'; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->


                <style>
                    p{
                        font-size: 11px;
                    }
                </style>


                <div class="col-md-3 col-xl-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Total Blocks Cut</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center mb-0">
                                                <?php echo number_format($cutblocks,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Total SKUs Cut</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center mb-0">
                                                <?php echo number_format($sumsku); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Total Dry Block Weight Cut (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($sumblocktype,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Cut SKUs Weight (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($totalsku,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Actual Recycle Weight (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($totalactual,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Expected Recycle Weight (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-warning mb-0">
                                                <?php echo number_format($totalexpected,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Recycle Weight Variance (kgs)</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <h6 class="d-flex align-items-center text-danger mb-0">
                                                <?php echo number_format($totalactual-$totalexpected,2); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="mb-0">Recycle Variance</p>
                                    </div>
                                    <div class="row d-flex align-items-center mb-0">
                                        <div class="col-8">
                                            <?php
                                            $recycle = number_format((($totalactual-$totalexpected)/$totalexpected)*100,2);
                                            if ($recycle>5){
                                                $text="text-danger";
                                                $notice = "Check cutting output data";
                                            }
                                            else{$text="text-success";}
                                            ?>
                                            <h6 class="d-flex align-items-center <?php echo $text;?>  mb-0">
                                                <?php echo $recycle; ?>%
                                            </h6>
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
<?php include '../parts/footer.php'; ?>