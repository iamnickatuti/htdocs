<?php

$thisyr = date("Y");
$year = $thisyr.'%';

$sqlVerified = "select * from
             
(SELECT  order_status_logs1.order_id,
max(order_status_logs1.created_at) as 'placed_time',
order_status_logs1.value
FROM order_status_logs as order_status_logs1
WHERE order_status_logs1.value = 0 and date(order_status_logs1.created_at) LIKE '$year' group by order_status_logs1.order_id ) as Placed_Data


LEFT JOIN

(SELECT  order_status_logs2.order_id,
max(order_status_logs2.created_at) as 'verified_time',
order_status_logs2.value
from order_status_logs as order_status_logs2
WHERE order_status_logs2.value = 1 and date(order_status_logs2.created_at)  LIKE '$year' group by order_status_logs2.order_id ) as Verified_Data

ON Placed_Data.order_id = Verified_Data.order_id where Verified_Data.value is not null  ";

$sqlPicklisted = "select * from

(SELECT  order_status_logs1.order_id,
max(order_status_logs1.created_at) as 'placed_time',
order_status_logs1.value
FROM order_status_logs as order_status_logs1
WHERE order_status_logs1.value = 1 and date(order_status_logs1.created_at)  LIKE '$year' group by order_status_logs1.order_id ) as Placed_Data


LEFT JOIN

(SELECT  order_status_logs2.order_id,
max(order_status_logs2.created_at) as 'verified_time',
order_status_logs2.value
from order_status_logs as order_status_logs2
WHERE order_status_logs2.value = 2 and date(order_status_logs2.created_at)  LIKE '$year' group by order_status_logs2.order_id ) as Verified_Data

ON Placed_Data.order_id = Verified_Data.order_id where Verified_Data.value is not null  ";



$sqlPicked = "select * from

( SELECT  order_status_logs1.order_id,
max(order_status_logs1.created_at) as 'placed_time',
order_status_logs1.value
FROM order_status_logs as order_status_logs1
WHERE order_status_logs1.value = 2 and date(order_status_logs1.created_at)  LIKE '$year' group by order_status_logs1.order_id ) as Placed_Data


LEFT JOIN

(SELECT  order_status_logs2.order_id,
max(order_status_logs2.created_at) as 'verified_time',
order_status_logs2.value
from order_status_logs as order_status_logs2
WHERE order_status_logs2.value = 9 and date(order_status_logs2.created_at)  LIKE '$year' group by order_status_logs2.order_id ) as Verified_Data

ON Placed_Data.order_id = Verified_Data.order_id where Verified_Data.value is not null  ";



$sqlPacked = "select * from

( SELECT  order_status_logs1.order_id,
max(order_status_logs1.created_at) as 'placed_time',
order_status_logs1.value
FROM order_status_logs as order_status_logs1
WHERE order_status_logs1.value = 9 and date(order_status_logs1.created_at)  LIKE '$year' group by order_status_logs1.order_id ) as Placed_Data


LEFT JOIN

(SELECT  order_status_logs2.order_id,
max(order_status_logs2.created_at) as 'verified_time',
order_status_logs2.value
from order_status_logs as order_status_logs2
WHERE order_status_logs2.value = 5 and date(order_status_logs2.created_at)  LIKE '$year' group by order_status_logs2.order_id ) as Verified_Data

ON Placed_Data.order_id = Verified_Data.order_id where Verified_Data.value is not null  ";



$sqlLoaded = "select * from

( SELECT  order_status_logs1.order_id,
max(order_status_logs1.created_at) as 'placed_time',
order_status_logs1.value
FROM order_status_logs as order_status_logs1
WHERE order_status_logs1.value = 5 and date(order_status_logs1.created_at)  LIKE '$year' group by order_status_logs1.order_id ) as Placed_Data


LEFT JOIN

(SELECT  order_status_logs2.order_id,
max(order_status_logs2.created_at) as 'verified_time',
order_status_logs2.value
from order_status_logs as order_status_logs2
WHERE order_status_logs2.value = 14 and date(order_status_logs2.created_at)  LIKE '$year' group by order_status_logs2.order_id ) as Verified_Data

ON Placed_Data.order_id = Verified_Data.order_id where Verified_Data.value is not null ";



$sqlInvoiced = "select * from

( SELECT  order_status_logs1.order_id,
max(order_status_logs1.created_at) as 'placed_time',
order_status_logs1.value
FROM order_status_logs as order_status_logs1
WHERE order_status_logs1.value = 14 and date(order_status_logs1.created_at)  LIKE '$year' group by order_status_logs1.order_id ) as Placed_Data


LEFT JOIN

(SELECT  order_status_logs2.order_id,
max(order_status_logs2.created_at) as 'verified_time',
order_status_logs2.value
from order_status_logs as order_status_logs2
WHERE order_status_logs2.value = 6 and date(order_status_logs2.created_at)  LIKE '$year' group by order_status_logs2.order_id ) as Verified_Data

ON Placed_Data.order_id = Verified_Data.order_id where Verified_Data.value is not null";


$sqlDelivered ="SELECT * FROM orders WHERE status='10' AND orders.created_at LIKE '$year'";
