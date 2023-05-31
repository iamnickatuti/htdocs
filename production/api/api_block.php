<?php
include '../../cradle_config.php';
include '../sql/sqlBlockCount.php';
?>
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// Assuming you have established a database connection earlier

$data = array();

$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_free_result($result);
}

header('Content-Type: application/json');
echo json_encode($data);

mysqli_close($conn);
?>

