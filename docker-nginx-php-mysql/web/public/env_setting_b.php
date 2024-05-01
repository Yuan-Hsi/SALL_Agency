<?php 
session_start();
header("Cache-Control:private");
function connection(){
    $conn=mysqli_connect("mysql", "root", "A!Lab502","AP"); 
    if(!$conn){
        die('could not connect:'.mysqli_connect_error());
    }
    return $conn;
  }
$conn = connection();

function connection_stock(){
  $conn_stock=mysqli_connect("mysql", "root", "A!Lab502","stock_data"); 
  if(!$conn_stock){
      die('could not connect:'.mysqli_connect_error());
  }
  return $conn_stock;
}
$conn_stock = connection_stock();

include '../app/vendor/autoload.php';
$foo = new App\Acme\Foo();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./styles/style.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css"
      integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls"
      crossorigin="anonymous"
    />
    <script src="codemirror-5.65.15/lib/codemirror.js"></script>
    <link rel="stylesheet" href="codemirror-5.65.15/lib/codemirror.css">
    <link rel="stylesheet" href="codemirror-5.65.15/theme/darcula.css">
    <script src="codemirror-5.65.15/mode/javascript/javascript.js"></script>
    <script src="codemirror-5.65.15/mode/python/python.js"></script>
    <title><?php echo "SALL Agency"; ?></title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
    $( function() {
    $( "#training_set-slider" ).slider({
      range: "min",
      value: 75,
      min: 1,
      max: 100,
      slide: function( event, ui ) {
        $( "#training_set" ).val(  ui.value + "%" );
      }
    });
    $( "#training_set" ).val( $( "#training_set-slider" ).slider( "value" ) + "%");
  } );
    </script>
  </head>

  <style>
    table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 2px solid #ddd;
}

