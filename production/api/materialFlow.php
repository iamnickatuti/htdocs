<?php
include '../../cradle_config.php';
global $conn;
$sql = "
SELECT
  skus.name,
  stocktake_references.tag AS 'Duration',
  cycles.name AS 'Cycle Name',
  stocktakes.quantity AS 'Qty'
FROM
  (((((((((
    cradle.stocktakes
    LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id
  )
  LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id
)
LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id
)
LEFT JOIN locations ON locations.id = stocktakes.location_id
)
LEFT JOIN items ON items.id = stocktakes.item_id
)
LEFT JOIN skus ON skus.id = stocktakes.sku_id
)
LEFT JOIN categories ON categories.id = skus.category_id
)
LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
)
LEFT JOIN units ON units.id = skus.unit_id
)
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16', '24')
  AND stocktake_references.cycle_id = 3
ORDER BY
  stocktakes.date DESC";

$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    $groupedRows = array();

    // Fetch each row from the result set
    while ($row = $result->fetch_assoc()) {
        // Map the SKU name to the parent SKU based on the provided criteria
        $partSpain = array('RM-FS-SP001', 'RM-FS-SP002', 'RM-FS-SP003', 'RM-FS-SP004', 'RM-FS-SP005', 'RM-FS-SP007', 'RM-FS-SP008');
        $partJapan = array('RM-FS-JM001', 'RM-FS-JP002', 'RM-FS-JP004', 'RM-FS-JP005', 'RM-FS-JP007', 'RM-FS-JP008');
        $partChina = array('RM-FS-CH001', 'RM-FS-CH002', 'RM-FS-CH003', 'RM-FS-CH004', 'RM-FS-CH005');
        $partRecycle = array('RM-FM-FR001', 'RM-FM-FR004', 'RM-FM-FR005', 'RM-FM-FR006');
        $partTrial = array('RM-FS-TR001', 'MKE-SKU');
        $partBra = array('RM-FS-BR001');
        $partSweepings = array('RM-FS-SW001');
        $partMD1518 = array('RM-CH-MD007');
        $partMD1518H = array('RM-CH-MD008');

        if (in_array($row['name'], $partSpain)) {
           $psku = 'Raw Material:Foam Scrap:Normal - General/ Code G - SPAIN';
        } elseif (in_array($row['name'], $partJapan)) {
           $psku = 'Raw Material:Foam Scrap:Normal - Japan/ Code J';
        } elseif (in_array($row['name'], $partRecycle)) {
           $psku = 'Raw Material:Foam Scrap:Recycle Foam';
        } elseif (in_array($row['name'], $partChina)) {
           $psku = 'Raw Material:Foam Scrap:Normal - General/ Code G - CHINA';
        } elseif (in_array($row['name'], $partTrial)) {
           $psku = 'RM:Foam Scrap: Trial Foam';
        } elseif (in_array($row['name'], $partBra)) {
           $psku = 'Raw Material:Foam Scrap:Bra - Code B';
        } elseif (in_array($row['name'], $partSweepings)) {
           $psku = 'Raw Material:Foam Scrap:Sweepings';
        } elseif (in_array($row['name'], $partMD1518)) {
           $psku = 'Raw Material:Chemicals:MDI:MDI 1518';
        } elseif (in_array($row['name'], $partMD1518H)) {
           $psku = 'Raw Material:Chemicals:MDI:MDI 1518H';
        } elseif ($row['name'] === 'RM-FM-FR007') {
           $psku = 'Raw Material:Foam Scrap:Recon Mixed';
        } elseif ($row['name'] === 'RM-FS-FL001') {
           $psku = 'RM:Foam Scrap: Filter - Code F (GF)';
        } elseif ($row['name'] === 'RM-FS-FL002') {
           $psku = 'Raw Material:Foam Scrap:Filter - Code F (JF)';
        } elseif ($row['name'] === 'RM-CH-MD009') {
           $psku = 'Raw Material:Chemicals:MDI:MDI-Polyol';
        } elseif ($row['name'] === 'RM-FS-CM051') {
           $psku = 'Raw Material:Local Loose Foam';
        }

        // Create a unique key for grouping based on the combination of Duration and parent_sku
        $groupKey = $row['Duration'] . '-' . $psku;

        if (!isset($groupedRows[$groupKey])) {
            $groupedRows[$groupKey] = array(
                'Part Number' => $row['name'],
                'Duration' => $row['Duration'],
                'name' => $psku,
                'total_quantity' => $row['Qty']
            );
        } else {
            $groupedRows[$groupKey]['total_quantity'] += $row['Qty'];
        }
    }

    // Convert the grouped rows to a simple array
    $groupedArray = array_values($groupedRows);

    // Convert the result to JSON
    $jsonArray = json_encode($groupedArray);

    // Output the JSON array
    header('Content-Type: application/json');
    echo $jsonArray;
}
