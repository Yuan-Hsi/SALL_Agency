<?php
    $conn=mysqli_connect("host.docker.internal:8989", "root", "root","CPDSS");
    if(!$conn){
        die('could not connect:'.mysqli_connect_error());
    }
?>
<!DOCTYPE HTML>
<html lang="zh-TW">
  <head>
    <link href="styles/styleA.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/script.js"></script>
    <script type="text/javascript" src="scripts/table.js"></script>
    <link href="styles/table.css" rel="stylesheet" type="text/css">
    <title>銅價預測模型訓練設定</title>



  </head>
  <body>
   
    <!-- navbar -->
       <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <ul class="navbar-nav" >
          <div id="mySidenav" class="sidenav">
              <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
              <a href="start.php" onclick="return confirm('設定尚未完成，您確定要離開嗎？')">首頁</a>
              <a href="#">模型訓練<li style="font-size:16px;">設定・成果</li></a>
              <a href="management.php" onclick="return confirm('設定尚未完成，您確定要離開嗎？')">模型管理<li style="font-size:16px; ">部署・刪除</li></a>
              <a href="inferencing.php" onclick="return confirm('設定尚未完成，您確定要離開嗎？')">銅價預測<li style="font-size:16px;">輸入・結果</li></a>
            </div>           

          <div style="float:left; color:#ffffff; font-size: 30px;">
          <span onclick="openNav()">☰ </span>銅價預測模型訓練</div>
          <li class="nav-item" style="float: right; margin-right: 50px;">
            <a class="nav-link" href="#" style="display:flex; margin-right: 10px; color:#ffffff; background-color: #333;text-decoration: none; line-height:40px; padding: 0 0px;">User</a>
          </li>
          
        </ul>
      </nav>
      <!-- navbar over -->
    <h1></h1>

    <div class="all_content">



      <!-- 頁面說明 -->
      <div style="width: 1200px; margin-bottom: 10px;">
        <h3 style="margin-left:20px;">請您協助設定模型訓練之相關設定</h3>
        <li style="margin-left:20px;">本模型輸出為未來一至四週之預測銅價，
           <span class="highlight_des">每週都是一個子模型，共有四個子模型</span>。</li>
        <li style="margin-left:20px;">需請您協助設定的項目有
        <span class="highlight_des"> (1) 模型輸入變數選擇 (2) 訓練資料設定 (3) 超參數設定</span>。</li>
        <li style="margin-left:20px;">系統預設值為<span class="highlight_des">最近一次訓練</span>時使用的設定。</li>
        <li style="margin-left:20px;"><b>模型輸入變數選擇</b>：根據文獻回顧，本模型提供18種相關變數，您可以取消勾選部分變數。</li>
        <li style="margin-left:20px;"><b>訓練資料設定</b>：設定您要用於訓練的資料之期間。</li>
        <li style="margin-left:20px;"><b>超參數設定</b>：模型訓練所需之相關設定，您可以修改系統預填入的值，調整模型之訓練表現。</li>
        <li style="margin-left:20px;">您可以自行輸入
          <span class="highlight_des">備忘錄</span>，以協助您記憶不同模型。</li>
        <li style="margin-left:20px;">設定完成後，請點選下方按鈕，送出設定並開始訓練模型。</li>
        <li style="margin-left:20px;">可點選左上方之「☰」切換至模型管理、銅價預測。</li>
      </div>

      <!-- Select and construct appropriate features (x) -->
      <div style="float:left;">
      <div class = 'x_box'> 
      <p class = 'define_x'>模型輸入變數選擇</p>


      <?php 
      // 上次有勾選之變數 設定為checked
      $selection_gold = "";
      $selection_silver = "";
      $selection_nickel = "";
      $selection_aluminum = "";
      $selection_zinc = "";
      $selection_usd_clp = "";
      $selection_usd_pen = "";
      
      if ($last_row[25]=="1") { $selection_gold = 'checked';}
      if ($last_row[26]=="1") { $selection_silver = 'checked';}
      if ($last_row[27]=="1") { $selection_nickel = 'checked';}
      if ($last_row[28]=="1") { $selection_aluminum = 'checked';}
      if ($last_row[29]=="1") { $selection_zinc = 'checked';}
      if ($last_row[33]=="1") { $selection_usd_clp = 'checked';}
      if ($last_row[34]=="1") { $selection_usd_pen = 'checked';}

      ?>

    <!-- 變數說明 -->
    <li style="margin-left:20px;">同一類別中必須至少勾選一項。</li>
    <li style="margin-left:20px;">銅相關變數不得取消勾選。</li>
    <li style="margin-left:20px;">至多可以勾選18種，若需要其他變數，請聯繫工程師。</li>
    
    
    <form action='<?php echo $_SERVER['PHP_SELF'];?>' method="POST">  
      <!-- 能源類別 -->
      <div>
        <div class = 'category'>能源</div>
        <div class = 'checkbox'>
          
          <input type="checkbox" id="oil" name="oil" value="1" checked disabled/>石油
          <div class="tooltip" style="float: right; margin-right: 10px;"><img src="https://img.icons8.com/ios/50/000000/info--v1.png" style="height: 20px; vertical-align: middle;"/>
            <span class="tooltiptext tooltip-right" style="top: -5px; left: 105%;  width:220px; padding: 0 10px;">同一類別中必須至少勾選一項</span>
          </div>
           
        </div>
    </div>

      <!-- 貴金屬類別 -->
      <div>
        <div class = 'category' style="height: 352px;">貴金屬<br>及<br>工業金屬價格</div>
        <div class = 'checkbox'>
          <div><input type="checkbox" id="Yangtze_river_4" name='Yangtze_river_4' value="1" checked disabled>長江有色金屬網-過去第4週銅價
          <div class="tooltip" style="float: right; margin-right: 10px;"><img src="https://img.icons8.com/ios/50/000000/info--v1.png" style="height: 20px; vertical-align: middle;"/>
            <span class="tooltiptext tooltip-right" style="top: -5px; left: 105%; width:220px; padding: 0 10px;">銅相關變數不得取消勾選</span>
          </div><br></div>

          <input type="checkbox" id="Yangtze_river_3" name="Yangtze_river_3" value="1" checked disabled>長江有色金屬網-過去第3週銅價<br>
          <input type="checkbox" id="Yangtze_river_2" name="Yangtze_river_2"value="1" checked disabled>長江有色金屬網-過去第2週銅價<br>
          <input type="checkbox" id="Yangtze_river_1" vname="Yangtze_river_1" alue="1" checked disabled>長江有色金屬網-過去第1週銅價<br>
          <input type="checkbox" id="LME_cdcs" name ="LME_cdcs" value="1" checked disabled>倫敦交易所–銅現貨價<br>
          <input type="checkbox" id="LME_cdws" name ="LME_cdws" value="1" checked disabled>倫敦交易所–銅庫存價<br>
          <input type="checkbox" id="FX_broker_gold" name="FX_broker_gold"value="1" <?php echo $selection_gold;?> >FX Broker-金價<br>  
          <input type="checkbox" id="FX_broker_silver" name="FX_broker_silver"value="1" <?php echo $selection_silver;?> >FX Broker-銀價<br>
          <input type="checkbox" id="LME_nickel" name="LME_nickel"value="1" <?php echo $selection_nickel;?>>倫敦交易所–鎳價<br>  
          <input type="checkbox" id="LME_aluminum" name="LME_aluminum" value="1" <?php echo $selection_aluminum;?>>倫敦交易所–鋁價<br>
          <input type="checkbox" id="LME_zinc" name="LME_zinc"value="1" <?php echo $selection_zinc;?>>倫敦交易所–鋅價<br>  
        </div>
    </div>

      <!-- 總體經濟類別 -->
      <div>
        <div class = 'category' style="height: 192px;">總體<br>經濟</div>
        <div class = 'checkbox'>
          <input type="checkbox" id="inflation_rate_of_US" name="inflation_rate_of_US" value="inflation_rate_of_US_checkbox" checked="checked" disabled>美國通膨指數<br> 
          <input type="checkbox" id="inflation_rate_of_China" name="inflation_rate_of_China" value="inflation_rate_of_China_checkbox" checked="checked" disabled>中國通膨指數<br>
          <input type="checkbox" id="USD_CLP" name="USD_CLP" value="USD_CLP_checkbox" <?php echo $selection_usd_clp;?>>美元/智利比索匯率<br>  
          <input type="checkbox" id="USD_PEN" name="USD_PEN" value="USD_PEN_checkbox" <?php echo $selection_usd_pen;?>>美元/祕魯新索爾匯率<br>
          <input type="checkbox" id="USD_CNY" name="USD_CNY" value="USD_CNY_checkbox" checked disabled>美元/人民幣匯率<br>  
          <input type="checkbox" id="USD_EURO" name="USD_EURO" value="USD_EURO_checkbox" checked disabled>美元/歐元匯率<br>
        </div>
    </div>  
    </div>

     <!-- 訓練資料期間 -->
     <div class="period_box">
      <p class = 'define_x'>訓練資料設定</p>
      <?php
        $query_date = "SELECT MAX(data_date) FROM `CPDSS_parameter`;" ;
        $result_date = mysqli_query($conn,$query_date);
        while($row = mysqli_fetch_array($result_date)){
            $max_date = $row['MAX(data_date)']; 
          }         
      ?>
      <div class= 'period' style="margin-top:20px;">
        <span style="margin-left: 10px;">＊訓練資料期間: 
        <!-- ✨✨✨ max-->
        <input type="date" id="start" name="start" min='2011-11-02' max='<?php echo $max_date; ?>' style="width: 120px;" value=<?php echo $last_row[4]; ?> > ～
        <!-- ✨✨✨ max-->
        <input type="date" id="end" name="end"  min='2011-11-02' max='<?php echo $max_date; ?>' style="width: 120px;" value=<?php echo $last_row[5]; ?>>
           <!-- ✨✨✨ -->
           <!-- js 當日日期 -> 刪除 script內容-->
           <br>
        <!-- ✨✨✨ -->
        <span style="margin-left: 20px;">說明：可選擇的期間為 2011-11-02～資料庫最新資料日期，現在最新資料日期為<?php echo $max_date;?>。
      </div>
    
    </div>
    </div>




    <!-- Select an appropriate model family -->
    <div class="settings_box">
      <p class = "settings"> 超參數設定 </p>
      <li style="margin-left:20px;">包含學習速率、學習目標、邊際誤差、optimizer和moving window的設定</li> 

      
      <!-- learning rate -->
      <?php 
      $selection_001 = "";
      $selection_0001 = "";
      $selection_00001 = "";
      if ($last_row[13]=="0.01") {
        $selection_001 = "selected";
      }elseif ($last_row[13]=="0.001") {
        $selection_0001 = "selected";
      }else{
        $selection_00001 = "selected";
      }
      ?>
      <div class= 'settings_content'>
          <span style="margin-left: 10px;">＊學習速率：
            <span style="margin-left: 10px;">
              <label for='learning_rate'></label>
            <select name="learning_rate" id="learning_rate">
            <option value="0.01" <?php echo $selection_001;?>>0.01</option>
            <option value="0.001" <?php echo $selection_0001;?>>0.001</option>
            <option value="0.0001" <?php echo $selection_00001;?>>0.0001</option>
            </select>
          <br>
          <span style="margin-left: 20px;">說明：當學習速率設定較小代表對神經網路進行較小權重更新，訓練會較慢且易導致過度擬合(意即無法順利預測未來資料)；而當數值設定過高時則容易導致模型震盪過大甚至無法收斂，亦無法順利預測未來資料。本系統提供三個建議之學習速率，使用者可透過不同嘗試，藉此找到最合適的學習速率。
        </div>

        <!-- learning goal -->
        <div class= 'settings_content'>
          <span style="margin-left: 10px;">＊學習目標： <br>
          <span style="margin-left:20px;">第一週<input type="number" max="10000" min="2000" id='learning_goal_1' style="margin-left:10px;" name='week_1' value=<?php echo $last_row[14]; ?>> 
          <span style="margin-left:20px;">第二週<input type="number" max="10000" min="2000" id='learning_goal_2' style="margin-left:10px;"name='week_2' value=<?php echo $last_row[15]; ?>> <br>
          <span style="margin-left:20px;">第三週<input type="number" max="10000" min="2000" id='learning_goal_3' style="margin-left:10px;" name='week_3' value=<?php echo $last_row[16]; ?>> 
          <span style="margin-left:20px;">第四週<input type="number" max="10000" min="2000" id='learning_goal_4' style="margin-left:10px;"name='week_4' value=<?php echo $last_row[17]; ?>><br>
          <span style="margin-left: 20px;">說明：當模型訓練結果達到預先設定之目標時便會停止訓練，意即學習目標可作為模型訓練的停止標準，不同的學習目標會影響需花費的訓練時間及模型結果，因此高度仰賴領域專家依照過往經驗設定合適的學習目標，藉此提升模型未來預測的準確度。因本模型之輸出為未來四週銅價，又因每週銅價波動極大，因此針對四週需分別設定不同的學習目標。<br>
        </div>

        <!-- 邊際誤差 -->
        <div class= 'settings_content'>
          <span style="margin-left: 10px;">＊邊際誤差（margin of error）：
          <span style="margin-left:2px;"><input type="number" max="10000" min="0" id='margin_of_error' style="margin:0px 5px;" name='margin_of_error' value=<?php echo $last_row[18]; ?>>人民幣<br>
          <span style="margin-left: 20px;">說明：可容忍之誤差範圍。<br>
        </div>
      

      
      <!-- optimizer -->
      <?php 
      $selection_adam = "";
      $selection_adadelta = "";
      $selection_SGD = "";
      if ($last_row[19]=="adam") {
        $selection_adam = "selected";
      }elseif ($last_row[19]=="adadelta") {
        $selection_adadelta = "selected";
      }else{
        $selection_SGD = "selected";
      }
      ?>
        <div class = 'settings_content'>
          <span style="margin-left: 10px;">＊Optimizer
            <span style="margin-left: 20px;"> 
            <label for="optimizer"></label>
            <select name="optimizer" id="optimizer">
            <option value="adam" <?php echo $selection_adam;?>>adam</option>
            <option value="adadelta" <?php echo $selection_adadelta;?> >adadelta</option>
            <option value="SGD" <?php echo $selection_SGD;?>>SGD</option>
            </select><br>
            
            <span id="points" style="margin-left:20px;">說明：選擇Optimizer以幫助神經網路調整參數，共有三種可選擇（adam、adadelta、SGD）</span>
           <div style="margin: 0 20px;"><p id="moreText">【adam】Adam梯度經過偏置校正後，每一次迭代學習率都有一個固定範圍，使得參數比較平穩。適用於大多非凸優化問題，即適用於大數據集和高維空間。<br>【adadelta】訓練初中期，加速效果不錯，很快。訓練後期，反覆在局部最小值附近抖動。<br>
              【SGD】最常見的optimozer，由於每次參數更新僅僅需要計算一個樣本的梯度，訓練速度很快，即使在樣本量很大的情況下，可能只需要其中一部分樣本就能迭代到最優解，由於每次迭代並不是都向着整體最優化方向，導致梯度下降的波動非常大，更容易從一個局部最優跳到另一個局部最優，準確度下降。<br>
            </p></div>
            <button type="button" style="margin-left:20px;" onclick="toggleText()" id="textButton">查看更多說明</button>
          <script>
              function toggleText() {
                  var points = document.getElementById("points");
                  var showMoreText = document.getElementById("moreText");
                  var buttonText = document.getElementById("textButton");
                  if (points.style.display === "none") {
                      showMoreText.style.display = "none";
                      points.style.display = "inline";
                      buttonText.innerHTML = "查看更多說明";
                  }else {
                      showMoreText.style.display = "inline";
                      points.style.display = "none";
                      buttonText.innerHTML = "收合";
                  }
              }
          </script>
           
      </div>
      
      <!-- moving window -->
      <div class = 'settings_content'>
        <div style="margin-left: 10px;">＊Moving Window </div>
        <span style="margin-left:20px;">Window Size<input type="number" max="300" min="100" id="window_size" style="margin-left:10px;" name='window_size' value=<?php echo $last_row[20]; ?>> （window的範圍，意即每次訓練涵蓋的資料筆數）<br>
        <span style="margin-left:20px;">Window Step<input type="number" max="50" min="1" id="window_step" style="margin-left:10px;"name='window_step' value=<?php echo$last_row[21] ; ?> > （test data的範圍，意即每次測試涵蓋的資料筆數）<br>
        <span style="margin-left:20px;">說明：為因應訓練長期資料，避免訓練效果受特定事件影響，將資料切割成片段訓練。由於紅銅價格的波動性極高，為了提升模型未來的預測準確度，我們在訓練過程中採用moving window的方式，利用window size設定每次欲訓練的資料範圍，並透過window step設定每次作為測試資料的筆數，並且每次window的測試資料皆不重複，如此迭代進行數次，直到訓練完成。例如當總資料筆數有100筆，而window size/window step分別設定為10/2時，則表示第一次訓練是由第1-10筆作為訓練資料，第11-12筆作為測試資料；第二次訓練則是由第3-12筆作為訓練資料，第13-14筆作為測試資料……以此類推。
     </div>
 
  </div>
 

    
     <div style="margin-top: 30px; margin-left: 20%;">
       <span style="margin-left:150px;">備忘錄：<input id="memo" name="memo" style="margin-left:10px; width: 300px;" value=<?php echo $last_row[3]; ?>>
      </div>
 

      
      <input type='submit' name="submit" value='開始訓練'class="button-30" style="font-size: 20px; margin: 20px 45%;" ></input>
    
    <!-- 輸入值 -->
      <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

          $date = date('Y-m-d h:i:s', time());
          $start = $_POST['start'];
          $end = $_POST['end'];
          if (empty($_POST['memo'])){
            $kw = ""; }else{ $kw = $_POST['memo']; }
          $model_no = "LDSS01-".date('y').date('m').date('d').date('his');


          if(isset($_POST['FX_broker_gold'])){ 
            $FX_broker_gold=1; }else{ $FX_broker_gold=0; }
          if(isset($_POST['FX_broker_silver'])){ 
            $FX_broker_silver=1; }else{ $FX_broker_silver=0; }
          if(isset($_POST['LME_nickel'])){ 
            $LME_nickel=1; }else{ $LME_nickel=0; }
          if(isset($_POST['LME_aluminum'])){ 
            $LME_aluminum=1; }else{ $LME_aluminum=0; }
          if(isset($_POST['LME_zinc'])){ 
            $LME_zinc=1;}else{ $LME_zinc=0; }
          if(isset($_POST['USD_CLP'])){ 
            $USD_CLP=1; }else{ $USD_CLP=0; }
          if(isset($_POST['USD_PEN'])){ 
            $USD_PEN=1; }else{ $USD_PEN=0; }      
          
          $learning_rate = filter_input(INPUT_POST, 'learning_rate');
          $learning_goal_1 = $_POST['week_1'];
          $learning_goal_2 = $_POST['week_2'];
          $learning_goal_3 = $_POST['week_3'];
          $learning_goal_4 = $_POST['week_4'];
          $margin_of_error = $_POST['margin_of_error'];
          $optimizer = filter_input(INPUT_POST, 'optimizer');
          $window_size = $_POST['window_size'];
          $window_step = $_POST['window_step'];


        $new_row = array($model_no, $date, 'user1',$kw,  $start, $end, '', '', '', '', '', '', 0, $learning_rate, $learning_goal_1, $learning_goal_2, $learning_goal_3, $learning_goal_4,$margin_of_error,$optimizer, $window_size, $window_step, 1, 1, 1, $FX_broker_gold,$FX_broker_silver, $LME_nickel, $LME_aluminum, $LME_zinc, 1, 1, 1, $USD_CLP, $USD_PEN, 1, 1);

        //寫進csv file
        $setting_file = fopen("../model1_volume/model_1_setting.csv", "a");
        fputcsv($setting_file, $new_row);
        fclose($setting_file);
        echo "<script>location.href = 'training_results.php';</script>";

        }

    ?>
    
    </form>


    
</div>
    
</body>





