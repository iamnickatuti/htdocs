<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link arrow-none" href="../dashboard" id="topnav-charts"  aria-expanded="false">
                            <i class="mdi mdi-poll"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-charts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-truck-delivery"></i>Logistics <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-charts">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tables" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Queue Management<div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                    <a href="../logistics/queue" class="dropdown-item">Not Delivered</a>
                                    <a href="../logistics/queueTimeAnalysis" class="dropdown-item">Delivered</a>
                                </div>
                            </div>
                            <a href="../logistics/online.php" class="dropdown-item">Online Retail</a>
<!--                            <a href="../logistics/sla" class="dropdown-item">SLA</a>-->
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-charts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-toolbox"></i>Production <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-charts">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tables" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Count<div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                    <a href="../production/materialFlow" class="dropdown-item">Monthly</a>
                                    <a href="../production/weeklyCount" class="dropdown-item">Weekly</a>
                                    <a href="../production/dailyCount" class="dropdown-item">Daily </a>
                                </div>
                            </div>

                            <a href="../production/cuttingInfo" class="dropdown-item">Cutting Info</a>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tables" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    QB Upload<div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                    <a href="https://reports.moko.co.ke/qbBom/qbuploadrecycle" class="dropdown-item">Recycle</a>
                                    <a href="https://reports.moko.co.ke/qbBom/qbupload" class="dropdown-item">Cut SKUs</a>

                                </div>
                            </div>
                            <a href="../production/blockFlow" class="dropdown-item">Blocks Flow</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-charts" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-chart-line"></i>Demand Planning <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-charts">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tables" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Consumption Projection<div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                    <a href="../demandapi/mattresses-consumption" class="dropdown-item">Mattresses and Sofas</a>
                                    <a href="../demandapi/wood-consumption" class="dropdown-item">Wood Working</a>
                                </div>
                            </div>
                            <a href="../demand/salesProjection" class="dropdown-item">Sales Projection</a>
                            <a href="../demand/bomProjections" class="dropdown-item">BOM</a>

                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>