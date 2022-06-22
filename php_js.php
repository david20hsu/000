<!DOCTYPE html>
<html lang="en">

<head>

   <meta charset="UTF-8">

   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>資料傳輸到 JSON</title>
</head>

<body>
 <?php  
 require('include/config.php');
 $sql="Select a.hcust_id,a.ar_amt_rcv-(a.ar_amt1+a.ar_amt0) as xant,a.ar_ser,a.narea_id,b.invoice_tp,b.comp_idno,b.tax_code,b.mb_code,b.id_code,b.lv_code,d.tax_tp from ((hcust_ant a left join hcust b on a.hcust_id=b.hcust_id) left join marea c on a.narea_id=c.marea_id) left join comp d on c.comp_con=d.comp_con where right(a.ar_ser,3) >'000' and c.rcv_tp='2' and  a.ar_amt > 0 and  a.hcust_ym='2021-02-01'   and a.narea_id >='PA01' and a.narea_id <='PA01' order by a.narea_id,a.ar_ser";
 $row=$conn->prepare($sql);  
 $row->execute();//execute the query  
 $json_data=array();//create the array  
 date_default_timezone_set("Asia/Taipei");
 foreach($row as $rec)//foreach loop  
 {  
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
    $json_array['timeStamp']=$tm;  //
    $json_array['uncode']='16549670';            // 統一編號
    $json_array['idno']='A122632491';           //登入身分證字號
    $sign =strtoupper(md5($tm.'16549670'.'A122632491'));
    $json_array['sign']= $sign;           //簽名 Gime
    $json_array['customerName']=$rec['hcust_id'];   //N 客戶姓名 
    $json_array['phone']=$phone;           //N 載具 
    $json_array['datetime']=date('Y-m-d h:m:s');  //Y 發票日期(yyyy-MM-dd)
    $json_array['email']='';    //N Email
    $json_array['state'] =$state;    //發票捐贈 無-0  捐贈-1*  
    $json_array['donationCode']=$donationCode;    //N 捐贈碼（當發票捐贈為1是必填）
    $json_array['totalFee']= $rec['xant'];      //Y 總價
    $json_array['items']= 
    [['name','鮮羊乳','money',$rec['xant'],'number',1]];
    
   array_push($json_data,$json_array);  //解決中文問題
}  

//built in PHP function to encode the data in to JSON format  
echo  json_encode($json_data,JSON_UNESCAPED_UNICODE);  
$fp = fopen('test/results.json', 'w');
fwrite($fp, json_encode($json_data,JSON_UNESCAPED_UNICODE));
fclose($fp);

  ?>
</body>

</html>

<!--
$url = "http://example.com/request/post/json";    
$data = json_encode(["foo" => "bar"]);
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_exec($curl);
curl_close($curl);





POST http://www.example.com HTTP/1.1
Content-Type: application/json;charset=utf-8
{"title":"test","sub":[1,2,3]}



<form action="https://www.giveme.com.tw/invoice.do?action=addB2C" method="post" enctype="multipart/form-data" >
    <input type="file" name="file"><input type="submit" value="Submit">
</form>

  Search: <input type="search" name="search"> <input type="submit" value="Submit">
</form>


[{"name":"Tom","lastname":"Chen","report":[{"subject":"Math","score":80},{"subject":"English","score":90}]},{"name":"Amy","lastname":"Lin","report":[{"subject":"Math","score":86},{"subject":"English","score":88}]}]



   
$(function(){
  $('#send').click(function(){
    // 最後必須是 &callback=funcion 或 &jsoncallback=function 結尾
    var URL = "http://abc.com/jsonp.aspx?id=1&name=Bruce&jsoncallback=dosomething";

    $.ajax({
      type : 'GET',
      dataType : 'jsonp',  // 記得是jsonp
      url : URL,
      error : function(xhr, error){ alert('Ajax request error.');}
    })
  })
});
   -->