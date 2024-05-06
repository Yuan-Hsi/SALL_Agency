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
    <title><?php echo "SALL Agency"; ?></title>
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
  color : #2e75b6;
  border: 4px solid #2e75b6; /* 資料搜集#ed7e30 資料分析#2e75b6 環境設定#548235 訓練#c31b00;*/
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
    background: linear-gradient(to left, #2e75b6 25%, #deeaf7 75%, #ffffff)
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
		border-left: 40px solid #2e75b6; /* 箭头长度*/
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
  color: black;
  text-align:center;
  z-index: 3;
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


.material{
    margin-top: 105%;
    transition-duration:0.5s;
  }

  .material:hover{
    color: #edc122;
    cursor:pointer;
  }

  #floating-window {
          display:none;
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


        #close-button {
          position: absolute;
          top: 10px;
          right: 10px;
        }

        #mask {
          display:none;
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

        }

        #mask {
          z-index: 4;
        }

        #floating-window {
          z-index: 6;
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

    <div class="section_3" style="background: linear-gradient(180deg, rgba(255,236,165,1) 0%, rgba(253,187,45,1) 100%);; height: 300px">
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
      <div style="margin-right:30px"> <h1 class ='progress_name' style = "">Data<br>Analysis</h1> 
      <div class="progress_content">
      <ul><li>Fill Missing Value</li> </ul>
            <p><nobr>0 / Average of three days around </nobr></p>
          <ul><li>Preprocessing</li> </ul>
            <p>Standardize</p>
            <p>Normalize</p>
            <p>Scaling</p>
      </div></div>
      <div class="arrow_sp"></div>
      </div>

      <div style= 'display:flex;align-items:center;margin:0 5% 0% 0%'>
      <div style="margin-right:30px"> <div style="display: flex;justify-content:center;"><h1 class ='unprogress_name' style = "">Environment<br>Setting</h1></div>
      <div class="unprogress_content" style="display:flex;justify-content:center;">
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
      <div class="arrow"></div>
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
        <a href="demo_b.php"><h2 style = "line-height: 1.5;"> Agent Building </h2></a>
          <ul class = "MLOps_list">
          <li>
                <p> Choose Your Agent </p>
            </li>
            <li>
                <p>Data Collection</p>
            </li>
            <li>
                <p><span> Data Analysis </span></p>
            </li>
            <li>
                <p>Environment Setting</p>
            </li>
            <li>
                <p>Agent Training</p>
            </li>
          </ul>
          <a href="model_management_b.php"><h2> Agent Management</h2></a>
          <a href="model_inference_b.php"><h2> Agent Inference</h2></a>
          <ul onclick='open_ppt()' class = "MLOps_list material" style='margin-left: 0%;list-style-type: none;text-align: center;'>
            <li>
                <p> TD3 Algorithm Tutorial </p>
            </li>
          </ul>
        </div>

      <div class="wrapper" style="margin-left: 30px; width:75%;  background: linear-gradient(to right, #ffffff, #fdfdfd);box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;border-radius: 5%;"><!--右選單-->
      <?php
      if(isset($_POST["fix"])){
        $_SESSION["agent"] = $_POST["fix"];
        $_SESSION["back"] = "collect";
        echo "<script> history.go(-2);</script>";
      }
      if(!isset($_GET['model_type'])&&!isset($_GET['agent_num'])){?> 
        <p>請選完模型後，再往下進行。<a href="demo.php">請點擊</a></p>
      <?php }?>
      <!-- 表單確認 -->
      <?php
        function get_out(){
          $mt = $_GET['model_type'];
          $an = $_GET['agent_num'];
          echo "<script> history.back();</script>";
         }

        // 確認表單內容
        if (isset($_POST["submit"])) {
          // 取得 form 中的 input
          $name = $_POST["agent_name"];
          // 確認 input 是否存在
          if (empty($name)) {
            // 顯示錯誤訊息
            echo "<script> alert('名稱空白，請重新輸入')</script>";
            get_out();
            // 阻止表單提交
            return false;
          }elseif(!isset($_SESSION["agent"])) { 
            $query = "SELECT * FROM `Agent_data` WHERE (`Account`='".$_SESSION['account']."' AND `Agent`='".$name."')";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            $line_count = 0;
            while($row = mysqli_fetch_array($result)){
              echo "<script> alert('名稱已被使用，請至管理頁面修正。謝謝！')</script>";
              get_out();
            return false;
            }
          }
          if(isset($_POST["parameters"])){
            $para = $_POST["parameters"];
            $word = implode (";", $para);
            $sql_word = "`年月日`,`".implode("`,`",$para)."`";
            if(!stripos($word,"價(元)")){
              echo "<script> alert('必須要有價格，四個可任選。謝謝')</script>";
              get_out();
              return false;
              }
          }
          if($_POST["sel_stock"] === "請選擇你主要想投資的項目："){
            echo "<script> alert('請選擇主要的投資標的，謝謝！')</script>";
            get_out();
            return false;
          }
          if($_POST["start"]>$_POST["end"]){
            echo "<script> alert('日期請由小到大，謝謝！')</script>";
            get_out();
            return false;
          }

        }

        // 進入資料庫
        
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

        ?>
        <form action='<?php echo $_SERVER['PHP_SELF'];?>' method="POST"> 
            <button class="button-small pure-button" type="submit" name="fix" id="test" style = "margin-top:2%;margin-left:3%"value= <?php echo $_POST["agent_name"]; ?> >往前一頁</button>
        </form>

        <div id="floating-window">
            <h1 style="text-align: center;font-weight:bolder;">模型訓練過程介紹</h1>
            <p style="text-align: center;margin-bottom:5px;margin-top:-5px">此投影片將講解 Agent 的學習過程與待會將使用到的參數介紹。
            </p>
            <iframe src="https://1drv.ms/p/c/0408fe3a7d3e9ba7/IQPNOOMkmZDKR6mmJ9xbz0BeAR3ATiWy1UnQ3EroTt4PM4w" width="1280" height="629" frameborder="0" scrolling="no"></iframe>
            <button id="close-button">關閉</button>
        </div>


        <div id="mask"></div>
        
        <script>
          var floatingWindow = document.getElementById("floating-window");
          var mask = document.getElementById("mask");

            function open_ppt(){
              floatingWindow.style.display='block';
              mask.style.display='block';
              document.body.style.overflow = "hidden";
            }

            // 關閉按鈕的點擊事件
            document.getElementById("close-button").onclick = function() {
              floatingWindow.style.display='none';
              mask.style.display='none';
              document.body.style.overflow = "auto";
            };
        </script>

      <div calss = 'index' style ='margin-left:2%;width:15%;height:25px'>
            <div class = 'index_background' style="background-color: white;height:5%;width:9.5%;margin-top:0.3%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
            <div class = 'index_text' style="font-size:80%;margin-top:1.4%;width:8%;word-spacing:3px;">Data Distribution</div>
            </div>
        
      <div class="setting_area" style="justify-content:flex-start;width:auto;padding-right:10px;" >
        <?php
            $code=array();
            $name=array();
        
            $query = "SELECT * FROM `code_table` ";
            $result = $conn_stock -> query($query) or die ($conn_stock -> connect_error);
            $line_count = 0;
            while($row = mysqli_fetch_array($result)){
              $line_count += 1;
              $push = array_push($code, $row["Code"]);
              $push = array_push($name, $row["Name"]);
            }
        ?>
        
        <div style = "overflow: scroll; width:1200px;height:165%;margin-right:30px"><!--原為 width:auto-->
            <div id ='loading_spin' style ="display:block;align-items:center;margin-top:10%;flex-direction:column">
            <div class = 'spinner' style="margin-left:40%;margin-bottom:7%"></div>
            <p style = "text-align:center;letter-spacing:8px;font-size:80%;font-weight: 800;margin-top:3%"> Data distribution loading... </p>
            </div>
            <table id = 'graph_table'>
            </table>
        </div>

        <form class="form" action="env_setting_b.php" method="post">
          <!-- <input type="radio" id="userdata" name="data" > &nbsp;上傳資料 -->
          <div style="margin-top:50px;  display: flex; flex-wrap: wrap; width:auto">
          <p style="width: 100%; margin-bottom:7%" id = 'total'> The amount of data：</p>
            <div style="margin:5px;margin-bottom:7%;width:250px">
            <div calss = 'index' style ='width:15%;height:25px'>
            <div class = 'index_background' style="background-color: white;height:5%;width:9%;margin-top:-1.6%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
            <div class = 'index_text' style="font-size:80%;margin-top:-0.2%;width:8%;word-spacing:3px;">Fill missing value</div>
            </div>
            <!--<p style="width: 100%; margin-bottom:7%" >遺失值處理方式：</p>-->
            <input type="radio" id="avg" name="missing[]" value="AVG_fill"  style="margin:5px" >&nbsp; Average around 3 days
            <br>
            <input type="radio" id="fill" name="missing[]" value="Custom_fill" checked="checked" style="margin:5px" >&nbsp; Filled by
            <input type="text" id="value" name="missing[]" value=0 style="margin:5px" required minlength="1" maxlength="8" size="10">
            </div>
            <div>
            <!--<p style="width: 100%; margin-bottom:7%" >資料前處理方式：</p>-->
            <div calss = 'index' style ='width:15%;height:25px'>
            <div class = 'index_background' style="background-color: white;height:5%;width:9%;margin-top:-1.6%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
            <div class = 'index_text' style="font-size:80%;margin-top:-0.2%;width:8%;word-spacing:3px;">Data preprocessing</div>
            </div>
            <div style="margin:5px"><input type="checkbox" name="process[]" value="Standardise">&nbsp; Standardize</div>
            <div style="margin:5px"><input type="checkbox" name="process[]" value="Normalize">&nbsp; Normalize</div>
            <div style="margin:5px"><input type="checkbox" name="process[]" value="Scaleing">&nbsp; Scaling</div>
            </div>
            <div style='margin-top:5px;'>
            <!--<p style="width: 100%; margin-bottom:7%; margin-left:3%" >主要的買賣價格使用：</p>-->
            <div calss = 'index' style ='width:15%;height:25px'>
            <div class = 'index_background' style="background-color: white;height:5%;width:9%;margin-top:-1.6%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
            <div class = 'index_text' style="font-size:80%;margin-top:-0.2%;width:8%;word-spacing:3px;">The buy/sell price</div>
            </div>
            <?php 
            $check = FALSE;
            if(stripos(' '.$word,"開盤")){
              $check = TRUE;
              echo "<input type='radio' id='avg' name='price[]' value='open' checked='checked' style='margin:5px' >&nbsp; 開盤價(元)";
            }
            if(stripos(' '.$word,"收盤")){
              if(!$check){echo "<input type='radio' id='avg' name='price[]' value='close' checked='checked' style='margin:5px' >&nbsp; 收盤價(元)"; $check = TRUE;}
              else{echo "<input type='radio' id='avg' name='price[]' value='close' style='margin:5px' >&nbsp; 收盤價(元)";}
            }
            if(stripos(' '.$word,"最高")){
              if(!$check){echo "<input type='radio' id='avg' name='price[]' value='high' checked='checked' style='margin:5px' >&nbsp; 最高價(元)"; $check = TRUE;}
              else{echo "<input type='radio' id='avg' name='price[]' value='high' style='margin:5px' >&nbsp; 最高價(元)";}
            }
            if(stripos(' '.$word,"最低")){
              if(!$check){echo "<input type='radio' id='avg' name='price[]' value='low' checked='checked' style='margin:5px' >&nbsp; 最低價(元)"; $check = TRUE;}
              else{echo "<input type='radio' id='avg' name='price[]' value='low' style='margin:5px' >&nbsp; 最低價(元)";}
            }
              ?>
            </div>
          </div>
          </div>
          <button type="submit" name="agent_name" value=<?php echo $_POST["agent_name"]?> style="background : gray ; width:40%; margin-left:30%" class="login_btn"> 完成資料設定 </button>
        </form>
        
        <div calss = 'index' style = 'width:15%;height:50px;margin-left:1.45%'>
        <div class = 'index_background' style="background-color: #cacbd4;width:13%;margin-top:0.1%;"></div>
        <div class = 'index_text' style="margin-top:1.8%;width:10%;"> Selected Data </div>
        </div>
        <div id = 'preview' style = 'background : #cacbd4;margin: 20px;height:450px;overflow: scroll;'>
        
        <!-- 預覽表格 -->

        <?php
        echo "<table>";
        $start = str_replace('-', '/', $_POST['start']);
        $end = str_replace('-', '/', $_POST['end']);
        $query = "SELECT {$sql_word} FROM `{$_POST["sel_stock"]}` WHERE `年月日` BETWEEN '{$start}' AND '{$end}' ";
        $result = $conn_stock -> query($query) or die ($conn_stock -> connect_error);
       echo "<p style='visibility: hidden;font-size: 1pt;' id='hidden-div' >$query</p>";
        // 新增表頭
        echo "<tr>";
        while ($field = $result->fetch_field()) {
          echo "<th>" . $field->name . "</th>";
        }
        echo "</tr>";

        // 取得資料
        
        while ($row = $result->fetch_row()) {
          echo "<tr>";
          for ($i = 0; $i < count($row); $i++) {
            echo "<td>" . $row[$i] . "</td>";
          }
          echo "</tr>";
        }

        // 結束表格
        echo "</table>";
        ?>
        </div>
  
        <script >
          /*
              var fix = document.getElementById('test');
              fix.addEventListener("click", function () {
                  history.back();
                });
              
                window.addEventListener("popstate", function (event) {
            alert("hohoho")
              });
              */
             // 取得 wrapper div 的寬度
              var wrapperWidth = document.querySelector(".wrapper").offsetWidth;

              // 設定 setting_area div 的寬度
              document.querySelector(".setting_area").style.width = wrapperWidth-30 + "px";

              async function show_graph() {
                    const hiddenDiv = document.getElementById('hidden-div');
                    console.log(hiddenDiv.textContent)
                    const api_url = 'http://140.119.19.81:6050/get_graph?query=' + hiddenDiv.textContent;
                    const response = await fetch(api_url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    });
                    const graph_set = await response.json();
                    console.log(graph_set['keys'][0]);
                    document.getElementById("loading_spin").style.display='none';
                    // 取得表格元素
                    var table = document.getElementById("graph_table");
                    const thead = table.createTHead();
                    var head_num = graph_set['keys'].length;
                    // 新增表頭
                    for (var i = 1; i < head_num; i++) {
                      var header = document.createElement("th");
                      header.textContent = graph_set['keys'][i];
                      thead.appendChild(header);
                    }
                    table.appendChild(thead)

                    var row = table.insertRow(-1);

                    for (var j = 1; j < head_num; j++) {
                      var cell = row.insertCell(-1);
                      var img = document.createElement('img');
                      img.style.width = '200px';
                      img.style.height = '150px';
                      img.src = graph_set[graph_set['keys'][j]];
                      cell.appendChild(img);
                      }

                      var row = table.insertRow(-1);

                    for (var j = 1; j < head_num; j++) {
                      var cell = row.insertCell(-1);
                      cell.innerHTML = "NULL : " + graph_set[graph_set['keys'][j]+"_N"] + "個 <br>\n" + "為 0 的共有 : " + graph_set[graph_set['keys'][j]+"_Z"] + "個";
                      }

                    var total = document.getElementById("total");
                    total.textContent = "The amount of data：" +graph_set['total_len'] ;
                    }

                    show_graph();
          </script>
      </div>
    
    </div>
  </body>
</html>

