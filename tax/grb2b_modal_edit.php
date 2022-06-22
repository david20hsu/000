
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal 頭部 -->
            <div class="modal-header">
                <h4 class="modal-title">
                
                   <?php echo '團戶應收發票資料-文字檔(.txt)下載'; ?></h3>
                
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form  method="post" enctype="multipart/form-data" name="grb2b_form_edit" id="grb2b_form_edit" action="./tax/gr_export.php">
                <div class="modal-body">
                    <div class="messages"></div>
                    <div class="form-group">
                        <label for="csv_file" class="col-sm-5 control-label">發票日期:*</label>
                        <div class="col-sm-10">         
                             <input type="date"  class="form-control" id="inv_date" name="inv_date" value="<?php echo date('Y-m-d', strtotime('now'));?>" required>  
                         </div>
                    </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">離開</button>
                    <button type="submit" name="submit" class="btn btn-primary">下載</button>
                  </div>
                 </div>
            </form>
        </div>
    </div>
</div>