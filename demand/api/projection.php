<?php

include '../cradle_config.php';
// SQL query to retrieve data
$sql = "SELECT
  MAX(projection_entries.projection_id) AS 'projection_id',
  projection_entries.month,
  projection_entries.year,
  financial_years.name AS 'Financial_year',
  parent_categories.name AS 'parent_category',
  projection_entries.sub_category,
  projection_entries.units,
  projection_entries.unit_of_measure,
  projection_entries.price,
  categories.parent_id,
  projection_entries.amount
FROM
  ((((projection_entries
  LEFT JOIN projections ON projections.id = projection_entries.projection_id)
  LEFT JOIN financial_years ON financial_years.id = projections.financial_year_id)
  LEFT JOIN categories ON categories.id = projection_entries.category_id)
  LEFT JOIN categories AS parent_categories ON categories.parent_id = parent_categories.id)
GROUP BY
  projection_entries.id,
  projections.financial_year_id,
  projection_entries.month,
  projection_entries.sub_category,
  projection_entries.units
HAVING
  projection_id = (SELECT MAX(projection_entries.projection_id) FROM projection_entries)
";

$result = $conn->query($sql);

// Initialize an empty associative array to store the results
$data = array();

if ($result->num_rows > 0) {
    // Fetch each row and add it to the data array
    while ($row = $result->fetch_assoc()) {
        $category = $row['parent_category'];
        $subcategory = $row['sub_category'];
        $month = $row['month'];
        $units = $row['units'];

        // Create the subcategory if it doesn't exist
        if (!isset($data[$category][$subcategory])) {
            $data[$category][$subcategory] = array();
        }

        // Add the units to the corresponding category, subcategory, and month
        $data[$category][$subcategory][$month] = $units;
    }
}

// Close the database connection
$conn->close();

// Generate the JSON result
$jsonResult = array();

foreach ($data as $category => $subcategories) {
    foreach ($subcategories as $subcategory => $monthsData) {
        $result = array(
            "Parent Category" => $category,
            "Sub Category" => $subcategory
        );

        foreach ($monthsData as $month => $units) {
            $result[$month] = $units;
        }

        $jsonResult[] = $result;
    }
}
header('Content-Type: application/json');

// Output the JSON result
echo json_encode($jsonResult, JSON_PRETTY_PRINT);

?>
