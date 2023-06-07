<?php
include '../../cradle_config.php';
$query = "SELECT
stocktake_references.tag AS 'Tag',
locations.name AS 'Location',
locations.issuance_team_id,
skus.name AS 'Part Number',
stocktakes.quantity AS 'Qty',
skus.description AS 'SKU Description'

    FROM
      (((((((((
    cradle.stocktakes 
    LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id)
    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
    LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
    LEFT JOIN locations ON locations.id = stocktakes.location_id )
    LEFT JOIN items ON items.id = stocktakes.item_id )
    LEFT JOIN skus ON skus.id = stocktakes.sku_id )
    LEFT JOIN categories ON categories.id = skus.category_id )
    LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id
    )
    WHERE stocktake_references.cycle_id = 1  ORDER BY stocktakes.date DESC";

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

?>
