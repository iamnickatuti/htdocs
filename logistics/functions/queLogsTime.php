<?php

$url = 'https://reports.moko.co.ke/logistics/api/queueLogsComplete.php';
$jsonArray = file_get_contents($url);

$orders = json_decode($jsonArray, true);

$intervalLabels = array(
    '<1 Day',
    '1 Day',
    '2 Days',
    '3 Days',
    '4 Days',
    '>5 Days'
);

$orderStatus = array(
    'Verified' => array_fill(0, 6, 0),
    'Picklisted' => array_fill(0, 6, 0),
    'Picked' => array_fill(0, 6, 0),
    'Packed' => array_fill(0, 6, 0),
    'Loaded' => array_fill(0, 6, 0),
    'Invoiced' => array_fill(0, 6, 0),
    'Delivered' => array_fill(0, 6, 0)
);

$currentDateTime = new DateTime('now');
foreach ($orders as $order) {
    $placedTime = new DateTime($order['placed_time']);
    $verifiedTime = new DateTime($order['verified_time']);
    $interval = $verifiedTime->diff($placedTime)->days;
    if ($interval < 1) {
        $orderStatus['Verified'][0]++;
    } elseif ($interval == 1) {
        $orderStatus['Verified'][1]++;
    } elseif ($interval == 2) {
        $orderStatus['Verified'][2]++;
    } elseif ($interval == 3) {
        $orderStatus['Verified'][3]++;
    } elseif ($interval == 4) {
        $orderStatus['Verified'][4]++;
    } else {
        $orderStatus['Verified'][5]++;
    }

    $picklistedTime = new DateTime($order['picklisted_time']);
    $interval = $picklistedTime->diff($verifiedTime)->days;
    if ($interval < 1) {
        $orderStatus['Picklisted'][0]++;
    } elseif ($interval == 1) {
        $orderStatus['Picklisted'][1]++;
    } elseif ($interval == 2) {
        $orderStatus['Picklisted'][2]++;
    } elseif ($interval == 3) {
        $orderStatus['Picklisted'][3]++;
    } elseif ($interval == 4) {
        $orderStatus['Picklisted'][4]++;
    } else {
        $orderStatus['Picklisted'][5]++;
    }

    $pickedTime = new DateTime($order['picked_time']);
    $interval = $pickedTime->diff($picklistedTime)->days;
    if ($interval < 1) {
        $orderStatus['Picked'][0]++;
    } elseif ($interval == 1) {
        $orderStatus['Picked'][1]++;
    } elseif ($interval == 2) {
        $orderStatus['Picked'][2]++;
    } elseif ($interval == 3) {
        $orderStatus['Picked'][3]++;
    } elseif ($interval == 4) {
        $orderStatus['Picked'][4]++;
    } else {
        $orderStatus['Picked'][5]++;
    }

    $packedTime = new DateTime($order['packed_time']);
    $interval = $packedTime->diff($pickedTime)->days;
    if ($interval < 1) {
        $orderStatus['Packed'][0]++;
    } elseif ($interval == 1) {
        $orderStatus['Packed'][1]++;
    } elseif ($interval == 2) {
        $orderStatus['Packed'][2]++;
    } elseif ($interval == 3) {
        $orderStatus['Packed'][3]++;
    } elseif ($interval == 4) {
        $orderStatus['Packed'][4]++;
    } else {
        $orderStatus['Packed'][5]++;
    }

    $loadedTime = new DateTime($order['loaded_time']);
    $interval = $loadedTime->diff($packedTime)->days;
    if ($interval < 1) {
        $orderStatus['Loaded'][0]++;
    } elseif ($interval == 1) {
        $orderStatus['Loaded'][1]++;
    } elseif ($interval == 2) {
        $orderStatus['Loaded'][2]++;
    } elseif ($interval == 3) {
        $orderStatus['Loaded'][3]++;
    } elseif ($interval == 4) {
        $orderStatus['Loaded'][4]++;
    } else {
        $orderStatus['Loaded'][5]++;
    }

    $invoicedTime = new DateTime($order['invoiced_time']);
    $interval = $invoicedTime->diff($loadedTime)->days;
    if ($interval < 1) {
        $orderStatus['Invoiced'][0]++;
    } elseif ($interval == 1) {
        $orderStatus['Invoiced'][1]++;
    } elseif ($interval == 2) {
        $orderStatus['Invoiced'][2]++;
    } elseif ($interval == 3) {
        $orderStatus['Invoiced'][3]++;
    } elseif ($interval == 4) {
        $orderStatus['Invoiced'][4]++;
    } else {
        $orderStatus['Invoiced'][5]++;
    }

    $deliveredTime = new DateTime($order['delivered_time']);
    $interval = $deliveredTime->diff($invoicedTime)->days;
    if ($interval < 1) {
        $orderStatus['Delivered'][0]++;
    } elseif ($interval == 1) {
        $orderStatus['Delivered'][1]++;
    } elseif ($interval == 2) {
        $orderStatus['Delivered'][2]++;
    } elseif ($interval == 3) {
        $orderStatus['Delivered'][3]++;
    } elseif ($interval == 4) {
        $orderStatus['Delivered'][4]++;
    } else {
        $orderStatus['Delivered'][5]++;
    }
}

$totalOrders = count($orders);

