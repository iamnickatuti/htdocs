<?php
$sqlStocktake = "
    SELECT
      skus.name,
      stocktake_references.tag AS 'Duration',
      cycles.name AS 'Cycle Name',
      stocktakes.quantity AS 'Qty'
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
    WHERE
      cradle.skus.sku_type_id = '1'
      AND locations.id IN ('16', '24')
      AND stocktake_references.cycle_id = 3
    ORDER BY stocktakes.date DESC";

    $cageQuery = "SELECT
    skus.name as 'Part Name',
    cage_receipts.value AS 'Cages',
    cage_receipts.date AS 'Masaa'
    FROM (cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
    WHERE skus.name IN ('RM-FM-FR001','RM-FM-FR002','RM-FM-FR003', 'RM-FM-FR004', 'RM-FM-FR005','RM-FM-FR006')";

$queryYard = "SELECT
    manufacturing_receipts.created_at AS 'Timed',
    manufacturing_receipts.quantity AS 'Yards',
    skus.name AS 'Part Name'
FROM
    (((((((manufacturing_receipts
    LEFT JOIN inventories ON inventories.id = manufacturing_receipts.inventory_id)
    LEFT JOIN items ON items.id = inventories.item_id)
    LEFT JOIN skus ON skus.id = items.sku_id)
    LEFT JOIN categories ON categories.id = skus.category_id)
    LEFT JOIN units ON units.id = skus.unit_id)
    LEFT JOIN locations ON locations.id = manufacturing_receipts.location_id)
    LEFT JOIN issuance_teams ON issuance_teams.id = locations.issuance_team_id) ";

$sqlConsumption = "SELECT skus.name AS 'Part Name',
        block_components.weight AS 'Consumption',
        block_components.date as 'Tarehe'
        FROM block_components
        LEFT JOIN skus ON skus.id = block_components.sku_id";