<?php
$sql = "SELECT *
FROM
    (SELECT order_status_logs1.order_id,
            MAX(order_status_logs1.created_at) AS placed_time,
            order_status_logs1.value AS placed_value
    FROM order_status_logs AS order_status_logs1
    WHERE order_status_logs1.value = 0
    AND DATE(order_status_logs1.created_at)
    GROUP BY order_status_logs1.order_id) AS Placed_Data
LEFT JOIN
    (SELECT order_status_logs2.order_id,
            MAX(order_status_logs2.created_at) AS verified_time,
            order_status_logs2.value AS verified_value
    FROM order_status_logs AS order_status_logs2
    WHERE order_status_logs2.value = 1
    AND DATE(order_status_logs2.created_at)
    GROUP BY order_status_logs2.order_id) AS Verified_Data ON Placed_Data.order_id = Verified_Data.order_id
LEFT JOIN
    (SELECT order_status_logs3.order_id,
            MAX(order_status_logs3.created_at) AS picklisted_time,
            order_status_logs3.value AS picklisted_value
    FROM order_status_logs AS order_status_logs3
    WHERE order_status_logs3.value = 2
    AND DATE(order_status_logs3.created_at)
    GROUP BY order_status_logs3.order_id) AS Picklisted_Data ON Placed_Data.order_id = Picklisted_Data.order_id
LEFT JOIN
    (SELECT order_status_logs4.order_id,
            MAX(order_status_logs4.created_at) AS picked_time,
            order_status_logs4.value AS picked_value
    FROM order_status_logs AS order_status_logs4
    WHERE order_status_logs4.value = 9
    AND DATE(order_status_logs4.created_at)
    GROUP BY order_status_logs4.order_id) AS Picked_Data ON Placed_Data.order_id = Picked_Data.order_id
LEFT JOIN
    (SELECT order_status_logs5.order_id,
            MAX(order_status_logs5.created_at) AS packed_time,
            order_status_logs5.value AS packed_value
    FROM order_status_logs AS order_status_logs5
    WHERE order_status_logs5.value = 5
    AND DATE(order_status_logs5.created_at)
    GROUP BY order_status_logs5.order_id) AS Packed_Data ON Placed_Data.order_id = Packed_Data.order_id
LEFT JOIN
    (SELECT order_status_logs6.order_id,
            MAX(order_status_logs6.created_at) AS loaded_time,
            order_status_logs6.value AS loaded_value
    FROM order_status_logs AS order_status_logs6
    WHERE order_status_logs6.value = 14
    AND DATE(order_status_logs6.created_at)
    GROUP BY order_status_logs6.order_id) AS Loaded_Data ON Placed_Data.order_id = Loaded_Data.order_id
LEFT JOIN
    (SELECT order_status_logs7.order_id,
            MAX(order_status_logs7.created_at) AS invoiced_time,
            order_status_logs7.value AS invoiced_value
    FROM order_status_logs AS order_status_logs7
    WHERE order_status_logs7.value = 6
    AND DATE(order_status_logs7.created_at)
    GROUP BY order_status_logs7.order_id) AS Invoiced_Data ON Placed_Data.order_id = Invoiced_Data.order_id
LEFT JOIN
    (SELECT order_status_logs8.order_id,
            MAX(order_status_logs8.created_at) AS delivered_time,
            order_status_logs8.value AS delivered_value
    FROM order_status_logs AS order_status_logs8
    WHERE order_status_logs8.value = 10
    AND DATE(order_status_logs8.created_at)
    GROUP BY order_status_logs8.order_id) AS Delivered_Data ON Placed_Data.order_id = Delivered_Data.order_id
WHERE Verified_Data.verified_value IS NOT NULL
AND Picklisted_Data.picklisted_value IS NOT NULL
AND Picked_Data.picked_value IS NOT NULL
AND Packed_Data.packed_value IS NOT NULL
AND Loaded_Data.loaded_value IS NOT NULL
AND Invoiced_Data.invoiced_value IS NOT NULL
AND Delivered_Data.delivered_value IS NOT NULL
AND Placed_Data.placed_time LIKE '2023-05%'";