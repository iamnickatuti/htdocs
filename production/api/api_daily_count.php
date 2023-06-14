<?php
header('Content-Type: application/json');
include '../../cradle_config.php';
$query = "SELECT
    stocktake_references.tag AS 'Tag',
    stocktake_references.date AS 'Duration',
    locations.name AS 'Location',
    issuance_teams.name AS 'issuance_team_id',
    skus.name AS 'Part Number',
    stocktakes.quantity AS 'Qty',
    skus.description AS 'SKU Description'
FROM
    cradle.stocktakes
    LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id
    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id
    LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id
    LEFT JOIN locations ON locations.id = stocktakes.location_id
    LEFT JOIN issuance_teams ON issuance_teams.id = locations.issuance_team_id
    LEFT JOIN items ON items.id = stocktakes.item_id
    LEFT JOIN skus ON skus.id = stocktakes.sku_id
    LEFT JOIN categories ON categories.id = skus.category_id
    LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
    LEFT JOIN units ON units.id = skus.unit_id
WHERE
    stocktake_references.cycle_id = 1
    AND stocktake_references.date >= CURDATE() - INTERVAL 30 DAY
    AND stocktakes.deleted_by = 0
ORDER BY
    stocktakes.date DESC ";
$data = array();
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    mysqli_free_result($result);
}
// Get unique "Tag" and "issuance_team_id" values
$tags = array_unique(array_column($data, 'Tag'));
$issuanceTeams = array_unique(array_column($data, 'issuance_team_id'));

// Filter the data based on the selected "Tag" and "issuance_team_id"
$selectedTag = isset($_GET['tag']) ? $_GET['tag'] : null;
$selectedTeam = isset($_GET['team']) ? $_GET['team'] : null;

// Prepare the filtered data for JavaScript usage
$filteredData = array_filter($data, function ($row) use ($selectedTag, $selectedTeam) {
    return ($selectedTag === null || $row['Tag'] === $selectedTag)
        && ($selectedTeam === null || $row['issuance_team_id'] === $selectedTeam);
});
$filteredDataJSON = json_encode($filteredData);

?>
