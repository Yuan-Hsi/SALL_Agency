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
  letter-spacing:8px;
  font-size:120%;
  font-weight: 900;
  color: black;
  text-align:center;
  z-index: 3;
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
      <!--績效表-->
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
          <a href="model_management.php"><h2> Agent Management</h2></a>
          <a href="model_inference.php"><h2> Agent Inference</h2></a>
        </div>

      <div class="wrapper" style="margin-left: 30px; width:75%;  background: linear-gradient(to right, #ffffff, #fdfdfd);box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;border-radius: 5%;"><!--右選單-->
      <?php if(!isset($_GET['model_type'])&&!isset($_GET['agent_num'])){?> 
        <p>請選完模型後，再往下進行。<a href="demo.php">請點擊</a></p>
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


        <form class="form" action=<?php echo "data_analysis.php?model_type=".$_GET['model_type']."&agent_num=".$_GET['agent_num'] ?> method="post">
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
        <div calss = 'index' style ='width:15%;height:50px'>
          <div class = 'index_background' style="margin-left:2%;background-color: white;height:4%;width:5%;margin-top:2%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
          <div class = 'index_text' style="margin-left:0.4%;font-size:80%;margin-top:2.9%;width:8%;letter-spacing: 5px;">基本設定</div>
          </div>
        <div style="margin-right:3%;margin-top:7%;display:flex; flex-direction: column;align-items:center;box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;padding-right:15px;padding-top:15px">
        <label style = "margin:0; text-align: center;" > Agent Name </label>
          <input  type="text" name="agent_name" value="" class=agent_name style = "margin : 5%;padding-left: 10px;" placeholder="請設定策略名稱：" maxlength="8" required> <br>
          <!--<input type="radio" id="database" name="data" > &nbsp;網站資料庫資料 &nbsp; 空格-->
        <label style = "margin:3%;text-align: center;"> Item Selection </label>
          <input type="text" id="select_id" list="stock_list" name="sel_stock" onchange="stock_select()" style="padding-left: 10px;" placeholder="請輸入投資標的：">
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
            <span style="margin-left: 10px">＊選取資料期間
            <div> 從 <input type="date" id="start" name="start"  style="width: 120px;margin-top:10px" > </div>
            <div> 到 <input type="date" id="end" name="end"  style="width: 120px;" ></div>
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
          <div class = 'index_background' style="background-color: white;height:5%;width:9%;margin-top:1%;box-shadow: 8px -5px 3px 0px #cbced1;"></div>
          <div class = 'index_text' style="font-size:80%;margin-top:2%;width:8%;">欄位選擇</div>
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
        <div class = 'index_text' style="margin-top:0.9%;width:10%;">資料庫總覽</div>
        </div>

        <div id = 'preview' style = 'background : #cacbd4;margin: 0px,20px,20px,20px;height:450px;overflow: scroll;margin-bottom:3%'>
        <p id='hint' style = "text-align:center;letter-spacing:8px;font-size:120%;font-weight: 900;margin-top:13%"> 請設定投資標的 </p>
        <div id ='loading_spin' style ="display:none;align-items:center;margin-top:10%;flex-direction:column">
        <div class = 'spinner'></div>
        <p style = "text-align:center;letter-spacing:8px;font-size:80%;font-weight: 800;margin-top:3%"> 資料搜集中，請稍後 </p>
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
              


          </script>
      </div>
    
    </div>
  </body>
</html>
