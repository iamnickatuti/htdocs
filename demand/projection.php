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
        $subCategory = $row['sub_category'];
        $month = $row['month'];
        $units = $row['units'];

        // Check if the category exists in the data array
        if (!isset($data[$category])) {
            $data[$category] = array();
        }

        // Check if the subCategory exists in the category array
        if (!isset($data[$category][$subCategory])) {
            $data[$category][$subCategory] = array();
        }

        // Check if the month exists in the subCategory array
        if (!isset($data[$category][$subCategory][$month])) {
            $data[$category][$subCategory][$month] = 0;
        }

        // Add the units to the corresponding category, subCategory, and month
        $data[$category][$subCategory][$month] += $units;
    }
}

// Close the database connection
$conn->close();

// HTML table generation
echo '<table>';
echo '<thead><tr><th>Main Category</th><th>Sub Category</th>'; // Main Category and Sub Category columns

// Generate the table header row with months as column heads
$months = $month;
foreach ($months as $month) {
    echo '<th>' . $month . '</th>';
}
echo '</tr></thead><tbody>';

// Generate the table rows with categories, subCategories, and units data
foreach ($data as $category => $subCategories) {
    foreach ($subCategories as $subCategory => $monthsData) {
        echo '<tr>';
        echo '<td>' . $category . '</td>'; // Main Category
        echo '<td>' . $subCategory . '</td>'; // Sub Category

        // Loop through each month and output the units data
        foreach ($months as $month) {
            $units = isset($monthsData[$month]) ? $monthsData[$month] : 0;
            echo '<td>' . $units . '</td>';
        }

        echo '</tr>';
    }
}

echo '</tbody></table>';

?>
