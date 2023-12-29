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
    <link rel="stylesheet" href="codemirror-5.65.15/theme/darcula.css">
    <script src="codemirror-5.65.15/mode/javascript/javascript.js"></script>
    <script src="codemirror-5.65.15/mode/python/python.js"></script>
    <title><?php echo "SALL Agency"; ?></title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  </head>

  <style>
    .deploy_model{   border: 2px solid black; /* 設定框線 */
   box-shadow: 10px 10px 5px grey; /* 設定外陰影 */
   border-radius: 10px; /* 設定圓角 */
   position: relative;}
   .box_tag{
    position: absolute;
    background-color: white;
    margin-top:-10px;
    margin-left:45%;
    text-align: center;
    border: 1px solid black; /* 設定框線 */
    box-shadow: 2px 2px 5px grey; /* 設定外陰影 */
    border-radius: 10px; /* 設定圓角 */
    width:100px;
    padding:10px;
   }
   .for_more {
   width: 50px;
   height: 50px;
   border-radius: 50%;
   background-color: rgba(255,0,0,0);
   border-width:2px;
   border-style:solid;
   border-color:#ffffff;
   color: #ffffff;
   text-align: center;
   line-height: 50px;
   position: relative; /* Add this */
   font-size: 2em;}


.for_more .tooltip {
   width:150pt;
   visibility: hidden;
   position: absolute;
   background-color: #e2ebf0;
   color: black;
   text-align: center;
   border-radius: 0.25em;
   padding: 0.25em 0.5em;
   z-index: 1;
   top: -150%;
   left: 150%;
   transition: visibility 0.1s;
}

