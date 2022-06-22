<?php
/*
* post 傳送JSON 格式資料
* @param $url string URL
* @param $data_string string 請求的具體內容
* @return array
* code 狀態碼
* result 返回結果
*/
function post_json_data($url, $data_string) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json; charset=utf-8',
'Content-Length: ' . strlen($data_string))
);
ob_start();
curl_exec($ch);
$return_content = ob_get_contents();
ob_end_clean();
$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
return array('timeStamp'=>$return_code, 'sign'=>$return_content);
}

//$arr = array('a'=>'00x','b'=>'中文');
$json_data=array();//create the array  
function bud_json(){
    require('include/config.php');
    $sql="Select a.hcust_id,a.ar_amt_rcv-(a.ar_amt1+a.ar_amt0) as xant,a.ar_ser,a.narea_id,b.invoice_tp,b.comp_idno,b.tax_code,b.mb_code,b.id_code,b.lv_code,d.tax_tp from ((hcust_ant a left join hcust b on a.hcust_id=b.hcust_id) left join marea c on a.narea_id=c.marea_id) left join comp d on c.comp_con=d.comp_con where right(a.ar_ser,3) >'000' and c.rcv_tp='2' and  a.ar_amt > 0 and  a.hcust_ym='2021-02-01'   and a.narea_id >='PA01' and a.narea_id <='PA01' order by a.narea_id,a.ar_ser";
    $row=$conn->prepare($sql);    
    $row->execute();    //execute the query  
    //$json_data=array();//create the array  
    date_default_timezone_set("Asia/Taipei");
    foreach($row as $rec)//foreach loop  
    {  
      $json_data=array();//create the array  
        $state="0";
        $donationCode="";
        $phone="";
        if ($rec['tax_code']=='1' || $rec['tax_code']=='2'){
           $phone=$rec['mb_code']; //載具 
        }
        if ($rec['tax_code']=='3')
        {
           $state="1";   // 愛心捐贈
           $donationCode=$rec['lv_code']; //愛心碼
        }
         //    $xx= json_encode("鮮羊乳", JSON_UNESCAPED_UNICODE);
        $tm=date('Y-m-d h:m:s');
        $json_array['timeStamp']=date('Y-m-d h:m:s');  //
        $json_array['uncode']='16549670';            // 統一編號
        $json_array['idno']='A122632491';           //登入身分證字號
        $json_array['sign']= strtoupper(md5($tm.'16549670'.'A122632491'));           //簽名 Gime
        $json_array['customerName']=$rec['hcust_id'];   //N 客戶姓名 
        $json_array['phone']=$phone;           //N 載具 
        $json_array['datetime']=date('Y-m-d h:m:s');  //Y 發票日期(yyyy-MM-dd)
        $json_array['email']='';    //N Email
        $json_array['state'] =$state;    //發票捐贈 無-0  捐贈-1*  
        $json_array['donationCode']=$donationCode;    //N 捐贈碼（當發票捐贈為1是必填）
        $json_array['totalFee']= $rec['xant'];      //Y 總價
        $json_array['items']= 
        [['name','鮮羊乳','money',$rec['xant'],'number',1]];

      // array_push($json_data,$json_array);  //解決中文問題
      // $data_string = json_encode($json_data,JSON_UNESCAPED_UNICODE);                                                                                   

       var_dump(post_json_data('http://localhost/000/receive_pp.php', $json_array));
    }  
    //echo  json_encode($json_data,JSON_UNESCAPED_UNICODE);  



// $fp = fopen('test/results.json', 'w');
//fwrite($fp, json_encode($json_data,JSON_UNESCAPED_UNICODE));
//fclose($fp);

}
bud_json(); // 產生JSON Array
//var_dump(post_json_data('http://localhost/000/receive.php', json_encode($arr,JSON_UNESCAPED_UNICODE)));
//var_dump(post_json_data('http://localhost/000/receive_pp.php', json_encode($json_data,JSON_UNESCAPED_UNICODE)));
?>