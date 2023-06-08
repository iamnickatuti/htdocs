<?php

$sql = "SELECT orders.id AS order_id, orders.created_at, orders.status, orders.order_no, orders.orderable_type, customers.name AS customer_name, customers.phone, users.first_name, users.last_name, users.email, SUM(order_details.amount) AS total
FROM orders
LEFT JOIN order_details ON orders.id = order_details.order_id
LEFT JOIN customers ON orders.customer_id = customers.id
LEFT JOIN users ON orders.user_id = users.id
WHERE orders.created_at LIKE '2023%'
GROUP BY orders.order_no, orders.id, customers.id, users.id

";
