<?php
include '../cradle_config.php';
?>
        <div class="page-content">
            <div class="container-fluid">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <!--            <h4 class="card-title">Basic example</h4>-->
                            <!--            <p class="card-subtitle mb-4"> For basic styling—light padding and only horizontal-->
                            <!--                dividers—add the base class <code>.table</code> to any-->
                            <!--                <code>&lt;table&gt;</code>.-->
                            <!--            </p>-->
                            <div class="table-responsive">
                                <table id="basic-datatable" class="table nowrap" style="font-size: 11px;">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Block ID</th>
                                        <th>Block SKU</th>
                                        <th>Block Category</th>
                                        <th>Cut SKU Part Number</th>
                                        <th>Cut SKU Part Description</th>
                                        <th>Cut SKU Category</th>
                                        <th>Cut Qty</th>
                                        <th>Cut Weights</th>
                                        <th>Average SKU Weights</th>
                                        <th>Cummulative SKU weight per block</th>
                                        <th>Dry Block Weight</th>
                                        <th>SKU Cut Weight</th>
                                        <th>Recorded Recycle Weight</th>
                                        <th>Expected Recycle Weight</th>
                                        <th>Recycle Variance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php include './functions/test.php';?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- end card-body-->
                    </div>
                    <!-- end card -->

                </div>
            </div>
        </div>
    </body>



