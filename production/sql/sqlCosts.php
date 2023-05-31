<?php

$query = "
    WITH Cutting_output AS (
    SELECT
    ROW_NUMBER() OVER () AS 'index',
    DATE_FORMAT(outputs.cutting_date, '%Y-%M-%d') AS 'Cutting_Date',
    outputs.block_id AS 'Key_',
    blocks.name AS 'Block_ID',
    blocks.is_cut,
    blocks.dimension AS 'Block Dimension',
    block_types.name AS 'Block SKU',
    categories.name AS 'Block Category',
    CAST(REPLACE(blocks.weight, ',', '.') AS DECIMAL(10,2)) AS 'Block Weight (kgs)',
    CAST(REPLACE(dry_blocks.weight, ',', '.') AS DECIMAL(10,2)) AS 'Dry Block Weight (kgs)',
    skus.name AS 'Cut SKU Part Number',
    skus.description AS 'Cut SKU Part Description',
    categories1.name AS 'Cut SKU Category',
    units.name AS 'Cut SKU Unit of Measure',
    cradle.lines.name AS 'Cutting Line',
    outputs.quality AS 'Quality',
    outputs.quantity AS 'Cut SKUs Quantity',
    discount_descriptions.name AS 'Discount Descriptions',
    CAST(REPLACE(outputs.weight, ',', '.') AS DECIMAL(10,2)) AS 'Cut_SKU_Weights',
    CAST(REPLACE(recycled_cuttings.weight, ',', '.') AS DECIMAL(10,2)) AS 'Recycle Weight (kgs)',
    CONCAT(users.first_name,' ',users.last_name) AS 'User'
    FROM (((((((((((outputs
    LEFT JOIN blocks ON blocks.id = outputs.block_id)
    LEFT JOIN block_types ON block_types.id = blocks.block_type_id)
    LEFT JOIN categories ON categories.id = block_types.category_id)
    LEFT JOIN skus ON skus.id = outputs.sku_id)
    LEFT JOIN dry_blocks ON dry_blocks.block_id = outputs.block_id)
    LEFT JOIN categories AS categories1 ON categories1.id = skus.category_id)
    LEFT JOIN units ON units.id = skus.unit_id)
    LEFT JOIN cradle.lines ON cradle.lines.id = outputs.line_id)
    LEFT JOIN discount_descriptions ON discount_descriptions.id = outputs.discount_description_id)
    LEFT JOIN users ON users.id = outputs.user_id)
    LEFT JOIN recycled_cuttings ON recycled_cuttings.block_id = outputs.block_id))
    
    SELECT *
    FROM Cutting_output
    WHERE Cutting_output.Cutting_Date LIKE '2023%'
    ORDER BY Key_ DESC, Cut_SKU_Weights ASC
";