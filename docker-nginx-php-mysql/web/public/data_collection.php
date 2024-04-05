<?php 
session_start();
header("Cache-Control:private");
function connection(){
    $conn=mysqli_connect("mysql", "root", "root","AP"); 
    if(!$conn){
        die('could not connect:'.mysqli_connect_error());
    }
    return $conn;
  }
$conn = connection();

function connection_stock(){
  $conn_stock=mysqli_connect("mysql", "root", "root","stock_data"); 
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
            echo "ohohoho";
          } 
           */
          if(isset($_SESSION["agent"])){
            echo "<script>document.getElementById('period').style.display = 'block';</script>";
          }
         
        ?>

        </div>
        <div style="margin-right:3%;margin-top:7%;display:flex; flex-direction: column;align-items:center">
        <label style = "margin:0; margin-left:55%"> Agent Name </label>
          <input  type="text" name="agent_name" value="" class=agent_name style = "margin : 5%"> <br>
          <!--<input type="radio" id="database" name="data" > &nbsp;網站資料庫資料 &nbsp; 空格-->
          <select  onchange="stock_select()" id='select_id' name="sel_stock">
            <option>請選擇你主要想投資的項目：</option>
            <?php
              $stock_num = 0;
              while($stock_num < $line_count){
                ?>
                <option value=<?php echo $code[$stock_num]?> > <?php echo $code[$stock_num] . $name[$stock_num]?> </option>
                <?php
              $stock_num ++;}
            ?>
          </select>
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
          <div style="margin-top:50px;  display: flex; flex-wrap: wrap;">
          <p style="width: 100%;">＊ 請仔細根據表格選擇。</p>
          <?php 
          $col = 0;
          while($col < $column_count){
            $che = 0 ;
            if( $column_name[$col] == '開盤價(元)' or $column_name[$col] == '最高價(元)' or $column_name[$col] == '最低價(元)' or $column_name[$col] == '收盤價(元)' or $column_name[$col] == '成交量(千股)' or $column_name[$col] == '報酬率％'){$che = 1;}
            ?>
            <div style="margin:5px"><input type="checkbox"  name="parameters[]" value=<?php echo $column_name[$col] ?> <?php if ($che == 1){echo 'checked';} ?> >&nbsp;<?php echo $column_name[$col] ?></div>
            <?php
            $col++;
          }
          ?>
          </div>
          </div>
          <input type="submit" name="submit" style="background : gray ; width:60%; margin-left:20%" class="login_btn" value="選定資料庫"></input>
          <?php
          if(isset($_POST['agent_name'])){ 
            echo "<script> history.back(); </script>";
          }
            ?>
        </form>
        </div>

        <div id = 'preview' style = 'background : #cacbd4;margin: 20px;height:450px;overflow: scroll;'>
        <table id = 'stock_table' style="">
        </table>
        </div>
        <script >
          

              $('input[type=radio][id=database]').change(function(){
              var var1 = $(this).val();
              document.getElementById("var1").value=var1;
              svar1=String(var1);
              //alert(svar1.length);
              if((svar1.length>0)){
                document.getElementById("var1").disabled="disabled";
              }else if ((svar1.length==0)){
                document.getElementById("var1").disabled= false;
              }
              });

  
              async function stock_select() {

                  d = document.getElementById("select_id").value;
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
      </div>
    
    </div>
  </body>
</html>
