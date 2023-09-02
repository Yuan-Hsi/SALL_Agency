<?php
    # $conn=mysqli_connect("host.docker.internal:8989", "root", "root","CPDSS"); # ALERT 

    function connection(){
      $conn=mysqli_connect("mysql", "root", "root","CPDSS"); 
      if(!$conn){
          die('could not connect:'.mysqli_connect_error());
      }
      return $conn;
    }
    $conn = connection();

?>

<!DOCTYPE HTML>
<html lang="zh-TW">
  <head>
    <link href="styles/styleA.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/script.js"></script>
    <script type="text/javascript" src="scripts/table.js"></script>
    <link href="styles/table.css" rel="stylesheet" type="text/css">
    <title>資料上傳</title>



  </head>
  <body>
   
    <!-- navbar -->
       <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <ul class="navbar-nav" >
          <div id="mySidenav" class="sidenav">
              <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
              <a href="start.php" >首頁</a>
              <a href="#">資料上傳</a>
              <!-- <a href="http://140.119.19.81:8080/"  target="_blank" >資料庫系統</a> -->
            </div>           

          <div style="float:left; color:#ffffff; font-size: 30px;">
          <span onclick="openNav()">☰ </span>資料上傳</div>
          <li class="nav-item" style="float: right; margin-right: 50px;">
            <a class="nav-link" href="#" style="display:flex; margin-right: 10px; color:#ffffff; background-color: #333;text-decoration: none; line-height:40px; padding: 0 0px;">User</a>
          </li>
          
        </ul>
      </nav>
      <!-- navbar over -->
    <h1></h1>
    <?php
          $query_date = "SELECT MAX(data_date) FROM `CPDSS_parameter` WHERE Yangtze_river IS NOT NULL;" ;
          $result_date = mysqli_query($conn,$query_date);
          while($row = mysqli_fetch_array($result_date)){
              $max_date = $row["MAX(data_date)"]; 
          }
        
          $query_date = "SELECT MAX(data_date) FROM `CPDSS_parameter` WHERE shredded_copper IS NOT NULL ;" ;
          $result_date = mysqli_query($conn,$query_date);
          while($row = mysqli_fetch_array($result_date)){
              $max_date_sh = $row["MAX(data_date)"]; 
          }
        
    ?>
    
    <div class="all_content" style="width 600px">
    
    <script src="./scripts/script.js"></script>

    <!-- 詞彙表 -->
    <table style="float:right; " >
      <div style = >
        <thead>
          <tr>
            <th style="width: 150px; ">英文縮寫</th>
            <th style="width: 150px;" colspan="3">中文名詞</th>
          </tr>
          
        </thead>
        <tr>  <!--詞彙表的部分 拉開很長喔！-->
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt; margin : 0;"> Date </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 日期 </p>
            </td>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> oil </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 石油價格 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> WTI_oil </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 原油價格 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> Yangtze_river </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 長江有色金屬銅價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> shredded_copper </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 碎銅價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; margin: 20px;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> LME_CDCS </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 倫敦交易所銅現貨價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> FX_broker_gold </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> FX Broker-金價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> FX_broker_silver </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> FX Broker-銀價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> LME_nickel </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 倫敦交易所–鎳價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> LME_aluminum </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 倫敦交易所–鋁價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> LME_zinc </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 倫敦交易所–鋅價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> LME_CDWS </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 倫敦交易所–銅庫存價 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> inflation_rate_of_US </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 美國通膨指數 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> inflation_rate_of_China </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 中國通膨指數 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> CN10YY </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 中國10年期公債殖利率 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> US10YY </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 美國10年期公債殖利率 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> XLI </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 美國工業指數 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> li_keqiang_index </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 李克強指數 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> CRB </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> CRB金屬指數 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> USD_CLP </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 美元/智利比索匯率 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> USD_PEN </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 美元/祕魯新索爾匯率 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> USD_CNY </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 美元/人民幣匯率 </p>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;" class = "c_glossary">
              <p style = "font-size : 10pt;margin : 0;"> USD_EURO </p>
            </td>
            <td style="text-align: center;" class = "e_glossary">
              <p style = "font-size : 10pt;margin : 0;"> 美元/歐元匯率 </p>
            </td>
          </tr>
          </div>
      </table>

      <!-- 頁面說明 -->
       <div style="width: 600px; margin-bottom: 10px;">
        <h3 style="margin-left:20px;">上傳訓練用資料</h3>        
        <li style="margin-left:20px;"><b>上傳檔案</b>：請依照指定格式上傳csv檔（可參考提供範本）。</li>
        <li style="margin-left:20px;">銅價資料庫目前最新一筆資料為<?php echo $max_date;?>。</li>
        <li style="margin-left:20px;">碎銅價資料庫目前最新一筆資料為<?php echo $max_date_sh;?>。</li>
        <li style="margin-left:20px;">可點選左上方之「☰」切換至首頁。</li>
        <li style="margin-left:20px;">建議每次上傳資料時，先下載資料庫資料。</li>
      </div>
     
      <!-- 上傳檔案 -->
      <h3 style="margin-left: 3%; border: 0px solid; background: #6db3e7; border-radius: 5px; width: 120px; text-align: center ;">
        檔案上傳
      </h3>
    
       <table style="margin: 10px 3%;">

        <thead>
          <tr>
            <th style="width: 250px; ">下載範本</th>
            <th style="width: 700px;" colspan="3">上傳檔案</th>
          </tr>
          
        </thead>

        <tbody>
           
          <form class="form"  method="post"  >
            <button type="submit" name="update" style="margin-left: 3%; font-size: 15px; height: 30px;"> 下載資料庫 </button>
          </form>
          
          <form  method="post" enctype="multipart/form-data">
          <tr>
            <td style="text-align: center;">
              <a href="/templates/WeeklyFinalData.csv" download="銅價資料範本.csv" style="text-decoration: underline; color: #0A2B7A; padding: 0;"  >銅價資料範本.csv</a>
            </td>

            <td style="text-align: center;"><input type="file" id="fileToUpload" accept=".csv" name="the_file" style="font-size:15px;"/></td>
            <td style="text-align: center;"><input type='submit' name="submit" value='確認'class="button-30" style="font-size: 15px; height: 30px;" ></input></td>
            <td style="text-align: center;"><input type='submit' value='清除'class="button-30" style="font-size: 15px; height: 30px; color: darkred;" onClick="this.form.reset()"></input></td>
          </tr>
          <tr>
            <td style="text-align: center;">
              <a href="/templates/WeeklyFinalData_sherred.csv" download="碎銅價資料範本.csv" style="text-decoration: underline; color: #0A2B7A; padding: 0;"  >碎銅價資料範本.csv</a> 
            </td>

            <td style="text-align: center;"><input type="file" id="fileToUpload" accept=".csv" name="the_file_sh" style="font-size:15px;"/></td>
            <td style="text-align: center;"><input type='submit' name="submit_sh" value='確認'class="button-30" style="font-size: 15px; height: 30px;" ></input></td>
            <td style="text-align: center;"><input type='submit' value='清除'class="button-30" style="font-size: 15px; height: 30px; color: darkred;" onClick="this.form.reset()"></input></td>
          </tr>


          <?php
          // $mysqli = new mysqli("140.119.19.81:8989", "root", "root","CPDSS");

          // $csvFilePath = "import-template.csv";
          // $file = fopen($csvFilePath, "r");
          // while (($row = fgetcsv($file)) !== FALSE) {
          //     $stmt = $mysqli->prepare("INSERT INTO tbl_users (userName, firstName, lastName) VALUES (?, ?, ?)");
          //     $stmt->bind_param("sss", $row[1], $row[2], $row[3]);
          //     $stmt->execute();
          // }
          ?>

          <?php
                  
          if (isset($_POST['submit']) || isset($_POST['submit_sh'])) {
          $currentDirectory = getcwd();
          $uploadDirectory = "/uploads/";

          $errors = []; // Store errors here

          $fileExtensionsAllowed = ['csv']; // These will be the only file extensions allowed 
          if(isset($_POST['submit'])){
            $fileName = $_FILES['the_file']['name'];
            $fileSize = $_FILES['the_file']['size'];
            $fileTmpName  = $_FILES['the_file']['tmp_name'];
            $fileType = $_FILES['the_file']['type'];
          }
          else{
            $fileName = $_FILES['the_file_sh']['name'];
            $fileSize = $_FILES['the_file_sh']['size'];
            $fileTmpName  = $_FILES['the_file_sh']['tmp_name'];
            $fileType = $_FILES['the_file_sh']['type'];            
          }
          // $fileExtension = strtolower(end(explode('.',$fileName)));

          $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName); 
          
          

            // if (! in_array($fileExtension,$fileExtensionsAllowed)) {
            //   $errors[] = "This file extension is not allowed. Please upload a csv file";
            // }

            if (empty($errors)) {
              $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

              if ($didUpload) {
                //echo "檔案 " . basename($fileName) . " 已上傳完畢！";
                // 寫入資料庫
                $result =  (isset($_POST['submit'])) ? copperUpload($fileName) : $result = copperShUpload($fileName);
                

                
                if($result){
                  echo "檔案 " . basename($fileName) . " 已上傳完畢！";
                }
              } else {
                echo "請選擇欲上傳的檔案";
              }
            } else {
              foreach ($errors as $error) {
                echo $error . "These are the errors" . "\n";
              }
            }
          }
          
          #取資料庫資料
          if (isset($_POST['update'])){
              get_sql_data();
              ?>
              <a href="/templates/sql_data.csv" download="資料庫資料檔.csv" style="text-decoration: underline; color: #0A2B7A; padding:0;"  >資料庫資料檔.csv</a>
          <?php
  
          unset($_POST['update']);
          }

          function copperUpload($fileName){
            $row_date = array();
            $row_oil = array();
            $row_yangtze = array();
            $row_cdcs = array();
            $row_gold = array();
            $row_silver = array();
            $row_nickel = array();
            $row_alu = array();
            $row_zinc = array();
            $row_cdws = array();
            $row_inflation_rate_of_US = array();
            $row_inflation_rate_of_China = array();
            $row_USD_CLP = array();
            $row_USD_PEN = array();
            $row_USD_CNY = array();
            $row_EURO_USD = array();
            $row_count=0;
            $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
            //$fp = fopen("$DOCUMENT_ROOT/text.txt",'w');
            if (($handle = fopen("$DOCUMENT_ROOT/uploads/$fileName", "r")) !== FALSE){
              while (($data = fgetcsv($handle, 100000, ",")) !== FALSE){
                if($row_count == 0){
                  if(sizeof($data) != 16) {echo "<script> alert('所給資料欄位數量有誤，應有 16 項') </script>"; exit;}
                  $index = ['Date','oil','Yangtze_river','LME_CDCS','FX_broker_gold','FX_broker_silver','LME_nickel','LME_aluminum','LME_zinc','LME_CDWS','inflation_rate_of_US','inflation_rate_of_China','USD_CLP','USD_PEN','USD_CNY','EURO_USD'];
                  for($i = 0; $i < 16 ; $i++){
                    if($index[$i] != $data[$i]){echo "<script> alert('$data[$i]應為$index[$i]，請參照範例的標籤給予資料。') </script>";exit;}
                  }
                }
                else{
                  $last_date = $row_date[$row_count-1];
                  if($data[0] == NULL) {echo "<script> alert(' $last_date 的下筆日期不存在，請更改。謝謝') </script>";exit;};
                  $weekarray=array("日","一","二","三","四","五","六");
                  $day = "星期".$weekarray[date("w",strtotime($data[0]))];
                  if($day != "星期三"){echo "<script> alert('$data[0]並非星期三，請更改資料。謝謝') </script>";exit;}
                  if($row_count>2){
                    $new_date=date_create($data[0]);
                    $correct_date = date_interval_create_from_date_string("7 days");
                    if(date_create($last_date) != date_sub($new_date,$correct_date)){echo "<script> alert(' $data[0] 日期錯誤與前筆資料差距非 7 天，請參照範例給予資料。') </script>";exit;};
                  }
                  for($i = 1; $i < 16 ; $i++){
                    if($data[$i] == NULL){echo "<script> alert('$index[$i] 在 $data[0] 時呈現 NULL， 請更改資料！') </script>";exit;}
                  }
                }
                    $row_count++;
                    $push = array_push($row_date, $data[0]);
                    $push = array_push($row_oil, $data[1]);
                    $push = array_push($row_yangtze, $data[2]);
                    $push = array_push($row_cdcs, $data[3]);
                    $push = array_push($row_gold, $data[4]);
                    $push = array_push($row_silver, $data[5]);
                    $push = array_push($row_nickel, $data[6]);
                    $push = array_push($row_alu, $data[7]);
                    $push = array_push($row_zinc, $data[8]);
                    $push = array_push($row_cdws, $data[9]);
                    $push = array_push($row_inflation_rate_of_US, $data[10]);
                    $push = array_push($row_inflation_rate_of_China, $data[11]);
                    $push = array_push($row_USD_CLP, $data[12]);
                    $push = array_push($row_USD_PEN, $data[13]);
                    $push = array_push($row_USD_CNY, $data[14]);
                    $push = array_push($row_EURO_USD, $data[15]);
              }
            }
            
            $conn=mysqli_connect("mysql", "root", "root","CPDSS");  # ALERT 
            if(!$conn){
                die('could not connect:'.mysqli_connect_error());
            }

            //echo($row_count);
            for($i=1;$i<$row_count;$i++){
              
              # 判斷是不是有此日期了
              $query_date = " SELECT data_date FROM `CPDSS_parameter` WHERE data_date = '{$row_date[$i]}' " ;
              $date_result = mysqli_query($conn,$query_date);
    
              while($row = mysqli_fetch_array($date_result)){
                  $date = $row["data_date"]; 
              }
              if(isset($date)){ # 有此日期的話用 update
                $sql_upload=" UPDATE CPDSS_parameter SET oil = $row_oil[$i], Yangtze_river = $row_yangtze[$i] , LME_CDCS = $row_cdcs[$i], LME_CDWS = $row_cdws[$i], FX_broker_gold = $row_gold[$i], FX_broker_silver = $row_silver[$i], LME_nickel = $row_nickel[$i], LME_aluminum = $row_alu[$i], LME_zinc = $row_zinc[$i], USD_CNY = $row_USD_CNY[$i], inflation_rate_of_US = $row_inflation_rate_of_US[$i], inflation_rate_of_China = $row_inflation_rate_of_China[$i], USD_CLP = $row_USD_CLP[$i],USD_PEN = $row_USD_PEN[$i], EURO_USD = $row_EURO_USD[$i] WHERE data_date = '{$row_date[$i]}' ;"; 
              }
              else{  # 沒有此日期的話用 insert
                $sql_upload="INSERT IGNORE INTO CPDSS_parameter (data_date, oil, Yangtze_river, LME_CDCS, FX_broker_gold, FX_broker_silver, LME_nickel, LME_aluminum, LME_zinc, LME_CDWS, inflation_rate_of_US, inflation_rate_of_China, USD_CLP, USD_PEN ,USD_CNY, EURO_USD) 
                VALUES ('$row_date[$i]',{$row_oil[$i]},{$row_yangtze[$i]},{$row_cdcs[$i]},{$row_gold[$i]},{$row_silver[$i]},{$row_nickel[$i]},{$row_alu[$i]},{$row_zinc[$i]},{$row_cdws[$i]},{$row_inflation_rate_of_US[$i]},{$row_inflation_rate_of_China[$i]},{$row_USD_CLP[$i]},{$row_USD_PEN[$i]} ,{$row_USD_CNY[$i]},{$row_EURO_USD[$i]})";
              }
              $result= mysqli_query($conn, $sql_upload); 

              // $errorCode = mysqli_errno( $conn );
              // echo $errorCode;
              //echo(error_reporting(E_ERROR | E_WARNING | E_PARSE));
              //if(error_reporting(E_ALL)){echo("hi");};
              // IF NOT EXISTS(SELECT * FROM CPDSS_parameter WHERE data_date=$row_date[$i]) 
            }
            return $result;
          }
          
          function copperShUpload($fileName){
            $row_date = array();
            $row_oil = array();
            $row_shredded = array();
            $row_cdcs = array();
            $row_cdws = array();
            $row_gold = array();
            $row_silver = array();
            $row_nickel = array();
            $row_alumin = array();
            $row_zinc = array();
            $row_USD_CNY = array();
            $row_CN10YY = array();
            $row_US10YY = array();
            $row_XLI = array();
            $row_li_keqiang_index = array();
            $row_CRB = array();
            $row_count=0;
            $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
            //$fp = fopen("$DOCUMENT_ROOT/text.txt",'w');
            if (($handle = fopen("$DOCUMENT_ROOT/uploads/$fileName", "r")) !== FALSE){
              while (($data = fgetcsv($handle, 100000, ",")) !== FALSE){
                if($row_count == 0){
                  if(sizeof($data) != 16) {echo "<script> alert('所給資料欄位數量有誤，應有 16 項') </script>"; exit;}
                  $index = ['Date','WTI_oil','shredded_copper','LME_CDCS','LME_CDWS','FX_broker_gold','FX_broker_silver','LME_nickel','LME_aluminum','LME_zinc','USD_CNY','CN10YY','US10YY','XLI','li_keqiang_index','CRB'];
                  for($i = 0; $i < 16 ; $i++){
                    if($index[$i] != $data[$i]){echo "<script> alert('$data[$i]應為$index[$i]，請參照範例的標籤給予資料。') </script>";exit;}
                  }
                }
                else{
                  $last_date = $row_date[$row_count-1];
                  if($data[0] == NULL) {echo "<script> alert(' $last_date 的下筆日期不存在，請更改。謝謝') </script>";exit;};
                  $weekarray=array("日","一","二","三","四","五","六");
                  $day = "星期".$weekarray[date("w",strtotime($data[0]))];
                  if($day != "星期三"){echo "<script> alert('$data[0]並非星期三，請更改資料。謝謝') </script>";exit;}
                  if($row_count>2){
                    $new_date=date_create($data[0]);
                    $correct_date = date_interval_create_from_date_string("7 days");
                    if(date_create($last_date) != date_sub($new_date,$correct_date)){echo "<script> alert(' $data[0] 日期錯誤與前筆資料差距非 7 天，請參照範例給予資料。') </script>";exit;};
                  }
                  for($i = 1; $i < 16 ; $i++){
                    if($data[$i] == NULL){echo "<script> alert('$index[$i] 在 $data[0] 時呈現 NULL， 請更改資料！') </script>";exit;}
                  }
                }
                    $row_count++;
                    $push = array_push($row_date, $data[0]);
                    $push = array_push($row_oil, $data[1]);
                    $push = array_push($row_shredded, $data[2]);
                    $push = array_push($row_cdcs, $data[3]);
                    $push = array_push($row_cdws, $data[4]);
                    $push = array_push($row_gold, $data[5]);
                    $push = array_push($row_silver, $data[6]);
                    $push = array_push($row_nickel, $data[7]);
                    $push = array_push($row_alumin, $data[8]);
                    $push = array_push($row_zinc, $data[9]);
                    $push = array_push($row_USD_CNY, $data[10]);
                    $push = array_push($row_CN10YY, $data[11]);
                    $push = array_push($row_US10YY, $data[12]);
                    $push = array_push($row_XLI, $data[13]);
                    $push = array_push($row_li_keqiang_index, $data[14]);
                    $push = array_push($row_CRB, $data[15]);
              }
            }
            
            $conn=mysqli_connect("mysql", "root", "root","CPDSS");  # ALERT 
            if(!$conn){
                die('could not connect:'.mysqli_connect_error());
            }

            //echo($row_count);
            for($i=1;$i<$row_count;$i++){
              
              # 判斷是不是有此日期了
              $query_date = " SELECT data_date FROM `CPDSS_parameter` WHERE data_date = '{$row_date[$i]}' " ;
              $date_result = mysqli_query($conn,$query_date);
    
              while($row = mysqli_fetch_array($date_result)){
                  $date = $row["data_date"]; 
              }
              if(isset($date)){ # 有此日期的話用 update
                $sql_upload=" UPDATE CPDSS_parameter SET WTI_oil = $row_oil[$i], shredded_copper = $row_shredded[$i] , LME_CDCS = $row_cdcs[$i], LME_CDWS = $row_cdws[$i], FX_broker_gold = $row_gold[$i], FX_broker_silver = $row_silver[$i], LME_nickel = $row_nickel[$i], LME_aluminum = $row_alumin[$i], LME_zinc = $row_zinc[$i], USD_CNY = $row_USD_CNY[$i], CN10YY = $row_CN10YY[$i], US10YY = $row_US10YY[$i], XLI = $row_XLI[$i],li_keqiang_index = $row_li_keqiang_index[$i], CRB = $row_CRB[$i] WHERE data_date = '{$row_date[$i]}' ;";
              }
              else{  # 沒有此日期的話用 insert
                $sql_upload="INSERT IGNORE INTO CPDSS_parameter (data_date, WTI_oil, shredded_copper, LME_CDCS, LME_CDWS, FX_broker_gold, FX_broker_silver, LME_nickel, LME_aluminum, LME_zinc, USD_CNY, CN10YY, US10YY, XLI,li_keqiang_index, CRB) 
                VALUES ('$row_date[$i]',{$row_oil[$i]},{$row_shredded[$i]},{$row_cdcs[$i]},{$row_cdws[$i]},{$row_gold[$i]},{$row_silver[$i]},{$row_nickel[$i]},{$row_alumin[$i]},{$row_zinc[$i]},{$row_USD_CNY[$i]},{$row_CN10YY[$i]},{$row_US10YY[$i]},{$row_XLI[$i]} ,{$row_li_keqiang_index[$i]},{$row_CRB[$i]})";
              }
              $result= mysqli_query($conn, $sql_upload); 
              // $errorCode = mysqli_errno( $conn );
              // echo $errorCode;
              //echo(error_reporting(E_ERROR | E_WARNING | E_PARSE));
              //if(error_reporting(E_ALL)){echo("hi");};
              // IF NOT EXISTS(SELECT * FROM CPDSS_parameter WHERE data_date=$row_date[$i]) 
            }
            return $result;
          }

          function get_sql_data(){
            $conn=mysqli_connect("mysql", "root", "root","CPDSS"); 
            if(!$conn){
                die('could not connect:'.mysqli_connect_error());
            }
            $query = "SELECT * FROM CPDSS_parameter ORDER BY data_date ";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            $line_count = 0;  
            $DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
            copy("$DOCUMENT_ROOT/templates/template.csv", "$DOCUMENT_ROOT/templates/sql_data.csv");
            # 取 index 而已
            $fp1 = fopen("$DOCUMENT_ROOT/templates/template.csv", "r");
            while (($data = fgetcsv($fp1,10000,",")) !== FALSE){
                $index = $data;
            }
            # 寫入 csv
            $fp2 = fopen("$DOCUMENT_ROOT/templates/sql_data.csv", 'w');
            $str = $index[0].',';
            for($i = 1 ; $i < 22 ; $i++){
                $str = $str.$index[$i].',';
            }
            $str = $str.$index[22];
            fputcsv($fp2, explode(',', $str));
            while($line = $result->fetch_assoc()){
                $str = $line[$index[0]].',';
                for($i = 1 ; $i < 22 ; $i++){
                    $str = $str.$line[$index[$i]].',';
                }
                $str = $str.$line[$index[22]];
                fputcsv($fp2, explode(',', $str));
            }
           }
    

?>
        
      </form>


          
        </tbody>
      <?php
      echo '<script>', 'showMessage();', '</script>';
      ?>
      <script>
        
      </script>
    </div>
    

    
          
    

    </body>





