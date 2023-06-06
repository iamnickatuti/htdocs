<?php
$today = date("Y-m-d");
$date = date_create_from_format('Y-m-d', $today);
$formatted_date = date_format($date, 'Y M');
$thisMonth = $formatted_date." Stocktake";
$thisMonthClosing = $formatted_date." Stocktake";
$thisMonths = $formatted_date.'%';
$thisMonthss = date('Y-m').'%';

if ($thisMonth == '2023 Jan Stocktake') { $opening = '2022 Dec%'; }
elseif ($thisMonth == '2023 Feb Stocktake'){ $opening = '2023 Jan%'; }
elseif ($thisMonth == '2023 Mar Stocktake'){ $opening = '2023 Feb%'; }
elseif ($thisMonth == '2023 Apr Stocktake'){ $opening = '2023 Mar%'; }
elseif ($thisMonth == '2023 May Stocktake'){ $opening = '2023 Apr%'; }
elseif ($thisMonth == '2023 Jun Stocktake'){ $opening = '2023 May%'; }
?>

<?php
//stock takes
$sqlSpainC = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag = '$thisMonth'
  AND skus.name IN('RM-FS-SP001','RM-FS-SP002','RM-FS-SP003','RM-FS-SP004','RM-FS-SP005','RM-FS-SP006','RM-FS-SP007','RM-FS-SP008','RM-FS-SP009')
order by
  stocktakes.date desc";
$sqlChinaC = "SELECT
  sum(quantity) AS 'qty'

FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag = '$thisMonth'
  AND skus.name IN('RM-FS-CH001','RM-FS-CH002','RM-FS-CH003','RM-FS-CH004','RM-FS-CH005','RM-FS-CH006','RM-FS-CH007')
order by
  stocktakes.date desc ";
$sqlJapanC = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag = '$thisMonth'
  AND skus.name IN('RM-FS-JM001', 'RM-FS-JP002', 'RM-FS-JP003', 'RM-FS-JP004', 'RM-FS-JP005', 'RM-FS-JP006', 'RM-FS-JP007', 'RM-FS-JP008')
order by
  stocktakes.date desc ";
$sqlFilterC = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')   
  AND stocktake_references.cycle_id = 3
  AND tag = '$thisMonth'
  AND skus.name IN('RM-FS-FL002')
order by
  stocktakes.date desc ";
$sqlBraC = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag = '$thisMonth'
  AND skus.name IN('RM-FS-BR001')
order by
  stocktakes.date desc ";
$sqlPlC = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-CH-MD009')
order by
  stocktakes.date desc ";
$sql8HC = "SELECT
  sum(quantity) AS 'qty'
  FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-CH-MD008')
order by
  stocktakes.date desc ";
$sql8C = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-CH-MD007')
order by
  stocktakes.date desc ";
$sqlTestC = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-CH-MD013')
order by
  stocktakes.date desc ";
$sqlSwC = "SELECT
  sum(quantity) AS 'qty'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-FS-SW001')
order by
  stocktakes.date desc ";
$sqlR5C = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-FM-FR005')
order by
  stocktakes.date desc ";
$sqlR1C = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-FM-FR001')
order by
  stocktakes.date desc ";
$sqlR7C = "SELECT
  sum(quantity) AS 'qty'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag = '$thisMonth' AND skus.name IN('RM-FM-FR007')
order by
  stocktakes.date desc ";


$sqlSpain = "SELECT
  sum(quantity) AS 'qty',
    skus.name AS 'Part Number',
    skus.description AS 'Part Description'
FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag LIKE '$opening'
  AND skus.name IN('RM-FS-SP001','RM-FS-SP002','RM-FS-SP003','RM-FS-SP004','RM-FS-SP005','RM-FS-SP006','RM-FS-SP007','RM-FS-SP008','RM-FS-SP009')
order by
  stocktakes.date desc";
$sqlChina = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
   skus.description AS 'Part Description'

FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag LIKE '$opening'
  AND skus.name IN('RM-FS-CH001','RM-FS-CH002','RM-FS-CH003','RM-FS-CH004','RM-FS-CH005','RM-FS-CH006','RM-FS-CH007')
order by
  stocktakes.date desc ";
$sqlJapan = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
    skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag LIKE '$opening'
  AND skus.name IN('RM-FS-JM001', 'RM-FS-JP002', 'RM-FS-JP003', 'RM-FS-JP004', 'RM-FS-JP005', 'RM-FS-JP006', 'RM-FS-JP007', 'RM-FS-JP008')
order by
  stocktakes.date desc ";
$sqlFilter = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
    skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')   
  AND stocktake_references.cycle_id = 3
  AND tag LIKE '$opening'
  AND skus.name IN('RM-FS-FL002')
order by
  stocktakes.date desc ";
$sqlBra = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
  skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1'
  AND locations.id IN ('16','24')
  AND stocktake_references.cycle_id = 3
  AND tag LIKE '$opening'
  AND skus.name IN('RM-FS-BR001')
order by
  stocktakes.date desc ";
$sqlPl = "SELECT
  sum(quantity) AS 'qty' ,
  skus.name AS 'Part Number',
  skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-CH-MD009')
