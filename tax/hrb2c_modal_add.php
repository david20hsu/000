<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal 頭部 -->
            <div class="modal-header">
                <h4 class="modal-title">
                <?php echo substr($title,13,strlen($title)-13) .'-CSV上傳'; ?></h3>
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" name="hrb2c_form" id="hrb2c_form" action="./tax/import_hrb2c.php" method="POST">
                <div class="modal-body">
                    <div class="messages"></div>

                    <div class="form-group">
                       
                           <label for="comp_idno" class="col-sm-5 control-label">公司別:*</label>
                           <div class="col-sm-10">
                             <select class="form-control" name="comp_idno" id="comp_idno" required>
                                <option value="">~~請點選~~</option>
                                <?php
                                 require('./include/config.php'); 
                                 $sql=" select comp_idno,comp_names from comp where api_idno<>''  order by comp_idno";
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
                        <label for="csv_file" class="col-sm-5 control-label">發票日期:*</label>
                        <div class="col-sm-10">         
                             <input type="date"  class="form-control" id="inv_date" name="inv_date" value="<?php echo date('Y-m-t', strtotime('now'));?>" required>  
                         </div>
                    </div>
                    -->
                    <div class="form-group">
                        <label for="csv_file" class="col-sm-5 control-label">上傳檔案:*</label>
                        <div class="col-sm-10">         
                             <input type="file"  class="form-control" id="csv_file" name="csv_file"  accept=".csv"  required>  
                         </div>
                      </div> 
                    <div class="form-group">
                        <label for="tp" class="col-sm-5 control-label">發票期間:*</label>
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

<!-- ReModal 本體 -->
<div class="modal fade" id="ReModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal 頭部 -->
      
        <div class="modal-header">
                <h4 class="modal-title">
                <?php echo substr($title,13,strlen($title)-13) .'-發票空白-取號'; ?></h3>
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
            <form class="form-horizontal" enctype="multipart/form-data" name="reno_hrb2c" id="reno_hrb2c" action="./tax/reno_hrb2c.php" method="POST">
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
                                   $conn= null;
                                 ?>
                               </select>
                          </div>
                    </div>
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
                            </select> 
                         </div>
                    </div>
                    <div class="form-group">
                        <label for="tp" class="col-sm-5 control-label">張數:*</label>
                        <div class="col-sm-10">
                           <input type="number" id="listno" name="listno" value="100" step="10">  
                         </div>
                    </div>
                  
                </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                    <button type="submit" name="submit" class="btn btn-primary">提交</button>
                  </div>
            </form>
      </div>
    </div>
  </div>  
</div>
<!----end  ReModal-->

<div class="modal fade" id="ToModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal 頭部 -->
        <div class="modal-header">
          <h4 class="modal-title">Modal UPD 標題位置</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>   
        <!-- Modal 身部 -->
        <div class="modal-body">
         <form active="#" method="post">
               <input type="text" name="cls_id" id="cls_id"/> </br>
               <input type="text" name="cls_name" id="cls_name"/> </br>
         </form>
        </div>   
        <!-- Modal 底部 -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        </div>   
      </div>
    </div>
  </div>  