th, td {
  text-align: left;
  padding: 8px;
}
</style>

  <body bgcolor="EDEDED" style="margin-left: 120px; margin-right: 120px">
    <div class="header">
      <a href="index.php"
        ><img
          src="./img/logo(2).png"
          alt="logo"
          style="width: 90.2px; height: 58.3px"
      /></a>
      <div
        class="pure-menu pure-menu-horizontal portal"
        style="margin-left: 20px"
      >
        <ul class="pure-menu-list">
          <li class="pure-menu-item">
            <a href="index.php" class="pure-menu-link"
              ><p class="portal_name">Home</p></a
            >
          </li>
          <li class="pure-menu-item">
            <a href="demo.php" class="pure-menu-link"
              ><p class="portal_name">Demo</p></a
            >
          </li>
          <li class="pure-menu-item">
            <a href="#" class="pure-menu-link"
              ><p class="portal_name">About me</p></a
            >
          </li>
          <li class="pure-menu-item">
            <a href="#" class="pure-menu-link"
              ><p class="portal_name">Contact</p></a
            >
          </li>
        </ul>
      </div>
      <div class="member-button" style='width:300px'>
        <?php
        if(isset($_SESSION['account']) && $_SESSION['account']!='Guest'){
        ?>
          <p class="login_portal" style='width:150px'><?php echo 'Hello! '.$_SESSION['account']?></p>
          <form action="demo.php?log_out=out" method="post">
            <button class="button-success pure-button">Log out</button>
          </form>
        <?php }else{ 
          $_SESSION['account']='Guest';
          ?>
          <p class="login_portal" style='width:150px' ><?php echo 'Hello! '.$_SESSION['account']?></p>
          <form action="index.php#enttrance">
          <button class="button-success pure-button">Sign up</button>
          </form>
        <?php } ?>
        <style>
          .button-success {
            color: white;
            width: 120px;
            height: 53px;
            border-radius: 8px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            background: #3164f4;
          }
        </style>
        <?php
        function user_logout(){
          if(isset($_SESSION['email']))echo "已將您登出";
          unset($_SESSION['email']);
          unset($_SESSION['account']);
      }
        ?>
      </div>
    </div>

    <div class="section_3" style="background: #eace5e; height: 300px">
    <div style= 'display:flex;align-items:center;margin:0 5% 0% 5%'>
      <div style="margin-right:30px"> <h1 class ='unprogress_name' >Data<br>Collection</h1>
      <div class = 'unprogress_content'>
        <ul><li>Initialization</li> </ul>
          <p>Agent Name</p>
          <p>Item Selection</p>
        <ul><li>Feature Selection</li> </ul>
      </div></div>
	    <div class="arrow" ></div>
      </div>
      
      <div style= 'display:flex;align-items:center;;margin:0 5% 0% 0%'>
      <div style="margin-right:30px"> <h1 class ='unprogress_name' style = "">Data<br>Analysis</h1> 
      <div class="unprogress_content">
      <ul><li>Fill Missing Value</li> </ul>
            <p><nobr>0 / Average of three days around </nobr></p>
          <ul><li>Preprocessing</li> </ul>
            <p>Standardize</p>
            <p>Normalize</p>
            <p>Scaling</p>
      </div></div>
      <div class="arrow"></div>
      </div>

      <div style= 'display:flex;align-items:center;margin:0 5% 0% 0%'>
      <div style="margin-right:30px"> <div style="display: flex;justify-content:center;"><h1 class ='progress_name' style = "">Environment<br>Setting</h1></div>
      <div class="progress_content" style="display:flex;justify-content:center;">
      <div >
      <ul><li><nobr>Basic Setting</nobr></li> </ul>
            <p><nobr>Length per Episode</nobr></p>
            <p>Invest Budget </p>
            <p>Random Seed </p>
      </div>
      <div style="margin-left:10%">
      <ul><li>Environment</li> </ul>
            <p style="font-weight:600">- Quick Mode -</p>
            <p>Selecting the reward function from lists. </p>
            <p style="font-weight:600">- Pro Mode -</p>
            <p>Setting by Coding ( OpenAI GYM Framework )</p>
      </div>
      </div></div>
      <div class="arrow_sp"></div>
      </div>

      <div style= 'display:flex;align-items:center;;margin:0 3% 0% 0%'>
      <div style="margin-right:30px"> <h1 class ='unprogress_name' style = "">Training</h1>  
      <div class="unprogress_content" style="display:flex;justify-content:center;">
      <div>
      <ul><li>Hyperparameters</li> </ul>
            <p>Batch Size</p>
            <p>Learning Rate</p>
            <p>Total Step</p>
            <p><nobr>Random explore steps</nobr></p>
            <p><nobr>Reward Discount Factor</nobr></p>
      </div>
      <div style ="margin-top:7%">
      <p><nobr>Exploration Noise</nobr></p>
      <p><nobr>Policy Noise</nobr></p>
      <p><nobr>Update after rounds</nobr></p>
      </div>
      </div></div>
      </div>
    </div>

    <div class="user_content" style="margin-top: 30px; margin-bottom: 100px">
      <!--Content-->

        <div class="menu" style="width: 18%; height: 700px; padding: 20px; padding-left: 40px"><!--左選單-->
        <a href="demo.php"><h2 style = "line-height: 1.5;"> Agent Building </h2></a>
          <ul class = "MLOps_list">
          <li>
                <p> Choose Your Agent </p>
            </li>
            <li>
                <p> Data Collection </p>
            </li>
            <li>
                <p>Data Analysis </p>
            </li>
            <li>
                <p><span>Environment Setting</span></p>
            </li>
            <li>
                <p>Agent Training</p>
            </li>
          </ul>
          <a href="model_management.php"><h2> Agent Management</h2></a>
          <a href="model_inference.php"><h2> Agent Inference</h2></a>
        </div>

      <div class="wrapper" style="margin-left: 30px; width:75%;height:1600px;   background: linear-gradient(to right, #ffffff, #fdfdfd);box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;border-radius: 5%;"><!--右選單-->
      <?php
      if(isset($_POST["fix"])){
        $_SESSION["agent"] = $_POST["fix"];
        echo "<script> history.go(-2);</script>";
      }?>
      <!-- 表單確認 -->
      <?php
        function get_out(){
          echo "<script> history.back();</script>";
         }

        // 確認表單內容
        if (isset($_POST["submit"])) {
          // 取得 form 中的 input
          $name = $_POST["agent_name"];
          }
        if($_POST["missing"][0]=="AVG_fill"){
          $set_missing = "`AVG_fill` = "."1".",`Custom_fill` = NULL";
        }
        else{
          $set_missing = "`Custom_fill` = ".$_POST["missing"][1].",`AVG_fill` = "."0";
        }
        if($_POST["price"][0]=="open"){
          $set_price = ",`買賣價` = '開盤價(元)'";
          $price_key = '開盤價(元)';
        }
        elseif($_POST["price"][0]=="close"){
          $set_price = ",`買賣價` = '收盤價(元)'";
          $price_key = '收盤價(元)';
        }
        elseif($_POST["price"][0]=="high"){
          $set_price = ",`買賣價` = '最高價(元)'";
          $price_key = '最高價(元)';
        }
        else{
          echo $_POST["price"][0];
          $set_price = ",`買賣價` = '最低價(元)'";
          $price_key = '最低價(元)';
        }
        if(isset($_POST["process"])){
          $process = $_POST["process"];
          $set_process = '';
          foreach ($process as &$value) {
            $set_process = $set_process.",`".$value."` = 1";

            $set_missing = $set_missing.$set_process;
        }
        unset($value);
        }
        else{
          $set_process = ",`Standardise` = 0,`Normalize` = 0,`Scaleing` = 0";
          $set_missing = $set_missing.$set_process;
        }
        
        $where = "`Account` = '".$_SESSION['account']."' AND `Agent` = '"."{$_POST["agent_name"]}'";
        $query = "UPDATE `Agent_data` SET ".$set_missing.$set_price." WHERE ".$where.";";
        $result = $conn -> query($query) or die ($conn -> connect_error);


        // 進入資料庫
        /*
        if(isset($_SESSION["agent"])){
          $query = "DELETE FROM `Agent_data` WHERE (`Agent` = '{$_SESSION["agent"]}' AND `Account` = '{$_SESSION['account']}')";
          $result = $conn -> query($query) or die ($conn -> connect_error);
          unset($_SESSION["agent"]);
        }

        $column_name=array();
        $query = "SELECT column_name FROM information_schema.columns WHERE table_name = '0050';";
        $result = $conn_stock -> query($query) or die ($conn_stock -> connect_error);
        $column_count = 0;
        while($row = mysqli_fetch_array($result)){
          if($row["COLUMN_NAME"]=='年月日'){
            continue;
          }
          $column_count += 1;
          $push = array_push($column_name, $row["COLUMN_NAME"]);
        }

        $para_bool = '1';
        $temp = 0;
        foreach($para as &$value){
          if($temp!=0){
            $para_bool = $para_bool.',1';
          }
          $temp++;
        }
        $query = "INSERT INTO `Agent_data` (Account, Model_type, Agent, Agent_num, Start_date,End_date,stock_num,`".implode ("`,`", $para)."`) VALUES ('{$_SESSION['account']}','{$_GET['model_type']}','{$name}','{$_GET['agent_num']}','{$_POST['start']}','{$_POST['end']}','{$_POST["sel_stock"]}',".$para_bool.")";
        $result = $conn -> query($query) or die ($conn -> connect_error);
        */
        ?>
        <style>
        #floating-window {
          position: fixed;
          left: 50%;
          top: 45%;
          transform: translate(-50%, -50%);
          width: 1280px;
          height: 720px;
          border: 1px solid black;
          border-radius: 5px;
          background-color: white;
          color: black;
        }

        #loading_window {
          position: fixed;
          left: 50%;
          top: 45%;
          transform: translate(-50%, -50%);
          width: 680px;
          height: 350px;
          border: 1px solid black;
          border-radius: 5px;
          background-color: white;
          color: black;
        }

        #close-button {
          position: absolute;
          top: 10px;
          right: 10px;
        }

        #mask {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: white;
          opacity: 0.5;
        }

        // 浮動視窗出現時，顯示遮罩
        #floating-window.active {
          #mask {
            display: block;
          }
        }
        body {
          z-index: 1;
          overflow: hidden;

        }

        #mask {
          z-index: 4;
        }

        #floating-window {
          z-index: 6;
        }
        
        #loading_window {
          z-index: 6;
        }

        .progress_name{
  line-height: 120%;
  word-spacing:8px;
  font-size:15pt;
  text-align:center;
  font-weight: 900;
  background-color: #ffffff;
  border-radius: 8px;
  width:175px;
  height:60px;
  display: flex;
  align-items:center;
  justify-content:center;
  color : #548235;
  border: 4px solid #548235; /* 資料搜集#ed7e30 資料分析#2e75b6 環境設定#548235 訓練#c31b00;*/
}

