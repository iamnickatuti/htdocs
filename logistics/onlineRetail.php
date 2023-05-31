<?php
session_start();
if (!isset($_SESSION['id']))
{?>
    <?php include '../notloggedin.php'?>
    <?php  die(); }?>
<?php include "../parts/header.php"?>


<?php
if (isset($_GET['status'])) {
    $orderStatus = $_GET['status'];
} else {
}

// Fetch the orders based on the given order status
$url = 'https://reports.moko.co.ke/logistics/api/onlineRetail.php'; // Replace with your actual URL
$jsonArray = file_get_contents($url);
$array = json_decode($jsonArray, true);

$orders = array();
foreach ($array as $item) {
    if ($item['Order_Status'] === $orderStatus) {
        // Calculate the number of days elapsed after the order date
        $orderDate = new DateTime($item['Order_Date']);
        $currentDate = new DateTime(); // Current date
        $elapsedDays = $currentDate->diff($orderDate)->days;

        // Add the elapsed days to the order array
        $item['Elapsed_Days'] = $elapsedDays;
        $orders[] = $item;
    }
}

?>





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

        <?php include '../parts/nav.php';?>
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 font-size-18">Orders</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                  FIlter by date coming soon
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="card-title"><?php echo $orderStatus ; ?></h4>
<!--                                <p class="card-subtitle mb-04 font-size-13">Transaction period from 21 July to 25 Aug</p>-->

                                <div class="table-responsive">
                                    <table class="table table-centered table-striped table-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>Days ELapsed</th>
                                            <th>Order Date</th>
                                            <th>Order No</th>
                                            <th>Customer Name</th>
                                            <th>Ordered Part Number</th>
                                            <th>Ordered Part Description</th>
                                            <th>Qty</th>
                                            <th>Notes</th>
                                            <th>Agent Name</th>
                                            <th>Deal Amount</th>
                                            <th>Customer Contact</th>
                                            <th>Customer Alternative Contact</th>
                                            <th>Customer Address</th>
                                            <th>City</th>
                                            <th>Delivery Date</th>
                                            <th>Payment Reference</th>
                                            <th>Total Amount </th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (count($orders) > 0) {
                                            $html = '';
                                            foreach ($orders as $order) {
                                                $html .= '<tr>';
                                                $html .= '<td>' . $order['Elapsed_Days'] . '</td>';
                                                $html .= '<td>' . $order['Order_Date'] . '</td>';
                                                $html .= '<td>' . $order['order_no'] . '</td>';
                                                $html .= '<td>' . $order['Customer_name'] . '</td>';
                                                $html .= '<td>' . $order['ordered_part_number']. '</td>';
                                                $html .= '<td>' .$order['Order_part_description'].'</td>';
                                                $html .= '<td>' .$order['Order_quantity'].'</td>';
                                                $html .= '<td>' .$order['Notes'].'</td>';
                                                $html .= '<td>' .$order['Agent_name'].'</td>';
                                                $html .= '<td>' .$order['Deal_amount'].'</td>';
                                                $html .= '<td>' .$order['Customer_main_contact'].'</td>';
                                                $html .= '<td>' .$order['Customer_alternative_contact'].'</td>';
                                                $html .= '<td>' .$order['Customer_physical_address'].'</td>';
                                                $html .= '<td>' .$order['City_name'].'</td>';
                                                $html .= '<td>' .$order['Delivery_Date'].'</td>';
                                                $html .= '<td>' .$order['Payment_Reference'].'</td>';
                                                $html .= '<td>' .$order['Reference_amount_paid'] .'</td>';
                                                $html .= '</tr>';
                                            }
                                        } else {
                                            $html .= '<p>No orders found.</p>';
                                        }

                                        echo $html;
                                        ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!--end card body-->

                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->



                </div>
            </div>

        </div>
    </div>
</div>

</body>
<?php include "../parts/footer.php"?>