order by
  stocktakes.date desc ";
$sql8H = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
  skus.description AS 'Part Description'


  FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-CH-MD008')
order by
  stocktakes.date desc ";
$sql8 = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
  skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-CH-MD007')
order by
  stocktakes.date desc ";
$sqlTest = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
  skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-CH-MD013')
order by
  stocktakes.date desc ";
$sqlSw = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
    skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-FS-SW001')
order by
  stocktakes.date desc ";
$sqlR5 = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
    skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-FM-FR005')
order by
  stocktakes.date desc ";
$sqlR1 = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
    skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-FM-FR001')
order by
  stocktakes.date desc ";
$sqlR7 = "SELECT
  sum(quantity) AS 'qty',
  skus.name AS 'Part Number',
    skus.description AS 'Part Description'


FROM
  (((((((((( cradle.stocktakes
                      LEFT JOIN stocktake_references ON stocktake_references.id = stocktakes.stocktake_reference_id )
                    LEFT JOIN cycles ON cycles.id = stocktake_references.cycle_id )
                  LEFT JOIN stocktake_coverages ON stocktake_coverages.id = stocktake_references.stocktake_coverage_id )
                LEFT JOIN locations ON locations.id = stocktakes.location_id ) LEFT JOIN users ON users.id = stocktakes.user_id )
            LEFT JOIN items ON items.id = stocktakes.item_id ) LEFT JOIN skus ON skus.id = stocktakes.sku_id )
        LEFT JOIN categories ON categories.id = skus.category_id ) LEFT JOIN sku_types ON sku_types.id = skus.sku_type_id )
    LEFT JOIN units ON units.id = skus.unit_id )
WHERE
  cradle.skus.sku_type_id = '1' AND locations.id IN ('16','24') AND stocktake_references.cycle_id = 3 AND tag LIKE '$opening' AND skus.name IN('RM-FM-FR007')
order by
  stocktakes.date desc ";

//material receipts in cages

$R1rec = "SELECT sum(cage_receipts.value) AS 'r001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (218) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$R5rec = "SELECT sum(cage_receipts.value) AS 'r005' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (222) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$SWrec = "SELECT sum(cage_receipts.value) AS 'sw001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (970) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$TSrec = "SELECT sum(cage_receipts.value) AS 'ts001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (94) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$MDrec = "SELECT sum(cage_receipts.value) AS 'md001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (88) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$JPrec = "SELECT sum(cage_receipts.value) AS 'jp001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (80) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$CHrec = "SELECT sum(cage_receipts.value) AS 'ch001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (78) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$SPrec = "SELECT sum(cage_receipts.value) AS 'sp001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (79) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$FLrec = "SELECT sum(cage_receipts.value) AS 'fl001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (77) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$MIrec = "SELECT sum(cage_receipts.value) AS 'm001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (89) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$PLrec = "SELECT sum(cage_receipts.value) AS 'pl001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (90) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$BCrec = "SELECT sum(cage_receipts.value) AS 'bc001' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (75) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$R7rec = "SELECT sum(cage_receipts.value) AS 'r007' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (848) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";
$TTrec = "SELECT sum(cage_receipts.value) AS 'TOTAL' FROM
(cage_receipts LEFT JOIN skus ON skus.id = cage_receipts.sku_id)
WHERE cage_receipts.sku_id IN (848,90,75,89,79,77,78,80,88,94,970,218,222) AND DATE_FORMAT(cage_receipts.created_at, '%Y %M') LIKE '$thisMonths'";

//material receipts in the yard

$R1man = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '218'";
$R5man = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '222'";
$SWman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '970'";
$TSman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '94'";
$MDman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '88'";
$JPman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '80'";
$CHman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '78'";
$SPman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '79'";
$FLman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '77'";
$MIman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '89'";
$PLman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '90'";
$BCman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '75'";
$R7man = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'

FROM (((((((manufacturing_receipts

LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id = '848'";
$TTman = "SELECT
SUM(manufacturing_receipts.quantity) AS 'total'
FROM (((((((manufacturing_receipts
LEFT JOIN inventories on inventories.id = manufacturing_receipts.inventory_id)
LEFT JOIN items on items.id = inventories.item_id)
LEFT JOIN skus on skus.id = items.sku_id)
LEFT JOIN categories on categories.id = skus.category_id)
LEFT JOIN units on units.id = skus.unit_id)
LEFT JOIN locations on locations.id = manufacturing_receipts.location_id)
LEFT JOIN issuance_teams on issuance_teams.id = locations.issuance_team_id)

WHERE DATE_FORMAT(manufacturing_receipts.created_at, '%Y %M') LIKE '$thisMonths' AND sku_id IN (848,90,75,89,79,77,78,80,88,94,970,218,222)";

$R1Con="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 218";
$R5Con="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 222";
$SWCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 970";
$TSCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 94";
$MDCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 88";
$JPCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 80";
$CHCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 78";
$SPCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 79";
$FLCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 77";
$MICon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 89";
$PLCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 90";
$BCCon="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 75";
$R7Con="SELECT sum(weight) AS 'consumption' FROM `block_components` WHERE date_format(block_components.date, '%Y %M') LIKE '$thisMonths' AND sku_id = 848";



