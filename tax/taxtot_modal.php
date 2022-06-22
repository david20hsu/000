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
            <form class="form-horizontal"  enctype="multipart/form-data" name="taxtot_form" id="txttot_form" action="#" method="POST">
             
                <div class="modal-body">
                    <div class="messages"></div>

                    <div class="form-group">
                       
                           <label for="comp_idno" class="col-sm-5 control-label">公司別:*</label>
                           <div class="col-sm-10">
                             <select class="form-control" name="comp_idno" id="comp_idno" required>
                                <option value="">~~請點選~~</option>
                                <?php
                                 require('./include/config.php'); 
                                 $sql=" select comp_idno,comp_names from comp where api_idno<>'' order by comp_idno";
                                 $sth = $conn->prepare($sql);
                                 $sth->execute();
                                 while($row = $sth->fetch()) {
                                    echo '<option value="' . $row["comp_idno"] . '">' . $row["comp_names"] .'-'.$row["comp_idno"] . '</option>' . "\n";
                                }
                                 ?>
                               </select>
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                        <p>
                        <label for="tp" class="col-sm-5 control-label">類別:*</label>
                         <input type="radio" value="1" name="sel"  checked="checked"> 預收宅配
                         <input type="radio" value="2" name="sel"> 應收宅配
                         <input type="radio" value="3" name="sel"> 應收團戶
                        </p>
                    </div>
                    -->
                    <div class="form-group">
                        <label for="tp" class="col-sm-5 control-label">期間:*</label>
                        <div class="col-sm-10">
                            <input type="text" name="yy" id="yy" maxlength="4" size="4" value="<?php echo date('Y');?>"/>
                            <select   name="month"  id="month" autocomplete="off" required>
                      <?php $monthArr = array('01','02','03','04','05','06','07','08','09','10','11','12'); $currentMonth = date('m'); foreach($monthArr as &$value):?>
                         <?php if($value == $currentMonth): ?>
                             <option value="<?php echo $currentMonth;?>" selected="selected"><?php echo $currentMonth;?></option>
                         <?php else : ?>
                             <option value="<?php echo $value;?>"><?php echo $value;?></option>
                         <?php endif ;?>    
                       <?php endforeach;?>
                    </select>&ensp;~&ensp;
                            <select name="month0" id="month0" autocomplete="off" required>
                                     <?php $monthArr = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
                                        $currentMonth = date('m');
                                        foreach ($monthArr as &$value) : ?>
                                                          <?php if ($value == $currentMonth) : ?>
                                                             <option value="<?php echo $currentMonth; ?>" selected="selected"><?php echo $currentMonth; ?></option>
                                                         <?php else : ?>
                                                              <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                         <?php endif; ?>    
                                                      <?php endforeach; ?>
                            </select> 
                        </div>
                    </div>
                 
                 </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">放棄</button>
                    <button type="submit" name="submit" class="btn btn-primary">提交</button>
                  </div>
            </form>
        </div>
    </div>
</div>