.unprogress_name{
  line-height: 120%;
  word-spacing:8px;
  font-size:15pt;
  text-align:center;
  font-weight: 900;
  background-color: #d0cece;
  border-radius: 8px;
  width:175px;
  height:60px;
  display: flex;
  align-items:center;
  justify-content:center;
  border: 4px solid #7f7f7f;
  color:#434242;
}

	.arrow {
		width: 60px;
		height: 20px;
		position: relative;
    background: linear-gradient(to right, #434343 25%, #626060 75%, #767171)
		/* transform: rotate(-40deg); */  /* 旋转角度 */
	}
	.arrow::after {
		content: '';
		display: block;
		position: absolute;
		right: -40px;  /* 箭头位置 */
		top: -8px;  /* 箭头位置 */
		border-top: 20px solid transparent; 	/* 箭头高低 */
		border-bottom: 20px solid transparent; /* 箭头高低 */
		border-left: 40px solid #767171; /* 箭头长度*/
  }

  .arrow_sp{
    width: 60px;
		height: 20px;
		position: relative;
    background: linear-gradient(to left, #548235 25%, #e2f0da 75%, #ffffff)
		/* transform: rotate(-40deg); */  /* 旋转角度 */
  }

	.arrow_sp::after {
		content: '';
		display: block;
		position: absolute;
		right: -40px;  /* 箭头位置 */
		top: -8px;  /* 箭头位置 */
		border-top: 20px solid transparent; 	/* 箭头高低 */
		border-bottom: 20px solid transparent; /* 箭头高低 */
		border-left: 40px solid #548235; /* 箭头长度*/
  }

  ul{
    list-style-type:disc;
    font-size:12pt;
    font-weight: 900;
    margin:5px;
  }

  .progress_content p{
    line-height: 150%;
    margin-left:3pt;
  }

  .unprogress_content p{
    line-height: 150%;
    margin-left:3pt;
  }

  .index{
  position: relative;
  z-index: 1;
}

.index_background{
  position: absolute;
  width: 100px;
  height: 70px;
  border-radius: 8px 10px 0px 0px;/* 设置圆角 */
  transform: perspective(8px)scale(1.1, 1.3) rotateX(5deg);
		/* 镜头距离元素表面的位置为8px，x轴为1.1倍y轴为1.3倍，绕x轴旋转5度 */
	transform-origin: bottom left;
		/* bottom left = left bottom = 0 100% 中心点偏移量*/
  z-index: 2;
}

.index_text{
  position: absolute;
  word-spacing:8px;
  font-size:120%;
  font-weight: 900;
  color: #525151;
  text-align:center;
  z-index: 3;
}

.index_text:hover {
    cursor: pointer;
}

.index_background:hover {
    cursor: pointer;
}


.spinner{
  height: 80px;
  width: 80px;
  border: 6px solid;
  border-color: black transparent black transparent;
  border-radius: 50%;
  animation: spin 1.3s ease infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.quick_mode p{
  font-size:15pt;
}

.quick_mode select{
  margin-left:5px;
  font-size:15pt;
  padding-left:10pt;
}

th,td{
  padding-left:15px;
}

th{
  text-align:center;
}


        </style>
        <div id="floating-window">
            <h1 style="text-align: center;font-weight:bolder;">模型訓練過程介紹</h1>
            <p style="text-align: center;margin-bottom:5px;margin-top:-5px">此投影片將講解 Agent 的學習過程與待會將使用到的參數介紹。
            </p>
            <iframe src="https://1drv.ms/p/c/0408fe3a7d3e9ba7/IQPNOOMkmZDKR6mmJ9xbz0BeAR3ATiWy1UnQ3EroTt4PM4w" width="1280" height="629" frameborder="0" scrolling="no"></iframe>
            <button id="close-button">關閉</button>
        </div>

        <div id="loading_window" style='display:none'>
        <div id ='loading_spin' style ="display:block;align-items:center;margin-top:10%;flex-direction:column">
            <div class = 'spinner' style="margin-left:42%;margin-top:13%"></div>
            <p style = "text-align:center;letter-spacing:8px;font-size:80%;font-weight: 800;margin-top:9%"> Environment Validating... </p>
            </div>
        </div>

        <div id="mask"></div>
        
        <script>
          floatingWindow = document.getElementById("floating-window");
          mask = document.getElementById("mask");
            // 關閉按鈕的點擊事件
            document.getElementById("close-button").onclick = function() {
              floatingWindow.remove();
              mask.style.display='none';
              document.body.style.overflow = "auto";
            };
        </script>
        <form action='<?php echo $_SERVER['PHP_SELF'];?>' method="POST"> 
            <button class="button-small pure-button" type="submit" name="fix" id="test" style = "margin-top:2%;margin-left:3%"value= <?php echo $_POST["agent_name"]; ?> >往前一頁</button>
        </form>

        <div>
        <form action="model_training_b.php" method="POST">
        <div style = 'display:flex;justify-content:flex-end;'>
        <button class="button-small pure-button" type="submit" name="agent_name" id="next" style = "margin-top:-3%; margin-right:5%; " value= <?php echo $_POST["agent_name"]; ?> disabled>往下一頁</button>
        </div>
        <div style='margin-top:2%;margin-left:3%;display:flex;align-items:center'>
        <div style='margin-right:5%'>
        <!--
        獎賞乘數 <input type="number" id="value" name="env[]" value=0.3 style="margin:5px" min="0" step = "0.00001" size="10">
        懲罰乘數 <input type="number" id="value" name="env[]" value=0.7 style="margin:5px" min="0" step = "0.00001"  size="10">
        -->
        每輪經過次數 <input type="number" id="times" name="env[]" value=90 style="margin:5px" min="0" step="1" max="1000" size="10" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
        總投資預算 <input type="number" id="value" name="env[]" value=1000000 style="margin:5px" min="0" step="1" max="100000000" size="10" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
        <!--
        <br>
        每次操作總股數 <input type="number" id="value" name="env[]" value=1000 style="margin:5px" min="0" step="1" max="1000000" size="15">
        定存利率 <input type="number" id="value" name="env[]" value=0.05 style="margin:5px" min="0" step = "0.00001"  max="1" size="10">
        手續費利率 <input type="number" id="value" name="env[]" value=0.05 style="margin:5px" min="0" step = "0.00001"  max="1" size="10">
          -->
        隨機種子 <input type="number" id="value" name="env[]" value=101 style="margin:5px" min="0" step="1" maxlength="8" size="10" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
        </div>
        <div>
        <p>
            <label for="training_set">訓練資料集佔比:</label>
            <input type="text" id="training_set" readonly style="border:0; color:#f6931f; font-weight:bold;">
          </p>
          <div style="width:100%" id="training_set-slider"></div>
          <br>
        </div>
      </div>
      </div>
                
          </form>
      
        
      <div style='margin-top:20px'>
      <div style='display:flex;margin-bottom:-8px'>
      <div calss = 'index' id='quick_index' style = 'width:15%;height:50px;margin-left:2.2%;z-index:3'>
        <div class = 'index_background' id='quick_window'style="background-color: #e5e5e9;width:13%;margin-top:0.1%;box-shadow: 8px -1px 3px 0px #b1b1b1;"></div>
        <div class = 'index_text' id='quick_text' style="margin-top:1.8%;width:10%;"> Quick Mode </div>
        </div>
      <div calss = 'index' id='pro_index' style = 'width:15%;height:50px;margin-left:2.5%;z-index:2'>
        <div class = 'index_background' id='pro_window' style="background-color: #cacbd4;width:13%;margin-top:0.1%;box-shadow: rgb(177, 177, 177) -5px -1px 3px 1px;"></div>
        <div class = 'index_text' id='pro_text'style="margin-top:1.8%;width:10%;"> Pro Mode </div>
      </div>
      
      <p style="text-align:right;width:500px;padding-top:32px"> * Please Choose ONE mode to develop.</p>
      </div>
      <div class='setting_area' id='quick_mode' style ='justify-content:flex-start;position:absolute;;z-index:3;width:auto'>
            <div class='quick_mode' style = 'justify-content:flex-start;flex-direction:column;width:93%;height:1250px;background:#e5e5e9;border-radius: 8px;padding:50px 0px 0px 50px'>
              <h1>
                Every transaction:
              </h1>
              <table style='margin-left:17%;margin-bottom:20px;border: 3px solid #d5d5d5;width:70%;'>
                <thead>
                <tr style='font-weight:700;'>
                  <th scope="col" style ='text-align:center;border-right:3px solid #d5d5d5'>Reward Function</th>
                  <th scope="col" style ='text-align:center'>Code Reference</th>
                </tr>
                </thead>
              <tbody>
                <tr>
                  <th scope="row" style='border-right:3px solid #d5d5d5'>Action Value</th>
                  <td> reward += self.actino[0] * <span style='font-style: italic;text-decoration: underline;'> your input</span></td>
                </tr>
                <tr>
                  <th scope="row" style='border-right:3px solid #d5d5d5;vertical-align: middle;'>Price Fluctuation (day)</th>
                  <td style='line-height:150%'>  buying action: reward += self.next_price - self.price* <span style='font-style: italic;text-decoration: underline;'> your input</span> <br>
                  selling action: reward += self.price - self.next_price* <span style='font-style: italic;text-decoration: underline;'> your input</span>  
                  </td>
                </tr>
                <tr>
                  <th scope="row" style='border-right:3px solid #d5d5d5'>None</th>
                  <td> reward += 0 * <span style='font-style: italic;text-decoration: underline;'> your input</span> </td>
                </tr>
              </table>
              <div style='display:flex;align-items:center'>
              <p>
                Choosing the reward function when <span style='font-weight:700'>BUYING</span>:
              </p>
              <select onchange="edit_buy_reward()" id='buy_reward'>
                <option value='action'>Action Value</option>
                <option value='price_fluctuation'> Price Fluctuation (day)</option>
                <option value='none'>None</option>
              </select> 
              <p>&nbsp Times &nbsp <input onchange="edit_buy_reward()" id='buy_reweard_times' type="text" id="times" name="env[]" value=1.00 style="margin:5px;margin:5px;text-align:center"  size="20" ></p>
            </div>
            <div style='display:flex;align-items:center'>
              <p>
                Choosing the reward function when <span style='font-weight:700'>SELLING</span>:
              </p>
              <select onchange="edit_sell_reward()" id='sell_reward'>
                <option value='action'>Action Value</option>
                <option value='price_fluctuation'> Price Fluctuation (day)</option>
                <option value='none'>None</option>
              </select> 
              <p>&nbsp Times &nbsp <input onchange="edit_sell_reward()" id='sell_reweard_times' type="text" id="times" name="env[]" value=1.00 style="margin:5px;margin:5px;text-align:center"  size="20" ></p>
            </div>                
              <h1 style='margin-top:5%'>
                Last transaction:
              </h1>
              <div style='display:flex;align-items:center'>
                <p>
                Calculate the whole investment profit over this rounds <p>&nbsp times &nbsp <input onchange="edit_total_reward()" id='total_reweard_times' type="text" id="times" name="env[]" value=2**-6 style="margin:5px;margin:5px;text-align:center" size="20" ></p>
                </p>
                </div>  
            <h1 style='margin-top:5%'>
                Constrain:
              </h1>
              <div style=''>
                <p style='line-height:150%;'>
                When the agent keeps doing an action that is not available (e.g. buying without having any money),<br>
                it will leave a "dumb" mark, which will only cease when the agent performs an available action.<br>
                And when the dumb is over 5 times, the penalty will be generated as:<br></p>
                <p style='margin-top:20px'>The number of dumb times &nbsp <input onchange="edit_penalty()" id='total_dumb_times' type="text" id="times" name="env[]" value=2**2 style="margin:5px;margin:5px;text-align:center" size="20" ></p>
                </p>
                </div>  
            <div style = "width:auto;height:auto;margin-right:30px;display:flex;justify-content:center;margin-top:50px">
            <button type="submit" class="button-small pure-button" name="agent_name" style="width:50%;background-color:#bfbfbf"value=<?php echo $_POST["agent_name"]?>  id="save">保存</button>
            </div>
            </div>
      </div>
        
      <div class="setting_area" style="position:relative;justify-content:flex-start;flex-direction:column;width:auto;padding-right:10px;z-index:2" >
      <div style="background: #cacbd4; margin-right:5%;padding:10px;margin-bottom:3%">
      <h1 style = "font-size:40pt; font-weight: bold;" id = head> SETTING ACTION REWARD</h1>
                        <p class = "text" > The action reward is important thing for reinforcement learning,  <br> If you have experience in Python, welcome to edit the following code.
                        <br>
                        In the following code, we provide some parameters for you to use: 
                        <br>
                        <br>
                        <div style="">
                          <div style= "margin-bottom:5pt"><b>self.X(np.float64[ ])</b>: current state - [features1,features2,...,buy_maximum,sell_maximum]</div>
                          <div style= "margin-bottom:5pt"><b>rd(int)</b>: the current locate number in the state array.</div>
                          <div style= "margin-bottom:5pt"><b>self.buy_maximum(int)</b>: how much stock we can get from the left money.</div>
                          <div style= "margin-bottom:5pt"><b>self.sell_maximum(int)</b>: the number of stock we hold.</div>
                          <div style= "margin-bottom:5pt"><b>self.price(float)</b>: the price in the state right now.</div>
                          <div style= "margin-bottom:5pt"><b>self.next_price(float)</b>: the price in the next state.</div>
                          <div style= "margin-bottom:5pt"><b>self.length(int)</b>: the number of steps remaining.</div>
                          <div style= "margin-bottom:5pt"><b>self.reward(np.float64[ ])</b>: the reward we need to calculate.</div>
                          <div style= "margin-bottom:5pt"><b>self.hold(bool)</b>: this time is hold or not.</div>
                          <div style= "margin-bottom:5pt"><b>self.hold_times(int)</b>: the times that the transaction been hold.</div>
                          <div style= "margin-bottom:5pt"><b>self.left_money(float)</b>: the money we left.</div>
                          <div style= "margin-bottom:5pt"><b>action(np.float64[ ])</b>: the action be sent in the function between -1 to 1. </div>
                        </div>
                        <br>
                        Enjoy your self-develop time.</p>
      </div>
      <select  onchange="change_env()" id='select_env' name="sel_env" style="width:95%">
            <option value=<?php echo $_POST["agent_name"] ?>> <?php echo $_POST["agent_name"] ?> </option>
            <?php            
            $agent = [];
            $query = "SELECT `Agent` FROM `Agent_data` WHERE `Account` = '".$_SESSION['account']."' AND  `Agent` != '".$_POST["agent_name"]."' ORDER BY `create_time`";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            $line_count = 0; // 有幾行 agent 
            while($row = mysqli_fetch_array($result)){
              $line_count += 1;
              $push = array_push($agent, $row);
            }
            $env_count = 0;
            while($env_count < $line_count){
              $filePath = "./custom_env/".$_SESSION['account']."_".$agent[$env_count][0].".py";
              echo "<script>console.log('{$agent[$env_count][0]}')</script>";
              if(file_exists($filePath)){
              ?>
              <option value=<?php echo $agent[$env_count][0] ?>> <?php echo $agent[$env_count][0]?> </option>
              <?php
            }
            $env_count ++;}
            ?>
          </select>
        <!--<form class="form" action="index.php" method="post"> -->
        <div id = 'coding_area' style = 'background : #cacbd4;height:1800px;overflow: scroll;width:95%'>

        <?php
        $filePath = "./custom_env/".$_SESSION['account']."_".$_POST['agent_name'].".py";
        if(file_exists($filePath)){
          $code = file_get_contents($filePath);
        }
        else{
          $code = file_get_contents("./env_set.py");
        }
        // 設定預設原始碼
        echo "<textarea id='coding_editor' name='code' style='height:1800px;width:100%'>$code</textarea>";
        ?>
        </div>
        <div style = "width:auto;height:auto;margin-right:30px;display:flex;justify-content:center">
        <button type="submit" class="button-small pure-button" name="agent_name" style="width:50%"value=<?php echo $_POST["agent_name"]?>  id="save">保存</button>
        </div>
        <!--</form>-->
        </div>
        <script >
        // 初始化編輯器
        var editor = CodeMirror.fromTextArea(document.getElementById("coding_editor"), {
          theme: "darcula",
          mode: "python",
          indentUnit: 4,
          lineNumbers:true,
        });

        /*
        // the line numbers to be "readonly"
        var readOnlyLines =[];
        for (let i = 0; i <= 1; i++) {
          readOnlyLines.push(i);
        }
        

        // listen for the beforeChange event, test the changed line number, and cancel
        editor.on('beforeChange',function(cm,change) {
            if ( ~readOnlyLines.indexOf(change.from.line) ) {
                change.cancel();
            }
        });
        */


        // 取得 wrapper div 的寬度
        var wrapperWidth = document.querySelector(".wrapper").offsetWidth;
        

        // 設定 setting_area div 的寬度
        document.querySelector(".setting_area").style.width = wrapperWidth-30 + "px";

        document.querySelector(".cm-s-darcula").style.height = "100%";
      
        var loading_window = document.getElementById("loading_window");
        // 添加按鈕事件處理程序
        document.getElementById("save").addEventListener("click", function() {
          // Assuming you have a button element with the id "myButton"

          var saveButton = document.getElementById("save");
          
          mask.style.display='block';
          loading_window.style.display='block';
          // Disable the button
          saveButton.disabled = true;
          // 保存編輯器內容
          editor.save();
          document.getElementById("coding_editor").textContent =  editor.getValue();
          env_setting();
          coding_test();
          
        });


        async function env_setting() {
            const api_url2 = 'http://140.119.19.81:6055/env_setting';
            const response2 = await fetch(api_url2, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
        			"code":document.getElementById("coding_editor").textContent,
              "account": '<?php echo $_SESSION['account']; ?>',
              "agent_name":'<?php echo $_POST['agent_name']; ?>'
        			})
            });
            const code_2 = await response2.json();
            console.log(code_2);
          }

        async function coding_test() {
            const api_url = 'http://140.119.19.81:6050/coding_test';
            const response = await fetch(api_url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
        			"code":document.getElementById("coding_editor").textContent,
              "account": '<?php echo $_SESSION['account']; ?>',
              "agent_name":'<?php echo $_POST['agent_name']; ?>',
              "price_key":'<?php echo $price_key; ?>',
              "train_potion":$( "#training_set-slider" ).slider( "value" ),
              "times":document.getElementById("times").value
        			})
            });
            const respond = await response.json();
            if(respond['text'] == 'pass'){
              alert("Validating Complete.");
              // Assuming you have a button element with the id "myButton"
              var myButton = document.getElementById("next");

              // Disable the button
              myButton.disabled = false;
              }
            else{
              alert(respond['text'] );
              
            }

            mask.style.display='none';
            loading_window.style.display='none';
            
            // Assuming you have a button element with the id "myButton"
            var saveButton = document.getElementById("save");

            // Disable the button
            saveButton.disabled = false;
          }
            
          async function change_env(){
            var agent = document.getElementById("select_env").value;
            var file_name = './custom_env/'+'<?php echo $_SESSION['account'] ?>'+'_'+agent+'.py';


            fetch(file_name)
            .then((response) => {
              if (!response.ok) {
                fetch('./env_set.py').then((response) => {
                  return response.text();
            })
                .then((text) => {
                 editor.getDoc().setValue(text);
              })
                throw new Error(`HTTP error: ${response.status}`);
              }
              return response.text();
            })
            .then((text) => {
              editor.getDoc().setValue(text);
                  })

          }


        </script>

        <script>

        var quick_index= document.getElementById("quick_index");
        var quick_window = document.getElementById("quick_window");
        var quick_text = document.getElementById('quick_text');
        var quick_mode = document.getElementById("quick_mode");
        
        var pro_index = document.getElementById("pro_index");
        var pro_window = document.getElementById("pro_window");
        var pro_text = document.getElementById('pro_text');

        pro_window.style.background='#ededed';
        pro_text.style.color='#a19f9f';
        pro_window.style.boxShadow='none';
        
        // 添加按鈕事件處理程序
        quick_window.addEventListener("click", call_quick);
        pro_window.addEventListener("click", call_pro);
        quick_text.addEventListener("click", call_quick);
        pro_text.addEventListener("click", call_pro);


        
        function call_quick() {
          pro_index.style.zIndex='2';
          pro_window.style.backgroundColor='#ededed';
          pro_window.style.boxShadow='none';
          pro_text.style.color='#a19f9f';

          quick_mode.style.display='flex';
          quick_index.style.zIndex='3';
          quick_window.style.backgroundColor='#e5e5e9';
          quick_window.style.boxShadow='8px -1px 3px 0px #b1b1b1';
          quick_text.style.color='#525151';
        };
        
        function call_pro(){
          quick_mode.style.display='none';
          quick_index.style.zIndex='2';
          quick_window.style.backgroundColor='#ededed';
          quick_window.style.boxShadow='none';
          quick_text.style.color='#a19f9f';

          pro_index.style.zIndex='3';
          pro_window.style.backgroundColor='#e5e5e9';
          pro_window.style.boxShadow='-5px -1px 3px 1px rgb(177, 177, 177)';
          pro_text.style.color='#525151';
        }
        


        </script>


        <script>

          function edit_buy_reward(){


            var option = document.getElementById("buy_reward").value;
            var times = document.getElementById("buy_reweard_times").value;
            var text = document.getElementById("coding_editor").textContent;
            var lines = text.split('\n');

            
            if(times.toString()===''){
                alert('Please check your input about times.');
                document.getElementById("buy_reweard_times").value = '1.00';
                return 0;
              }

            switch (option) {
              case 'action':
                lines[33] = lines[33].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 action[0]");
                  break;
              case 'price_fluctuation':
                lines[33] = lines[33].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 (self.next_price-self.price)");
                  break;
              case 'none':
                lines[33] = lines[33].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 0");
                  break;
              default:
                  alert('error check the edit_buy_reward function.');
          }
            lines[33] = lines[33]+'*'+times;
            var new_content = lines.join('\n');
            document.getElementById("coding_editor").textContent = new_content;

            editor.setValue(new_content);
          }

          function edit_sell_reward(){

            var option = document.getElementById("sell_reward").value;
            var times = document.getElementById("sell_reweard_times").value;
            var text = document.getElementById("coding_editor").textContent;
            var lines = text.split('\n');


            if(times.toString()===''){
                alert('Please check your input about times.');
                document.getElementById("sell_reweard_times").value = '1.00';
                return 0;
              }

            switch (option) {
              case 'action':
                lines[50] = lines[50].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 -1*action[0]");
                  break;
              case 'price_fluctuation':
                lines[50] = lines[50].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 (self.price-self.next_price)");
                  break;
              case 'none':
                lines[50] = lines[50].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 0");
                  break;
              default:
                  alert('error check the edit_sell_reward function.');
            }
            lines[50] = lines[50]+'*'+times;
            var new_content = lines.join('\n');
            document.getElementById("coding_editor").textContent = new_content;

            editor.setValue(new_content);
            }

            
        function edit_total_reward(){

        
        var times = document.getElementById("total_reweard_times").value;
        var text = document.getElementById("coding_editor").textContent;
        var lines = text.split('\n');


        if(times.toString()===''){
            alert('Please check your input about times.');
            document.getElementById("total_reweard_times").value = '0.0156';
            return 0;
          }

        lines[83] = lines[83].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 (self.left_money + self.sell_maximum * self.price - self.capital)");


        lines[83] = lines[83]+'*'+times;
        var new_content = lines.join('\n');
        document.getElementById("coding_editor").textContent = new_content;

        editor.setValue(new_content);
        }

        function edit_penalty(){

        var times = document.getElementById("total_dumb_times").value;
        var text = document.getElementById("coding_editor").textContent;
        var lines = text.split('\n');


        if(times.toString()===''){
            alert('Please check your input about times.');
            document.getElementById("total_dumb_times").value = '4.0000';
            return 0;
          }

        lines[88] = lines[88].replace(/(\+=|-+=)\s*(.+)\s*/, "$1 -1 * self.dumb");


        lines[88] = lines[88] +'*'+times;
        var new_content = lines.join('\n');
        document.getElementById("coding_editor").textContent = new_content;

        editor.setValue(new_content);
        }
        </script>
      </div>
    
    </div>
  </body>
</html>

