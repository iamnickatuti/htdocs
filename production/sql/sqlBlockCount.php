<?php
$query= "SELECT *
FROM (
    SELECT
        stocktake_references.tag AS 'Tag',
        skus.description AS 'anza_Part_Description',
        skus.name AS 'anza_Part_Number',
        COUNT(items.name) AS 'opening_count'
    FROM
        cradle.stocktakes
        LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id
        LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id
        LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id
        LEFT JOIN locations ON locations.id = stocktakes.location_id
        LEFT JOIN users ON users.id = stocktakes.user_id
        LEFT JOIN items ON items.id = stocktakes.item_id
        LEFT JOIN skus ON skus.id = stocktakes.sku_id
        LEFT JOIN categories ON categories.id = skus.category_id
        LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
        LEFT JOIN units ON units.id = skus.unit_id
    WHERE
        skus.name LIKE 'WP-BL%'
        AND DATE_FORMAT(stocktake_references.date, '%Y-%M-%d') LIKE '2023-Feb%'
        AND stocktake_references.cycle_id = '3'
    GROUP BY
        stocktake_references.tag,
        skus.description,
        skus.name
) AS anza
LEFT JOIN (
    SELECT
        stocktake_references.tag AS 'Tag',
        skus.description AS 'isha_Part_Description',
        skus.name AS 'isha_Part_Number',
        COUNT(items.name) AS 'closing_count'
    FROM
        cradle.stocktakes
        LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id
        LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id
        LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id
        LEFT JOIN locations ON locations.id = stocktakes.location_id
        LEFT JOIN users ON users.id = stocktakes.user_id
        LEFT JOIN items ON items.id = stocktakes.item_id
        LEFT JOIN skus ON skus.id = stocktakes.sku_id
        LEFT JOIN categories ON categories.id = skus.category_id
        LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id
        LEFT JOIN units ON units.id = skus.unit_id
    WHERE
        skus.name LIKE 'WP-BL%'
        AND DATE_FORMAT(stocktake_references.date, '%Y-%M-%d') LIKE '2023-March%'
        AND stocktake_references.cycle_id = '3'
    GROUP BY
        stocktake_references.tag,
        skus.description,
        skus.name
) AS isha ON isha.isha_Part_Number = anza.anza_Part_Number";