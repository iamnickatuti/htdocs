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
    AND skus.id IN(79, 1810, 1811, 1812, 1813, 1814, 1815, 1816, 1817, 2843, 1818,1819, 1820, 1821, 1823, 1824, 1980, 78, 1804, 1805,
1806 ,1807, 1808, 1809, 218, 219, 220, 221, 222, 848, 2144, 81, 2451, 75, 970 , 88, 89, 90)
    ORDER BY
      stocktakes.date DESC";

$cageQuery = "SELECT 
skus.name as 'Part Name',
cage_receipts.value AS 'Cages',
cage_receipts.created_at AS 'Masaa'
FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE skus.id IN(79, 1810, 1811, 1812, 1813, 1814, 1815, 1816, 1817, 2843, 1818,1819, 1820, 1821, 1823, 1824, 1980, 78, 1804, 1805,
1806 ,1807, 1808, 1809, 218, 219, 220, 221, 222, 848, 2144, 81, 2451, 75, 970 , 88, 89, 90)";

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
    LEFT JOIN issuance_teams ON issuance_teams.id = locations.issuance_team_id) 
    WHERE skus.id IN(79, 1810, 1811, 1812, 1813, 1814, 1815, 1816, 1817, 2843, 1818,1819, 1820, 1821, 1823, 1824, 1980, 78, 1804, 1805,
1806 ,1807, 1808, 1809, 218, 219, 220, 221, 222, 848, 2144, 81, 2451, 75, 970 , 88, 89, 90)";
