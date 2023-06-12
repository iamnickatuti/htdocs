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

// Initialize an empty array to store the results
$data = array();

if ($result->num_rows > 0) {
    // Fetch each row and add it to the data array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Convert the data array to JSON
$jsonData = json_encode($data);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Projection Entries</title>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
<h2>Projection Entries</h2>

<?php
// Check if any data is available
if (!empty($data)) {
    // Get unique months from the data
    $months = array_unique(array_column($data, 'month'));

    // Get unique sub-categories from the data
    $subCategories = array_unique(array_column($data, 'sub-category'));
    $categories = array_unique(array_column($data, 'parent_category'));

    // Create the HTML table
    echo '<table>';

    // Create the table header row with months
    echo '<tr>';
    echo '<th>Category</th>';
    echo '<th>Sub-Category</th>';
    foreach ($months as $month) {
        echo '<th>' . $month . '</th>';
    }
    echo '</tr>';

    // Loop through each category and sub-category and populate the table rows
    foreach ($categories as $category) {
        foreach ($subCategories as $subCategory) {
            echo '<tr>';
            echo '<td>' . $category . '</td>';
            echo '<td>' . $subCategory . '</td>';

            // Loop through each month and find the corresponding units for the sub-category
            foreach ($months as $month) {
                $units = '';
                foreach ($data as $row) {
                    if ($row['month'] == $month && $row['sub-category'] == $subCategory && $row['parent_category'] == $category) {
                        $units = $row['units'];
                        break;
                    }
                }
                echo '<td>' . $units . '</td>';
            }

            echo '</tr>';
        }
    }

    echo '</table>';
} else {
    echo 'No data available.';
}
?>

<h2>JSON Output</h2>
<pre><?php echo $jsonData; ?></pre>
</body>
</html>
