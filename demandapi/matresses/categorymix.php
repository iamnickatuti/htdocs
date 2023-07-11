<?php
global $conn;
include '../../cradle_config.php';
header('Content-Type: application/json');
$sql = "SELECT
  category_mix_entries.id,
  MAX(category_mix_entries.category_mix_id) AS 'category_mix_id',
  financial_years.name AS 'Financial_year',
  categories.name AS 'Sub-category',
  skus.name AS 'Part Number',
  skus.description AS 'Part Description',
  category_mix_entries.proportion
FROM
  category_mix_entries
  LEFT JOIN category_mixes ON category_mixes.id = category_mix_entries.category_mix_id
  LEFT JOIN financial_years ON financial_years.id = category_mixes.financial_year_id
  LEFT JOIN categories ON categories.id = category_mix_entries.category_id
  LEFT JOIN skus ON skus.id = category_mix_entries.sku_id
  
  WHERE categories.name IN('HD Mattresses','MD Mattresses','Pouffes','Throw Pillows','Footstools','Headboard Cushions','Jiji 2 Seater','Jiji 3 Seater','Kutana Table','Mr Biggie 1 Seater', 'Mr Biggie 2 Seater', 'Mr Biggie 3 Seater','Ottoman','Mokoer Rug','Zzze Rug')
GROUP BY
  category_mix_entries.id,
  category_mixes.financial_year_id,
  category_mix_entries.category_id,
  category_mix_entries.sku_id,
  category_mix_entries.proportion
HAVING category_mix_id = ( SELECT MAX(category_mix_entries.category_mix_id) FROM category_mix_entries )";

$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $conn->error;
} else {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $jsonArray = json_encode($rows);
    echo $jsonArray;
}

// Close the database connection
$conn->close();

?>
