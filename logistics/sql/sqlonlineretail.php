<?php
$query = 'SELECT
orders.date AS \'Order_Date\',
orders.order_no,
CONCAT(online_customers.first_name, \' \', online_customers.last_name) AS \'Customer_name\',
skus.name AS \'ordered_part_number\',
skus.description AS \'Order_part_description\',
order_details.quantity AS \'Order_quantity\',
orders.notes AS \'Notes\',
CONCAT(users.first_name, \' \', users.last_name) AS \'Agent_name\',
order_details.amount AS \'Deal_amount\',
online_customers.main_number AS \'Customer_main_contact\',
online_customers.alternative_number AS \'Customer_alternative_contact\',
online_customers.physical_address AS \'Customer_physical_address\',
cities.name AS \'City_name\',
orders.delivery_date AS \'Delivery_Date\',
order_payments.reference AS \'Payment_Reference\',
order_payments.amount AS \'Reference_amount_paid\',
order_statuses.label AS \'Order_Status\',
orders.orderable_type
FROM
(((((((orders
LEFT JOIN online_customers ON orders.`orderable_id` = online_customers.id)
INNER JOIN users ON orders.`user_id` = users.id)
INNER JOIN order_details ON orders.id = order_details.order_id)
INNER JOIN skus ON order_details.sku_id = skus.id)
INNER JOIN cities ON cities.id = online_customers.city_id)
INNER JOIN order_payments ON order_payments.order_id = orders.id)
INNER JOIN order_statuses ON order_statuses.value = orders.status)
WHERE
orders.orderable_type LIKE \'%OnlineCustomer\' AND
orders.delivery_date BETWEEN \'2023-02-02\' AND NOW()';

?>