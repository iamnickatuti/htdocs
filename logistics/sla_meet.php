<?php

function meetsla()
{
    include '../cradle_config.php';
    $sql = "SELECT * FROM order_status_logs  WHERE order_status_logs.value ='1'";
    if ($result = $conn->query($sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $time1 = $row['created_at'];
            $sql = "SELECT COUNT(*) AS order_count FROM order_status_logs WHERE created_at >= '$time1' AND created_at <= DATE_ADD('$time1', INTERVAL 3 DAY) AND order_status_logs.value = '10'";
            if ($result = $conn->query($sql)) {
                while ($row = mysqli_fetch_array($result)) {
                   return $row['order_count'];
                }
            }
        }
    }
}

function meetslanot()
{
    include '../cradle_config.php';
    $sql = "SELECT * FROM order_status_logs  WHERE order_status_logs.value ='1'";
    if ($result = $conn->query($sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $time1 = $row['created_at'];
            $sql = "SELECT COUNT(*) AS order_count, id FROM order_status_logs WHERE created_at >= '$time1' AND created_at >= DATE_ADD('$time1', INTERVAL 3 DAY) AND order_status_logs.value = '10'";
            if ($result = $conn->query($sql)) {
                while ($row = mysqli_fetch_array($result)) {
                        return $row['order_count'];
                }
            }

        }
    }
}
function notmeet(){
include '../cradle_config.php';
    $sql = "SELECT * FROM order_status_logs  WHERE order_status_logs.value ='1'";
    if ($result = $conn->query($sql)) {
        while ($row = mysqli_fetch_array($result)) {
            $time1 = $row['created_at'];
            $sql = "SELECT * FROM order_status_logs WHERE created_at >= '$time1' AND created_at >= DATE_ADD('$time1', INTERVAL 3 DAY) AND order_status_logs.value = '10'";
            if ($result = $conn->query($sql)) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr> <td>" . $row["id"]. "</td> <td>" . $row["created_at"]. "</td> <td>" . $row["value"] . "</td></tr>";
                }
            }
        }
        }
}
function slapercentage()
{
    $meet = meetsla();
    $notmeet = meetslanot();

   $percentage = $meet + $notmeet;
   $percent =  ($meet/$percentage)*100;

  return number_format($percent,2);
}

function diffdatesorder(){
    global $conn;
    $sql=mysqli_query($conn,"select * from

( SELECT  order_status_logs1.order_id,
max(order_status_logs1.created_at) as 'verified_time',
order_status_logs1.value
FROM order_status_logs as order_status_logs1
WHERE order_status_logs1.value = 1 group by order_status_logs1.order_id ) as Verified_Data


LEFT JOIN

(SELECT  order_status_logs2.order_id,
max(order_status_logs2.created_at) as 'delivered_time',
order_status_logs2.value
from order_status_logs as order_status_logs2
WHERE order_status_logs2.value = 10 group by order_status_logs2.order_id ) as Delivery_Data

ON Verified_Data.order_id = Delivery_Data.order_id where Delivery_Data.value is not null");

    $count = mysqli_num_rows($sql);
    $totalDays = 0;

    while ($row = mysqli_fetch_assoc($sql)) {
        $date1 = new DateTime($row['verified_time']);
        $date2 = new DateTime($row['delivered_time']);
        $interval = $date1->diff($date2);
        $totalDays += $interval->format('%a');
    }

    $avgDays = intval($totalDays / $count);
    return $avgDays;
}

