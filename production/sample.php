
<?php
include '../parts/header.php';
$url = 'https://reports.moko.co.ke/production/api/qtest.php';

$json = file_get_contents($url);
if ($json === false) {
    echo "Error fetching JSON data from URL";
    exit;
}
$data = json_decode($json, true);
if ($data === null) {
    echo "Error decoding JSON data";
    exit;
}
$keys = array_keys($data[0]); // Assuming the keys are the same for all rows
?>

<div class="col-10">
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
    <div class="table-responsive">
        <table id="basic-datatable" class="table dt-responsive nowrap">
            </tbody
            <thead>
            <tr>
                <?php foreach ($keys as $key) : ?>
                    <th><?php echo $key; ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $row) : ?>
                <tr>
                    <?php foreach ($row as $value) : ?>
                        <td><?php echo $value; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
<?php include '../parts/footer.php';?>
