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
    <script src="codemirror-5.65.15/lib/codemirror.js"></script>
    <link rel="stylesheet" href="codemirror-5.65.15/lib/codemirror.css">
    <link rel="stylesheet" href="codemirror-5.65.15/theme/bespin.css">
    <script src="codemirror-5.65.15/mode/javascript/javascript.js"></script>
    <script src="codemirror-5.65.15/mode/python/python.js"></script>
    <title><?php echo "SALL Agency"; ?></title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
    $( function() {
      $( "#max_timesteps-slider" ).slider({
        range: true,
        min: 1000,
        max: 1000000,
        values: [ 80000, 100000 ],
        slide: function( event, ui ) {
          $( "#max_timesteps" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] + " times");
        }
      });
      $( "#max_timesteps" ).val(  $( "#max_timesteps-slider" ).slider( "values", 0 ) +
        " - " + $( "#max_timesteps-slider" ).slider( "values", 1 ) + " times");
    } );
    $( function() {
      $( "#start_timesteps-slider" ).slider({
        range: true,
        min: 1000,
        max: 100000,
        values: [ 20000, 30000 ],
        slide: function( event, ui ) {
          $( "#start_timesteps" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] + " times");
        }
      });
      $( "#start_timesteps" ).val(  $( "#start_timesteps-slider" ).slider( "values", 0 ) +
        " - " + $( "#start_timesteps-slider" ).slider( "values", 1 ) + " times");
    } );
    $( function() {
      $( "#discount-slider" ).slider({
        range: true,
        min: 0,
        max: 100,
        values: [ 90, 95 ],
        slide: function( event, ui ) {
          $( "#discount" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] + " %");
        }
      });
      $( "#discount" ).val(  $( "#discount-slider" ).slider( "values", 0 ) +
        " - " + $( "#discount-slider" ).slider( "values", 1 ) + " %");
    } );
    $( function() {
      $( "#expl_noise-slider" ).slider({
        range: true,
        min: 0,
        max: 100,
        values: [ 5, 10 ],
        slide: function( event, ui ) {
          $( "#expl_noise" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] + " %");
        }
      });
      $( "#expl_noise" ).val(  $( "#expl_noise-slider" ).slider( "values", 0 ) +
        " - " + $( "#expl_noise-slider" ).slider( "values", 1 ) + " %");
    } );
    $( function() {
      $( "#policy_noise-slider" ).slider({
        range: true,
        min: 0,
        max: 100,
        values: [ 20, 30 ],
        slide: function( event, ui ) {
          $( "#policy_noise" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] + " %");
        }
      });
      $( "#policy_noise" ).val(  $( "#policy_noise-slider" ).slider( "values", 0 ) +
        " - " + $( "#policy_noise-slider" ).slider( "values", 1 ) + " %");
    } );
    $( function() {
      $( "#tau-slider" ).slider({
        range: true,
        min: 0,
        max: 100,
        values: [ 1, 5 ],
        slide: function( event, ui ) {
          $( "#tau" ).val( "" + ui.values[ 0 ] + " - " + ui.values[ 1 ] + " ‰");
        }
      });
      $( "#tau" ).val(  $( "#tau-slider" ).slider( "values", 0 ) +
        " - " + $( "#tau-slider" ).slider( "values", 1 ) + " ‰");
    } );
    $( function() {
    $( "#actor_lr-slider" ).slider({
      value:3,
      min: 0,
      max: 9,
      step: 1,
      slide: function( event, ui ) {
        $( "#actor_lr" ).val( "1e-" + ui.value );
      }
    });
    $( "#actor_lr" ).val("1e-" + $( "#actor_lr-slider" ).slider( "value" ) );
  } );
  $( function() {
    $( "#target_lr-slider" ).slider({
      value:3,
      min: 0,
      max: 9,
      step: 1,
      slide: function( event, ui ) {
        $( "#target_lr" ).val( "1e-" + ui.value );
      }
    });
    $( "#target_lr" ).val("1e-" + $( "#target_lr-slider" ).slider( "value" ) );
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

.for_more {
   width: 20px;
   height: 20px;
   border-radius: 50%;
   background-color: #ffffff;
   border-width:1px;
   border-style:solid;
   border-color:#797979;
   color: #797979;
   text-align: center;
   line-height: 20px;
   position: relative; /* Add this */
   font-size: 0.5em;}


.for_more .tooltip {
   width:300pt;
   visibility: hidden;
   position: absolute;
   background-color: #ededed;
   color: black;
   text-align: center;
   border-radius: 0.25em;
   padding: 0.25em 0.5em;
   z-index: 1;
   top: -10%;
   left: 150%;
   transition: visibility 0.1s;
}

.for_more:hover .tooltip {
   visibility: visible;
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
                <p> Data Collection </p>
            </li>
            <li>
                <p>Data Analysis </p>
            </li>
            <li>
                <p>Environment Setting</p>
            </li>
            <li>
                <p><span>Agent Training</span></p>
            </li>
          </ul>
          <a href="#"><h2> Agent Management</h2></a>
          <a href="#"><h2> Agent Inference</h2></a>
        </div>

      <div class="wrapper" style="margin-left: 30px; width:75%;height:1200px;   background: linear-gradient(to right, #ffffff, #fdfdfd);box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;border-radius: 5%;"><!--右選單-->
      <?php
      if(isset($_POST["fix"])){
        $_SESSION["agent"] = $_POST["fix"];
        echo "<script> sessionStorage.setItem('reloading', 'true'); history.go(-2);</script>";
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
        if(isset($_POST["env"])){
          $reward_driver = "`reward_driver` = '0.3',";
          $punish_driver = "`punish_driver` = '0.7',";
          $length = "`length` = '".$_POST["env"][0]."',";
          $invest_budget = "`invest_budget` = '".$_POST["env"][1]."',";
          $stock_amount = "`stock_amount` = '1000',";
          $interest_rate = "`interest_rate` = '0.05',";
          $fee_rate = "`fee_rate` = '0.05',";
          $seed = "`seed` = '".$_POST["env"][2]."'";
        }
        unset($value);
        

        $where = "`Account` = '".$_SESSION['account']."' AND `Agent` = '"."{$_POST["agent_name"]}'";
        $query = "UPDATE `Agent_data` SET ".$reward_driver.$punish_driver.$invest_budget.$length.$stock_amount.$interest_rate.$fee_rate.$seed. " WHERE ".$where.";";
        $result = $conn -> query($query) or die ($conn -> connect_error);
        ?>
        <div>
        <form action='<?php echo $_SERVER['PHP_SELF'];?>' method="POST" > 
            <button class="button-small pure-button" type="submit" name="fix" id="test" style = "margin-top:2%;margin-left:3%"value= <?php echo $_POST["agent_name"]; ?> >往前一頁</button>
        </form>
        <form action='<?php echo $_SERVER['PHP_SELF'];?>' method="POST">
        <div style = 'display:flex;justify-content:flex-end;'>
        <button class="button-small pure-button" type="submit" name="fix" id="test" style = "margin-top:-3%; margin-right:5%; " value= <?php echo $_POST["agent_name"]; ?> >前往管理頁面</button>
        </div>
        <div >
        <br>
        <h1 style="margin-left:0%;display:flex;justify-content:center;margin-bottom:5%"> - 超參數調整設定 - </h1>
        <div class='hyper-setting' style="display:flex; flex-direction:row;margin-left:3%">
        <div style='margin-right: 5%'>
        <p>
          <div style = 'display:flex;flex-direction:row;justify-content:space-between;width:150%'><label for="max_timesteps">總共訓練步數 :</label> <div class="for_more" >!<span class="tooltip">總共可交易的次數</span></div></div>
          <input type="text" id="max_timesteps" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="max_timesteps-slider"></div>
        <br>
        <p>
          <div style = 'display:flex;flex-direction:row;justify-content:space-between;width:150%'><label for="start_timesteps">隨機探索步數 :</label><div class="for_more">!<span class="tooltip">代理人一開始對於各個狀態會先隨機做動作，不按照目前模型結果，<br>進而先拓展各種可能。</span></div></div>
          <input type="text" id="start_timesteps" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="start_timesteps-slider"></div>
        <br>
        <p>
          <div style = 'display:flex;flex-direction:row;justify-content:space-between;width:150%'><label for="discount">報酬遞減因子(γ) :</label><div class="for_more">!<span class="tooltip">報酬遞減因子控制者代理人偏重眼前的獎勵,還是願意為了遠大的目標作出短期的犧牲。如果γ越接近0,代理人則更關心當下的奬勵，而短視近利。</span></div></div>
          <input type="text" id="discount" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="discount-slider"></div>
        <br>
        <p>
          <label for="actor_lr">Actor 模型學習率 :</label>
          <input type="text" id="actor_lr" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="actor_lr-slider"></div>
        </div>
        <div style="margin-left:10%">
        <p>
          <div style = 'display:flex;flex-direction:row;justify-content:space-between;width:150%'><label for="expl_noise">探索動作噪訊 :</label><div class="for_more">!<span class="tooltip">對於代理人所決定的當前動作，將會從常態分配(μ:0,σ:噪訊值)中抽樣，<br>加入當前動作，幫助代理人在當前的動作增加探索機會</span></div></div>
          <input type="text" id="expl_noise" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="expl_noise-slider"></div>
        <br>
        <p>
          <div style = 'display:flex;flex-direction:row;justify-content:space-between;width:150%'><label for="policy_noise">代理人動作噪訊 :</label><div class="for_more">!<span class="tooltip">對於下個狀態的新動作，將會從高斯分佈中取樣探索動作造訊之後，<br>加到原本的新動作上，幫助代理人在下一步的動作增加探索機會</span></div></div>
          <input type="text" id="policy_noise" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="policy_noise-slider"></div>
        <br>
        <p>
          <div style = 'display:flex;flex-direction:row;justify-content:space-between;width:150%'><label for="tau">目標模型每輪更新率 :</label><div class="for_more">!<span class="tooltip">代理人會從後向傳播法進行更新，而目標模型則會透過代理人 × 更新率，<br>加上原本代理人 × ( 1 - 更新率 ) 來進行更新</span></div></div>
          <input type="text" id="tau" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="tau-slider"></div>
        <br>
        <p>
          <label for="target_lr">Crtic 模型學習率 :</label>
          <input type="text" id="target_lr" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div style="width:150%" id="target_lr-slider"></div>
        <br>
        </div>
        <div style="margin-left:20%;display:flex;justify-content:center;flex-direction:column">
        <div>
        <span style="font-size: 15px;">批量訓練數量 :</span> <input type="number" id="batch_size" name="batch_size" value=32 style="margin:5px" min = "1" step ="1" max = "10000" size="6" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
        <br>
        </div>
        <div>
        <span style="font-size: 15px;">幾輪過後，更新代理人策略 :</span> <input type="number" id="update_round" name="update_round" value=3 style="margin:5px" min = "1" step ="1" max = "10000" size="6" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
        <br>
        </div>
        <div>
        <span style="font-size: 15px;">多少步後，回傳一次評估 :</span><input type="number" id="evaluate_step" name="evaluate_step" value=5000 style="margin:5px"  min = "1" step ="1" max = "1000000000" size="6" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
        <br>
        </div>
        <div>
        <span style="font-size: 15px;">超參數自動調整測試次數 :</span><input type="number" id="test_times" name="test_times" value=3 style="margin:5px"  min = "1" step ="1" max = "50" size="6" onkeydown="if(event.key==='.'){event.preventDefault();}"  oninput="event.target.value = event.target.value.replace(/[^0-9]*/g,'');">
        </div> 
        <button class="button-small pure-button" type="button" name="train" id="train" style = "margin-top:5%; margin-right:5%;" value= <?php echo $_POST["agent_name"]; ?> >開始訓練</button>
        <button class="button-small pure-button" type="button" name="end" id="end" style = "margin-top:5%; margin-right:5%;" value= <?php echo $_POST["agent_name"]; ?> disabled onclick="cut()">中止訓練</button>
      </div>
        </div>
      </div>          
          </form>
        
      <div class="setting_area" style="justify-content:flex-start;flex-direction:row;width:auto;padding-right:10px;" >
      
        <!--<form class="form" action="index.php" method="post"> -->
      <div id = 'log' style = 'padding-top:2%;padding-left:4%;background : #cacbd4;height:500px;overflow: scroll;width:50%'>

      </div>
      <style>
        table, th, td {
          border:1px solid #cacbd4;
          vertical-align: middle;
          text-align: center;
          letter-spacing: 2px;
          line-height: 1.2;
        }
      </style>
      <div style="margin-left:20px" class="evaluation_result" >
        <table border="1" style="">
          <thead>
            <tr>
              <th colspan="3">超參數測試投資報酬率: <span id = "performance">-</span></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="3"><img src="" alt="績效圖片" id = "performance_img" width="450" height="350"></td>
            </tr>
            <tr>
              <td> 總共訓練步數 <br><span id = "table_total">-</span>步</td>
              <td> 隨機探索步數 <br><span id = "table_random">-</span>步</td>
              <td> 報酬遞減因子 <br><span id = "table_discount">-</span>%</td>
            </tr>
            <tr>
              <td> 探索動作噪訊 <br><span id = "table_expl">-</span>%</td>
              <td> 代理人動作噪訊 <br><span id = "table_action">-</span>%</td>
              <td> 目標模型更新率 <br><span id = "table_tau">-</span>%</td>
            </tr>
          </tbody>
        </table>
      </div>

      </div>
      </div>

      <script>
        var training = false;
        /*
        async function logging(){
          const socket = new WebSocket("ws://localhost:6055/training_log"); // 替换成你的服务器地址

          // 当连接建立后
          socket.onopen = event => {
              // 发送数据给后端
              const dataToSend = {
                "account": '<?php echo $_SESSION['account']; ?>',
                "agent_name":'<?php echo $_POST['agent_name']; ?>',
              };
              socket.send(JSON.stringify(dataToSend)); // 将数据作为字符串发送，你可以使用 JSON 或其他格式
          };

          socket.onmessage = event => {
            console.log(event.data);
              document.getElementById("log").innerText = event.data;
          };
          }
        */
        function sleep(ms) {
          return new Promise(resolve => setTimeout(resolve, ms));
          }
        
        async function get_img(){
          const api_url = 'http://localhost:6055/evaluation_img';
            const response = await fetch(api_url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({                
              "account": '<?php echo $_SESSION['account']; ?>',
              "agent_name":'<?php echo $_POST['agent_name']; ?>',
        			})
            });
            const result = await response.json();
            document.getElementById("performance_img").src = result['img'];
        }

        async function cut(){
          training = false;
          console.log(training);
          var end = document.getElementById('end');
          end.disabled = true;
          alert("訓練將於下次評估後終止。");

          const api_url = 'http://localhost:6055/end_training';
          const response = await fetch(api_url, {
          method: 'POST',
          headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({                
            "account": '<?php echo $_SESSION['account']; ?>',
            "agent_name":'<?php echo $_POST['agent_name']; ?>',
            })
          });
          const respond = await response.json();
        }

        async function logging() {
          var processing= true;
          await sleep(5000); 
          while (processing) {
            const api_url = 'http://localhost:6055/training_log';
            const response = await fetch(api_url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({                
              "account": '<?php echo $_SESSION['account']; ?>',
              "agent_name":'<?php echo $_POST['agent_name']; ?>',
        			})
            });
            const respond = await response.json();
            document.getElementById("log").innerText = respond['text'];
            console.log(respond['over'])
            if(respond['over']=="done"){
              processing= false;
              var start = document.getElementById('train');
              start.disabled = false;
              var end = document.getElementById('end');
              end.disabled = true;
            }
            await sleep(30000); 
          }
          }

        async function to_train() {

            /* RESET */
            document.getElementById("performance_img").src = '';
            document.getElementById("table_total").textContent = '-';
            document.getElementById("table_random").textContent = '-';
            document.getElementById("table_discount").textContent = '-';
            document.getElementById("table_expl").textContent = '-';
            document.getElementById("table_action").textContent = '-';
            document.getElementById("table_tau").textContent ='-';
            document.getElementById("performance").textContent = '-';
            document.getElementById("log").innerText = ' ';

            training = true;
            const api_url = 'http://localhost:6055/training';
            const response = await fetch(api_url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              "account": '<?php echo $_SESSION['account']; ?>',
              "agent_name":'<?php echo $_POST['agent_name']; ?>',
              "max_timesteps_low":$( "#max_timesteps-slider" ).slider( "values", 0 ),
              "start_timesteps_low":$( "#start_timesteps-slider" ).slider( "values", 0 ),
              "discount_low":$( "#discount-slider" ).slider( "values", 0 ),
              "expl_noise_low":$( "#expl_noise-slider" ).slider( "values", 0 ),
              "policy_noise_low":$( "#policy_noise-slider" ).slider( "values", 0 ),
              "tau_low":$( "#tau-slider" ).slider( "values", 0 ),
              "max_timesteps_up":$( "#max_timesteps-slider" ).slider( "values", 1 ),
              "start_timesteps_up":$( "#start_timesteps-slider" ).slider( "values", 1 ),
              "discount_up":$( "#discount-slider" ).slider( "values", 1 ),
              "expl_noise_up":$( "#expl_noise-slider" ).slider( "values", 1 ),
              "policy_noise_up":$( "#policy_noise-slider" ).slider( "values", 1 ),
              "tau_up":$( "#tau-slider" ).slider( "values", 1 ),
              "update_round":document.getElementById('update_round').value,
              "evaluate_step":document.getElementById('evaluate_step').value,
              "test_times":document.getElementById('test_times').value,
              "actor_lr":$( "#actor_lr-slider" ).slider( "value"),
              "target_lr":$( "#target_lr-slider" ).slider( "value"),
              "batch_size":document.getElementById('batch_size').value,
        			})
            });
            const result = await response.json();
            get_img();
            document.getElementById("table_total").textContent = result['max_timesteps'];
            document.getElementById("table_random").textContent = result['start_timesteps'];
            document.getElementById("table_discount").textContent = result['discount'];
            document.getElementById("table_expl").textContent = result['expl_noise'];
            document.getElementById("table_action").textContent = result['policy_noise'];
            document.getElementById("table_tau").textContent = result['tau'];
            document.getElementById("performance").textContent = result['performance'];
            }
            
          var start = document.getElementById('train');
          var end = document.getElementById('end');
              start.addEventListener("click", function () {
                start.disabled = true;
                end.disabled = false;
                console.log('start training.');
                  to_train();
                  logging();
                });
        
        // 取得 wrapper div 的寬度
        var wrapperWidth = document.querySelector(".wrapper").offsetWidth;

        // 設定 setting_area div 的寬度
        document.querySelector(".setting_area").style.width = wrapperWidth-30 + "px";

        document.querySelector(".cm-s-bespin").style.height = "100%";
        
      </script>
      </div>
    
    </div>
  </body>
</html>

