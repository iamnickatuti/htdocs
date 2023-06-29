<?php

$jsonDataProducts = file_get_contents('https://reports.co.ke/tests/products');
$dataProducts = json_decode($jsonDataProducts, true);

$jsonDataComponents = file_get_contents('https://reports.co.ke/tests/components');
$dataComponents = json_decode($jsonDataComponents, true);

$jsonSubComponents = file_get_contents('https://reports.co.ke/tests/subcomponents');
$dataSubComponents = json_decode($jsonSubComponents, true);

$subComponents = [];

foreach ($dataComponents as $component) {
$subComponents[$component['Sub Component']][] = $component;
}

echo '<table>';

    foreach ($dataProducts as $productKey => $product) {
    echo '<tr>';
        echo '<th>' . $productKey . ' - ' . $product['Product Description'] . '</th>';
        echo '</tr>';

    echo '<tr>';
        echo '<th>Component</th>';
        echo '</tr>';

    $components = $product['Components'];

    foreach ($components as $component) {
    $componentName = $component['Component'];

    // Check if the component starts with 'WP' and has subcomponents
    if (strpos($componentName, 'WP') === 0 && isset($subComponents[$componentName])) {
    $subComponentList = $subComponents[$componentName];

    foreach ($subComponentList as $subComponent) {
    $subComponentName = $subComponent['Component'];
    $component['Component'] = $subComponentName; // Substitute the component with the subcomponent

    echo '<tr>';
        echo '<td>' . $component['Component'] . '</td>';
        echo '</tr>';
    }
    } elseif (strpos($componentName, 'WP') === 0 && isset($dataSubComponents[$componentName])) {
    $subComponentOne = $dataSubComponents[$componentName]['Component One'];
    $component['Component'] = $subComponentOne; // Substitute the component with Component One

    echo '<tr>';
        echo '<td>' . $component['Component'] . '</td>';
        echo '</tr>';
    } else {
    echo '<tr>';
        echo '<td>' . $componentName . '</td>';
        echo '</tr>';
    }
    }
    }

    echo '</table>';
