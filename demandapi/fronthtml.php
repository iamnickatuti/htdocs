<?php
$url = "https://reports.moko.co.ke/demandapi/rm.json";
$json_data = file_get_contents($url);
$data = json_decode($json_data, true);
?>
<table>
    <tr>
        <th>Raw Material</th>
        <th>RM Description</th>
        <th>Component Quantity</th>
        <th>uom</th>
    </tr>
    <?php foreach ($data as $product => $details): ?>
        <?php if (isset($details['Raw Materials'])): ?>

            <?php foreach ($details['Raw Materials'] as $raw_material): ?>
                <tr>
                    <td><?php echo $raw_material['Raw Material']; ?></td>
                    <td><?php echo $raw_material['RM Description']; ?></td>
                    <td><?php echo $raw_material['Component Quantity']; ?></td>
                    <td><?php echo $raw_material['uom']; ?></td>
                </tr>
                <?php if (isset($raw_material['Sub Raw Materials'])): ?>
                    <?php foreach ($raw_material['Sub Raw Materials'] as $sub_raw_material): ?>
                        <tr>
                            <td><?php echo $sub_raw_material['Raw Material']; ?></td>
                            <td><?php echo $sub_raw_material['RM Description']; ?></td>
                            <td><?php echo $sub_raw_material['Component Quantity']; ?></td>
                            <td><?php echo $sub_raw_material['uom']; ?></td>
                        </tr>
                        <?php if (isset($sub_raw_material['Sub Raw Materials'])): ?>
                            <?php foreach ($sub_raw_material['Sub Raw Materials'] as $sub1_raw_material): ?>
                                <tr>
                                    <td><?php echo $sub1_raw_material['Raw Material']; ?></td>
                                    <td><?php echo $sub1_raw_material['RM Description']; ?></td>
                                    <td><?php echo $sub1_raw_material['Component Quantity']; ?></td>
                                    <td><?php echo $sub1_raw_material['uom']; ?></td>
                                </tr>
                                <?php if (isset($sub1_raw_material['Sub Raw Materials'])): ?>
                                    <?php foreach ($sub1_raw_material['Sub Raw Materials'] as $sub2_raw_material): ?>
                                        <tr>
                                            <td><?php echo $sub2_raw_material['Raw Material']; ?></td>
                                            <td><?php echo $sub2_raw_material['RM Description']; ?></td>
                                            <td><?php echo $sub2_raw_material['Component Quantity']; ?></td>
                                            <td><?php echo $sub2_raw_material['uom']; ?></td>
                                        </tr>
                                        <?php if (isset($sub2_raw_material['Sub Raw Materials'])): ?>
                                            <?php foreach ($sub2_raw_material['Sub Raw Materials'] as $sub3_raw_material): ?>
                                                <tr>
                                                    <td><?php echo $sub3_raw_material['Raw Material']; ?></td>
                                                    <td><?php echo $sub3_raw_material['RM Description']; ?></td>
                                                    <td><?php echo $sub3_raw_material['Component Quantity']; ?></td>
                                                    <td><?php echo $sub3_raw_material['uom']; ?></td>
                                                </tr>
                                                <?php if (isset($sub3_raw_material['Sub Raw Materials'])): ?>
                                                    <?php foreach ($sub3_raw_material['Sub Raw Materials'] as $sub4_raw_material): ?>
                                                        <tr>
                                                            <td><?php echo $sub4_raw_material['Raw Material']; ?></td>
                                                            <td><?php echo $sub4_raw_material['RM Description']; ?></td>
                                                            <td><?php echo $sub4_raw_material['Component Quantity']; ?></td>
                                                            <td><?php echo $sub4_raw_material['uom']; ?></td>
                                                        </tr>
                                                        <?php if (isset($sub4_raw_material['Sub Raw Materials'])): ?>
                                                            <?php foreach ($sub4_raw_material['Sub Raw Materials'] as $sub5_raw_material): ?>
                                                                <tr>
                                                                    <td><?php echo $sub5_raw_material['Raw Material']; ?></td>
                                                                    <td><?php echo $sub5_raw_material['RM Description']; ?></td>
                                                                    <td><?php echo $sub5_raw_material['Component Quantity']; ?></td>
                                                                    <td><?php echo $sub5_raw_material['uom']; ?></td>
                                                                </tr>
                                                                <?php if (isset($sub5_raw_material['Sub Raw Materials'])): ?>
                                                                    <?php foreach ($sub5_raw_material['Sub Raw Materials'] as $sub6_raw_material): ?>
                                                                        <tr>
                                                                            <td><?php echo $sub6_raw_material['Raw Material']; ?></td>
                                                                            <td><?php echo $sub6_raw_material['RM Description']; ?></td>
                                                                            <td><?php echo $sub6_raw_material['Component Quantity']; ?></td>
                                                                            <td><?php echo $sub6_raw_material['uom']; ?></td>
                                                                        </tr>
                                                                        <?php if (isset($sub6_raw_material['Sub Raw Materials'])): ?>
                                                                            <?php foreach ($sub6_raw_material['Sub Raw Materials'] as $sub7_raw_material): ?>
                                                                                <tr>
                                                                                    <td><?php echo $sub7_raw_material['Raw Material']; ?></td>
                                                                                    <td><?php echo $sub7_raw_material['RM Description']; ?></td>
                                                                                    <td><?php echo $sub7_raw_material['Component Quantity']; ?></td>
                                                                                    <td><?php echo $sub7_raw_material['uom']; ?></td>
                                                                                </tr>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                         <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