.for_more:hover .tooltip {
   visibility: visible;
}
.CodeMirror{
  height:700px;
}
.change_btn {
  height: 20%;
  align-self: flex-start;
  border-radius: 8px;
  border: 1px solid #424242;
  transition-duration: 0.4s;
  background: rgba(255, 122, 89, 0);
  color: #424242;
  cursor: pointer;
}
.change_btn:hover {
  background-color: #ffffff;
  color: rgb(0, 128, 255);
  font-weight:bold;
}
.change_red:hover {
  background-color: #d11313;
  color: rgb(0, 0, 0);
  font-weight:bold;
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
                <p>Data Collection</p>
            </li>
            <li>
                <p>Agent Setting</p>
            </li>
            <li>
                <p>Agent Training</p>
            </li>
            <li>
                <p>Agent Evaluation</p>
            </li>
          </ul>
          <a href="model_management.php"><h2> Agent Management</h2></a>
          <a href="model_inference.php"><h2> Agent Inference</h2></a>
        </div>

      <div class="wrapper" style="margin-left: 30px; width:75%;"><!--右選單-->
        <?php
            $agent=array();
            $agent_info=array();
            $column_name=array();
            $deploy = array();
            $deploy_info = array();
        
            $query = "SELECT * FROM `Agent_data` WHERE `Deploy` = 0 AND `Account` = '{$_SESSION['account']}' ORDER BY `create_time` DESC";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            $line_count = 0; // 有幾行 agent 
            while($row = mysqli_fetch_array($result)){
              $line_count += 1;
              $push = array_push($agent, $row);
            }

            $query = "SELECT column_name FROM information_schema.columns WHERE table_name = 'Agent_data' ORDER BY ordinal_position";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            $column_count = 0;
            $feature_start = 0;
            $featrues_end = 0;
            while($row = mysqli_fetch_array($result)){
              $column_count += 1; // 有幾個欄位 
              $push = array_push($column_name, $row["COLUMN_NAME"]);
              if($row["COLUMN_NAME"] == '開盤價(元)'){
                $feature_start = $column_count -1;
              }
              if($row["COLUMN_NAME"] == '股利殖利率-TSE'){
                $feature_end = $column_count;
              }
            }

            $query = "SELECT * FROM `Agent_data` WHERE `Agent`= '{$_GET['agent']}' AND `Account` = '{$_SESSION['account']}'";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            while($row = mysqli_fetch_array($result)){
              $push = array_push($deploy, $row);
            }
            
            
            for ($x = 0; $x < $line_count; $x++) {
              for ($y = 0; $y < $column_count; $y++) {
                $agent_info[$x][$column_name[$y]] = $agent[$x][$y];
              }
            }
            
            for ($z = 0; $z < $column_count; $z++) {
              $deploy_info[$column_name[$z]] = $deploy[0][$z];
            }

            
            
            // 如果是 NULL 的話 空值 == ''
            // 注意就算是一行拉一個值，一樣是二維的！
        ?>
        <div class ="deploy_model" style ="background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);margin-bottom:2%;display:flex;padding-bottom:5px">
        <div class ="box_tag">
          AGENT INFO
          </div>
            <div class = "agent_cv" style = "margin-top:5px;">
                <div class = "agent_info" style = "display:flex;justify-content:space-evenly;margin-top:10px" >
                    <div class = "agent_name" style = "border-style: solid;display:flex;flex-direction:row;align-items:center" >
                      <img 
                        class="avatar"
                        src=<?php echo "https://api.dicebear.com/5.x/big-smile/svg?flip=true&size=64&seed=".$deploy_info['Agent_num'] ?>
                        alt="avatar"
                        height="128px"
                        width = "128px"
                        style = "margin-top:0px"
                      />
                      <h1 style="font-weight: bold;margin-left:5px"><?php echo $deploy_info['Agent']?></h1>
                    </div>
                    <div class = "agent_reward" style = "display:flex;align-items:center" >
                    <h1><?php echo "ROI: ".$deploy_info['performance']?></h1>
                    </div>
                </div>
                <hr style = 'width:80%'> <!-- 分隔線 -->
                <div class = "agent_para" style = "border-style: solid;height:10%;display:flex;flex-direction:row;" >
                    <div class = "basic_info" style = "margin:10px;" >
                      <h3 style="font-weight: bold;text-align: center;"> - Basic Info - </h3>
                      <ul style = "list-style-type: square; margin-left:20pt; margin-top:10pt;line-height:120%">
                        <li style='color:#424242;'><?php echo'Item: '.$deploy_info['stock_num']?></li>
                        <li style='color:#424242'><?php echo'Period: '.$deploy_info['Start_date'].' ~ '.$deploy_info['End_date']?></li>
                        <li style='color:#424242'><?php echo'Training set ratio: '.$deploy_info['training_set'].' %'?></li>
                        <li style='color:#424242'><?php echo'Length per round(episode): '.$deploy_info['length']?></li>
                        <li style='color:#424242'><?php echo'Invest budget: $'.$deploy_info['invest_budget']?></li>
                        <li style='color:#424242'><?php echo'Random seed: '.$deploy_info['seed']?></li>
                      </ul>
                      <h3 style='margin-top:10pt;font-weight: bold;text-align: center;'> - Preprocessing Info - </h3>
                      <ul style = "list-style-type: square; margin-left:20pt; margin-top:10pt;line-height:120%">
                        <li style='color:#424242'><?php 
                        if($deploy_info['Custom_fill'] != ''){echo'Missing fill: '.$deploy_info['stock_num'];}
                        else{
                          echo'Missing fill: Recent Average';
                        }?></li>
                        <li style='color:#424242'><?php 
                        if($deploy_info['Standardise'] == 0){echo'Standardise: Close';}
                        else{
                          echo'Standardise: Used';;
                        }?></li>
                        <li style='color:#424242'><?php 
                        if($deploy_info['Normalize'] == 0){echo'Normalize: Close';}
                        else{
                          echo'Normalize: Used';;
                        }?></li>
                        <li style='color:#424242'><?php 
                        if($deploy_info['Scaleing'] == 0){echo'Scaleing: Close';}
                        else{
                          echo'Scaleing: Used';;
                        }?></li>
                      </ul>
                    </div>
                    <div class = "input_col" style = "margin:10px;" >
                    <h3 style='font-weight: bold;text-align: center;' > - Features - </h3>
                        <ul style = "list-style-type: square; margin-left:20pt; margin-top:10pt;line-height:140%">
                          <?php
                              for ($dog = $feature_start ; $dog < $feature_end ; $dog++) {
                                if($deploy[0][$dog] == 1){
                                  echo "<li style='color:#424242'>".$column_name[$dog].'</li>';
                                }
                              }
                          ?>
                        </ul> 
                    </div>
                    <div class = "hyper_para" style = "margin:10px;" >
                    <h3 style='font-weight: bold;text-align: center;'> - Hyper parameters - </h3>
                      <ul style = "list-style-type: square; margin-left:20pt; margin-top:10pt;line-height:120%">
                        <li style='color:#424242'><?php echo'Batch size: '.$deploy_info['batch_size']?></li>
                        <li style='color:#424242'><?php echo'Actor model learning rate: 1e-'.$deploy_info['actor_lr']?></li>
                        <li style='color:#424242'><?php echo'Critic model learning rate: 1e-'.$deploy_info['target_lr']?></li>
                        <li style='color:#424242'><?php echo'Total steps: '.$deploy_info['max_timesteps']?></li>
                        <li style='color:#424242'><?php echo'Random explore steps: '.$deploy_info['start_timesteps']?></li>
                        <li style='color:#424242'><?php echo'Reward discount factor (γ): '.$deploy_info['discount'].' %'?></li>
                        <li style='color:#424242'><?php echo'exploration noise: '.$deploy_info['expl_noise'].' %'?></li>
                        <li style='color:#424242'><?php echo'policy noise: '.$deploy_info['policy_noise'].' %'?></li>
                        <li style='color:#424242'><?php echo'tau (target model update ratio): '.$deploy_info['tau'].' ‰'?></li>
                        <li style='color:#424242'><?php echo'Update after rounds: '.$deploy_info['update_round']?></li>
                      </ul>
                    </div>
                </div>
            </div>
            <div class = "performance_graph" style = "border-style: solid;display:flex;justify-content:center;align-items:center;margin-left:5px" >
                <img src="" alt="績效圖片" id = "performance_img" width="450" height="350">
            </div>
        </div>
        <div class = 'bottom half' style ="display:flex;justify-content:space-between;margin-top:15px;">
        <div id = 'coding_area' style = 'background : #cacbd4;height:700px;width:47%'>
        <div class ="box_tag"  style = "margin-top:-18px;margin-left:12%;z-index:100">
          Environmet Design
          </div>

<?php
$filePath = "./custom_env/".$_SESSION['account']."_".$deploy_info['Agent'].".py";
if(file_exists($filePath)){
  $code = file_get_contents($filePath);
}
else{
  $code = file_get_contents("./env_set.py");
}
// 設定預設原始碼
echo "<textarea id='coding_editor' name='code' style='height:700px;width:100%'>$code</textarea>";
?>
        </div>
      <div style = "width:47%">
      <div class ="box_tag"  style = "margin-top:-18px;margin-left:12%;z-index:100">
          Training log
          </div>
        <div id = 'log' style = 'padding-top:2%;padding-left:4%;background : #cacbd4;height:700px;overflow: scroll;width:100%'>
            </div>
          </div>
        </div>

        <div style = "margin-top:30px;display:flex;justify-content:center;">
        <button type="button" onclick="deploy()" class="change_btn change_or" style="width:100px;height:50px;margin-right:50px">
              Deploy
            </button>
          <button type="button" onclick="delete_('<?php echo $deploy_info['Agent'] ?>')" class="change_btn change_red" style="width:100px;height:50px;">
              Delete
            </button>
        </div>
        <div style = "margin-top:20px;display:flex;justify-content:center;">
        <form
            id = "TD3-LSTM"
            class="form"
            action="model_management.php"
            method="post"
            style="align-self: flex-end"
          >
            <button type="submit" name="button" class="agent_choosen">
              <span style= "color:#424242"> Back to manage page </span>
            </button>
          </form>
        </div>


        <script >
        var editor = CodeMirror.fromTextArea(document.getElementById("coding_editor"), {
          theme: "darcula",
          mode: "python",
          indentUnit: 4,
          lineNumbers:true,
          readOnly: true,
        });

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
              "agent_name":'<?php echo $deploy_info['Agent']; ?>',
        			})
            });
            const result = await response.json();
            document.getElementById("performance_img").src = result['img'];
        }
        
        
        async function logging() {
            const api_url = 'http://localhost:6055/training_log';
            const response = await fetch(api_url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({                
              "account": '<?php echo $_SESSION['account']; ?>',
              "agent_name":'<?php echo $deploy_info['Agent']; ?>',
        			})
            });
            const respond = await response.json();
            document.getElementById("log").innerText = respond['text'];
          }

        async function delete_agent(agent){
          const api_url = 'http://localhost:6055/delete_agent';
            const response = await fetch(api_url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({                
              "account": '<?php echo $_SESSION['account']; ?>',
              "agent_name":agent
        			})
            });
            const result = await response.json();
            location.replace("model_management.php")
        }

        async function delete_(agent){
          let text = "請確認是否要刪除代理人。";
          if (confirm(text) == true) {
            delete_agent(agent);
          }
        }
        logging();
        get_img();
          </script>
    
    </div>
  </body>
</html>
