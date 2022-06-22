<div class="modal fade" id="QryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal 頭部 -->
            <div class="modal-header">
                <h4 class="modal-title">
                    <?php echo $title; ?></h3>
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!--
            <form class="form-horizontal"  enctype="multipart/form-data" name="hrb2b_form" id="hrb2b_form" action="./tax/up_hrb2b.php" method="POST">
           -->
            <form class="form-horizontal" enctype="multipart/form-data" name="taxtot_form" id="txttot_form" action="#" method="POST">

                <div class="modal-body">
                    <table id='Q1'>
                        <tr class='md'>
                            <td>項目</td>
                            <td>起始</td>
                            <td>截止</td>
                        </tr>
                        <tr>
                            <td class='md'>發票日期</td>
                            <td><input type="date" name="sdate" id="sdate" value="<?php echo $sdate; ?>" required>
                            </td>
                            <td><input type="date" name="edate" id="edate" value="<?php echo $edate; ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td class='md'>發票號碼</td>
                            <td>
                                <!--
                                <?php if ($xchk = 1 && $etax_id != '') { ?>
                                    <input type="text" name="stax_id" id="stax_id" maxlength="10" value="<?php echo $stax_id; ?>">
                                <?php } else { ?>
                                    <input type="text" name="stax_id" id="stax_id" maxlength="10">
                                <?php } ?>
                                -->
                                <input type="text" name="stax_id" id="stax_id" maxlength="10">
                            </td>

                            <td>
                                <!--
                                <?php if ($xchk = 1 && $etax_id != '') { ?>
                                    <input type="text" name="etax_id" id="etax_id" maxlength="10" value="<?php echo $etax_id; ?>">
                                <?php } else { ?>
                                    <input type="text" name="etax_id" id="etax_id" maxlength="10">
                                <?php } ?>
                                -->
                                <input type="text" name="etax_id" id="etax_id" maxlength="10">
                            </td>
                        </tr>
                        <tr>
                            <td class="md">公司別</td>
                            <td>
                                <select name="comp_idno" id="comp_idno" style="background-color:lightskyblue">
                                    <option value="">~請點選~</option>
                                    <?php
                                    require('./include/config.php');
                                    $sql = " select comp_idno,comp_names from comp where api_idno<>'' order by comp_idno";
                                    $sth = $conn->prepare($sql);
                                    $sth->execute();
                                    while ($row = $sth->fetch()) {
                                        if ($row['comp_idno'] == $comp_idno) {
                                            echo '<option   value="' . $row['comp_idno'] . '" selected>' . $row['comp_names'] . '</option>';
                                        } else {
                                            echo '<option value="' . $row["comp_idno"] . '">' . $row["comp_names"] . '</option>' . "\n";
                                        }
                                    }
                                    ?>
                                </select>

                            </td>
                            <td>
                                狀態:
                                <select name="tax_status" id="tax_status" style="background-color:lightskyblue">
                                    <option value="">全部</option>
                                    <option value="0" <?php if ($tax_status == '0') {
                                                            echo 'selected';
                                                        } ?>>開立</option>
                                    <option value="1" <?php if ($tax_status == '1') {
                                                            echo 'selected';
                                                        } ?>>作廢</option>
                                </select>&ensp;
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">放棄</button>
                    <button type="submit" name="submit" class="btn btn-primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>