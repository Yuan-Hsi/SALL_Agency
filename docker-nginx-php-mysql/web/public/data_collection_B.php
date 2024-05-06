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

.spinner{
  height: 80px;
  width: 80px;
  border: 6px solid;
  border-color: white transparent white transparent;
  border-radius: 50%;
  animation: spin 1.3s ease infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.description{
  width: 0px;
  height: 25px;
  margin-left: 10px;
  margin-right:10px;
  background-color: #ededed;;
  padding-top:3px;
  text-align:center;
  line-height: 25px;
  border-radius: 8px;
  transition-duration:1s;
  transition-timing-function:linear;
  overflow: hidden;
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
  color : #ed7e30;
  border: 4px solid #ed7e30; /* 資料搜集#ed7e30 資料分析#2e75b6 環境設定#548235 訓練#c31b00;*/
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
    background: linear-gradient(to left, #ed7d31 25%, #fbe5d6 75%, #ffffff)
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
		border-left: 40px solid #ed7d31; /* 箭头长度*/
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

  .material{
    margin-top: 105%;
    transition-duration:0.5s;
  }

  .material:hover{
    color: #edc122;
    cursor:pointer;
  }

  .speech-bubble {
	position: absolute;
	background: #ffd746;
	border-radius: .4em;
  width:300px;
  height:50px;
  padding:10px 20px 20px 20px;
  box-shadow: 9px 8px 8px 0px #8b8989;
}

.speech-bubble:after {
	content: '';
	position: absolute;
	bottom: 0;
	left: 30%;
	width: 0;
	height: 0;
	border: 20px solid transparent;
	border-top-color: #ffd746;
	border-bottom: 0;
	border-left: 0;
	margin-left: -10px;
	margin-bottom: -20px;
}

.btn-close {
  position:absolute;
  z-index:2;
  margin: 0;
  border: 0;
  padding: 0;
  background: blue;
  border-radius: 50%;
  width: 25px;
  height: 25px;
  display: flex;
  flex-flow: column nowrap;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  transition: all 150ms;
  margin-left: 15.3%;
  margin-top: -0.3%;
}

.icon-cross{
  color:white;
  font-size:10pt;
  font-weight:700;
}

.basic_index .index_background{
    margin-top:2%;
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

    <div class="section_3" style="background: linear-gradient(180deg, rgba(255,236,165,1) 0%, rgba(253,187,45,1) 100%); height: 300px;display:flex">
      
      <div style= 'display:flex;align-items:center;margin:0 5% 0% 5%'>
      <div style="margin-right:30px"> <h1 class ='progress_name' style = "border: 4px solid #ed7e30;">Data<br>Collection</h1>
      <div class = 'progress_content'>
        <ul><li>Initialization</li> </ul>
          <p>Agent Name</p>
          <p>Item Selection</p>
        <ul><li>Features Selection</li> </ul>
      </div></div>
	    <div class="arrow_sp" ></div>
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
                <p><span> Data Collection <span></p>
            </li>
            <li>
                <p>Data Analysis</p>
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
          <div class='welcome' id='welcome_text' style='margin-top:75%;margin-left:50%;z-index:4'>
          <button type="button" class="btn-close" id='close_welcome' onclick='close_welcome()'>
            <span class="icon-cross">x</span>
          </button>
          <div class=speech-bubble>
            <p><span style='font-weight:700;line-height:150%'>Welcome to SALL Agency. </span><br>Here is some material for you to review the algorithm.</p>
          </div>
          </div>
          <ul onclick='open_ppt()' class = "MLOps_list material" style='margin-left: 0%;list-style-type: none;text-align: center;'>
            <li>
                <p> TD3 Algorithm Tutorial </p>
            </li>
          </ul>
        </div>

      <div class="wrapper" style="margin-left: 30px; width:75%;  background: linear-gradient(to right, #ffffff, #fdfdfd);box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;border-radius: 5%;"><!--右選單-->
      <?php if(!isset($_GET['model_type'])&&!isset($_GET['agent_num'])){?> 
        <p>請選完模型後，再往下進行。<a href="demo_b.php">請點擊</a></p>
      <?php }?>
      <div class="setting_area" >
        <?php
            $code=array();
            $name=array();
            $column_name=array();
        
            $query = "SELECT * FROM `code_table` ";
            $result = $conn_stock -> query($query) or die ($conn_stock -> connect_error);
            $line_count = 0;
            while($row = mysqli_fetch_array($result)){
              $line_count += 1;
              $push = array_push($code, $row["Code"]);
              $push = array_push($name, $row["Name"]);
            }

            $query = "SELECT column_name FROM information_schema.columns WHERE table_name = '0050'  ORDER BY ordinal_position;";
            $result = $conn_stock -> query($query) or die ($conn_stock -> connect_error);
            $column_count = 0;
            while($row = mysqli_fetch_array($result)){
              if($row["COLUMN_NAME"]=='年月日'){
                continue;
              }
              $column_count += 1;
              $push = array_push($column_name, $row["COLUMN_NAME"]);
            }
        ?>
        
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

        <form class="form" action=<?php echo "data_analysis_b.php?model_type=".$_GET['model_type']."&agent_num=".$_GET['agent_num'] ?> method="post">
        <div style = "display: flex;justify-content space-around;">
        <div style = "width:250px;margin-top:5%;margin-right:3%;margin-left:3%">
        <?php 
        $agent  = $_GET['agent_num'];
        switch ($_GET['model_type']) {
          case "TD3-LSTM":
            echo "<img src='https://api.dicebear.com/5.x/big-smile/svg?flip=true&size=192&seed={$agent}' />";
            $_SESSION['model'] = "TD3";
            break;
          case "A3C-LSTM":
            echo "<img src='https://api.dicebear.com/5.x/bottts/svg?flip=true&size=192&seed={$agent}' />";
            $_SESSION['model'] = "A3C";
            break;
          case "PPO-LSTM":
            echo "<img src='https://api.dicebear.com/5.x/micah/svg?flip=true&size=192&seed={$agent}' />";
            $_SESSION['model'] = "PPO";
            break;
          default:
            echo "請選完模型後，再往下進行。";
        }
        /*
          // 偵測網頁是否是透過回到上一頁的方式進來的
          echo $_SERVER['HTTP_REFERER'];
          if (stripos($_SERVER['HTTP_REFERER'],"data_analysis.php")) {
            echo "<script>console.log('work');</script>";
          } 
          */
           
          /*
          if(isset($_SESSION["agent"])){
            echo "<script>document.getElementById('period').style.display = 'block';</script>";
          }
          */
        ?>

        </div>
        <div class = 'basic_index' style ='width:15%;height:50px'>
          <div class = 'index_background' style="margin-left:2%;background-color: white;height:4%;width:5%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
          <div class = 'index_text' style="margin-left:0.4%;font-size:80%;margin-top:2.9%;width:8%;word-spacing: 5px;">Basic</div>
          </div>
        <div style="margin-right:3%;margin-top:7%;display:flex; flex-direction: column;align-items:center;box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;padding-right:15px;padding-top:15px">
        <label style = "margin:0; text-align: center;" > Agent Name </label>
          <input  type="text" name="agent_name" value="" class=agent_name style = "margin : 5%;padding-left: 10px;" placeholder="Type in here" maxlength="8" required> <br>
          <!--<input type="radio" id="database" name="data" > &nbsp;網站資料庫資料 &nbsp; 空格-->
        <label style = "margin:3%;text-align: center;"> Item Selection </label>
          <input type="text" id="select_id" list="stock_list" name="sel_stock" onchange="stock_select()" style="padding-left: 10px;" placeholder="Type in here">
          <!--<select  " id='select_id' name="sel_stock">-->
          <datalist id="stock_list" style="max-height:100px!important">
            <?php
              $stock_num = 0;
              while($stock_num < $line_count){
                ?>
                <option value=<?php echo $code[$stock_num]?> > <?php echo $name[$stock_num]?> </option>
                <?php
              $stock_num ++;}
            ?>
          </datalist>
          <div class= 'period' id='period' style="margin-top:20px; display: none;">
            <span >＊Set the period
            <div> From <input type="date" id="start" name="start"  style="width: 120px;margin-top:10px" > </div>
            <div > to<input type="date" id="end" name="end"  style="width: 120px;margin-left:17%" ></div>
              <!-- js 當日日期 -->
              <br>
            <!-- <span style="margin-left: 20px;">說明：可選擇的期間為 2015-01-07～資料庫最新資料日期，現在最新資料日期為<?php echo $max_date;?>。 -->
          </div>
        </div>
          <!-- <input type="radio" id="userdata" name="data" > &nbsp;上傳資料 -->
          <div style="margin-top:50px;  ">
          
          <div style="width=100%;display:flex;align-items:center;justify-content:space-between">
          <p style="width: 50%;">＊ 請仔細根據資料庫選擇。</p>
          <div id='description_box' class='description'>
          </div> 
          </div> 
          <div calss = 'index' style ='width:15%;height:50px'>
          <div class = 'index_background' style="background-color: white;height:5%;width:10%;margin-top:0.5%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
          <div class = 'index_text' style="font-size:80%;margin-top:2%;width:8%;">Features Selection</div>
          </div>
          <div style="display: flex; flex-wrap: wrap;margin-top:20px;box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white">
          <?php
          # 表格
          $col = 0;
          while($col < $column_count){
            $che = 0 ;
            if( $column_name[$col] == '開盤價(元)' or $column_name[$col] == '最高價(元)' or $column_name[$col] == '最低價(元)' or $column_name[$col] == '收盤價(元)' or $column_name[$col] == '成交量(千股)' or $column_name[$col] == '報酬率％'){$che = 1;}
            ?>
            <div style="margin:10px"><input type="checkbox"  name="parameters[]" value=<?php echo $column_name[$col] ?> <?php if ($che == 1){echo 'checked';} ?> > <span id='<?php echo $column_name[$col] ?>'>&nbsp;<?php echo $column_name[$col] ?></span></div>
            <?php
            $col++;
          }
          ?>
          </div>
          </div>
          </div>
          <input id="go_next" type="submit" name="submit" style="background : gray ; width:60%; margin-left:20%" class="login_btn" value="選定資料庫" disabled></input>
          <?php
          if(isset($_POST['agent_name'])){ 
            echo "<script> history.back(); </script>";
          }
            ?>
        </form>
        </div>
        
        <div calss = 'index' style ='width:15%;height:50px'>
        <div class = 'index_background' style="background-color: #cacbd4;width:13%;margin-top:-0.9%"></div>
        <div class = 'index_text' style="margin-top:0.9%;width:10%;">Data Preview</div>
        </div>

        <div id = 'preview' style = 'background : #cacbd4;margin: 0px,20px,20px,20px;height:450px;overflow: scroll;margin-bottom:3%'>
        <p id='hint' style = "text-align:center;letter-spacing:8px;font-size:120%;font-weight: 900;margin-top:13%"> Please set the invest item. </p>
        <div id ='loading_spin' style ="display:none;align-items:center;margin-top:10%;flex-direction:column">
        <div class = 'spinner'></div>
        <p style = "text-align:center;letter-spacing:8px;font-size:80%;font-weight: 800;margin-top:3%"> Data collecting... </p>
        </div>
        <div>
        <table id = 'stock_table' style="">
        </table>
        </div>
        <script >

              window.onload = function() {
                setTimeout(function() {
                  var stock_id = document.getElementById("select_id").value;
                if( stock_id != ''){
                  document.getElementById("period").style.display = "block";
                  stock_select();
                }
              }, 100);
              };

              function close_welcome(){
                document.getElementById("welcome_text").style.display='none';
              }
  
              async function stock_select() {

                  document.getElementById("stock_table").style.display='none';
                  document.getElementById("period").style.display = "none";
                  document.getElementById("hint").style.display='none';
                  document.getElementById("loading_spin").style.display='flex';
                  d = document.getElementById("select_id").value;

                  // 驗證中心
                  var options = document.querySelectorAll('#' + "stock_list" + ' option');
                  var validate = 0;
                  console.log(options[0].value);
                  for(var i = 0; i < options.length; i++) {
                      var option = options[i];
                      if(option.value === d) {
                          var validate = 1;
                          break;
                      }
                  }
                  if(validate == 0){
                    alert('查無此標的: '+d);
                    
                    document.getElementById("go_next").disabled = true;
                    document.getElementById("loading_spin").style.display='none';
                    document.getElementById("hint").style.display='block';
                    return;
                  }

                  // 開始
                  const api_url = 'http://140.119.19.81:6050/get_data?code=' + d;
            	    const response = await fetch(api_url, {
            		  method: 'POST',
                  headers: {
                      'Accept': 'application/json',
                      'Content-Type': 'application/json'
                  },
                  });
                    const data_set = await response.json();
                    console.log(data_set['年月日'][0]);
                
                    
                document.getElementById("loading_spin").style.display='none';
                document.getElementById("stock_table").style.display='block';

                  // 取得表格元素
                 var table = document.getElementById("stock_table");

                // 取得 `th` 元素
                const th = table.querySelector("thead");
                if (th !== null) {
                  table.removeChild(th);
                }
                const tb = table.querySelector("tbody");
                if (tb !== null) {
                  table.removeChild(tb);
                }
                 const thead = table.createTHead();
                 var head_num = data_set['head'].length
                  // 新增表頭
                  for (var i = 0; i < head_num; i++) {
                    var header = document.createElement("th");
                    header.textContent = data_set['head'][i];
                    thead.appendChild(header);
                  }
                  table.appendChild(thead)

                  for (var i = 0; i < data_set['年月日'].length; i++) {
                    var row = table.insertRow(-1);

                    for (var j = 0; j < head_num; j++) {
                      var cell = row.insertCell(-1);
                      cell.innerHTML = data_set[data_set['head'][j]][i];
                    }
                  }

                  document.getElementById("period").style.display = "block";
                  document.getElementById("go_next").disabled = false;
                  

                  const start_date = document.getElementById("start");
                  const end_date = document.getElementById("end");
                  start_date.setAttribute("min", data_set['min_date']);
                  start_date.setAttribute("max", data_set['max_date']);
                  start_date.setAttribute("value", data_set['min_date']);
                  end_date.setAttribute("min", data_set['min_date']);
                  end_date.setAttribute("max", data_set['max_date']);
                  end_date.setAttribute("value", data_set['max_date']);
                }

              function update_table(){
                // 取得表格元素
                var table = document.getElementById("stock_table");
                // 刪除表格
                table.parentNode.removeChild(table);
              }

              
          </script>

          <!-- description -->
          <script>

              var arr = ["開盤價(元)","收盤價(元)","最高價(元)","最低價(元)","成交量(千股)","成交值(千元)","報酬率％","週轉率％","成交筆數(筆)","外資買賣超(千股)","投信買賣超(千股)",
              "自營買賣超(千股)","合計買賣超(千股)","融資餘額(張)","融資買進(張)","融資賣出(張)","融券餘額(張)","融券買進(張)","融券賣出(張)","本益比-TSE","股價淨值比-TSE","股利殖利率-TSE"];
              var des = [
                "在一個集中市場交易日開始時，首次的交易價格。",
                "在一個集中市場交易日結束時，最後一次的交易價格。",
                "在一個集中市場交易日中，最高的交易價格。",
                "在一個集中市場交易日中，最低的交易價格。",
                "在一個集中市場交易日中，交易的總股數。",
                "在一個集中市場交易日中，交易的總價值。",
                "股票在集中市場交易日中的價格變化百分比。",
                "成交量與總股本的比率，用來衡量股票的流動性。",
                "在一個交易日中，股票交易的總筆數。",
                "外國投資者在一個交易日中操作的股票數量。",
                "投資信託公司在一個交易日中操做的股票數量。",
                "自營商在一個交易日中操作股票數量",
                "外資、投信和自營商在一個交易日中操作的股票總量",
                "投資者借入資金進行股票交易的未清償金額。",
                "投資者透過借入資金買進的股票數量。",
                "投資者透過借入資金式賣出的股票數量。",
                "投資者借入股票進行融券交易的未清償股數。",
                "投資者透過借入股票方式買進的股票數量。",
                "投資者透過借入股票方式賣出的股票數量。",
                "股票市價與每股盈利 (總利潤/總股本) 之比率。",
                "股票市價與每股淨值 (總資產/總股本) 之比率。",
                "每股股利與股票市價之比率，用來衡量投資回報率。"
              ]
              var box = document.getElementById("description_box");
              for (let i = 0; i < arr.length; i++) {
                var element = document.getElementById(arr[i]);
                element.addEventListener("mouseover", event => {
                box.innerText = des[i]
                box.style.width='100%';
                
              });
                element.addEventListener("mouseout", event => {
                  box.style.width='0%';
                });
              }
              
              var screenWidth = window.innerHeight;
console.log("當前視窗寬度：" + screenWidth + "px");


          </script>
      </div>
    
    </div>
  </body>
</html>
