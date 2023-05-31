<?php
include 'sqlDash.php';
include 'cradle_config.php';

function users() {
    global $query;
    global $conn;
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        return $row['all'];
    }
}
//function users() {
//    global $query, $conn;
//    $result = mysqli_query($conn, $query);
//    $rows = array();
//    while ($row = mysqli_fetch_assoc($result)) {
//        $rows[] = $row;
//    }
//    return $rows;
//}


function thisweekorders() {
    global $conn;
    $currentWeek = date("W");
    $currentWeekQuery = "SELECT count(id) as 'all' FROM orders WHERE orders.status = '10' AND WEEK(orders.created_at) = '$currentWeek'";
    $currentWeekResult = mysqli_query($conn, $currentWeekQuery);
    while ($rowCur = mysqli_fetch_assoc($currentWeekResult)) {
        echo $rowCur['all'];
    }
}
function lastweekorders() {
    global $conn;
    $previousWeek = date("W", strtotime("-1 week"));
    $previousWeekQuery = "SELECT count(id) as 'all' FROM orders WHERE orders.status = '10' AND WEEK(orders.created_at) = '$previousWeek'";
    $previousWeekResult = mysqli_query($conn, $previousWeekQuery);
    while ($rowPrev = mysqli_fetch_assoc($previousWeekResult)) {
        echo $rowPrev['all'];
    }
}