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

// Initialize an empty array to store unique months
$months = array();

if ($result->num_rows > 0) {
    // Fetch each row and add it to the data array
    while ($row = $result->fetch_assoc()) {
        $category = $row['parent_category'];
        $subcategory = $row['sub_category'];
        $month = $row['month'];
        $units = $row['units'];

        // Check if the category exists in the data array
        if (!isset($data[$category])) {
            $data[$category] = array();
        }

        // Check if the subcategory exists in the category array
        if (!isset($data[$category][$subcategory])) {
            $data[$category][$subcategory] = array();
        }

        // Check if the month exists in the subcategory array
        if (!isset($data[$category][$subcategory][$month])) {
            $data[$category][$subcategory][$month] = 0;

            // Add the month to the unique months array
            if (!in_array($month, $months)) {
                $months[] = $month;
            }
        }

        // Add the units to the corresponding category, subcategory, and month
        $data[$category][$subcategory][$month] += $units;
    }
}

// Close the database connection
$conn->close();

// Output the data as JSON
$output = array(
    'months' => $months,
    'data' => $data
);
header('Content-Type: application/json');
echo json_encode($output);

?>
