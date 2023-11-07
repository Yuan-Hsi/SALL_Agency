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
                <p>Agent Setting</p>
            </li>
            <li>
                <p>Agent Training</p>
            </li>
            <li>
                <p>Agent Evaluation</p>
            </li>
          </ul>
          <a href="#"><h2> Agent Management</h2></a>
          <a href="#"><h2> Agent Inference</h2></a>
        </div>

      <div class="wrapper" style="margin-left: 30px; width:75%;"><!--右選單-->
        <?php
            $agent=array();
            $agent_info=array();
            $column_name=array();
            $deploy = array();
            $deploy_info = array();
        
            $query = "SELECT * FROM `Agent_data` WHERE `Deploy` = 0 ORDER BY `create_time`";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            $line_count = 0; // 有幾行 agent 
            while($row = mysqli_fetch_array($result)){
              $line_count += 1;
              $push = array_push($agent, $row);
            }

            $query = "SELECT column_name FROM information_schema.columns WHERE table_name = 'Agent_data' ORDER BY ordinal_position";
            $result = $conn -> query($query) or die ($conn -> connect_error);
            $column_count = 0;
            while($row = mysqli_fetch_array($result)){
              $column_count += 1; // 有幾個欄位 
              $push = array_push($column_name, $row["COLUMN_NAME"]);
            }

            $query = "SELECT * FROM `Agent_data` WHERE `Deploy`=1";
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
        ?>
        <div class ="deploy_model" style ="background-image: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%);margin-bottom:2%;display:flex;">
            <div class = "agent_cv" style = "">
                <div class = "agent_info" style = "display:flex;justify-content:space-between" >
                    <div class = "agent_name" style = "border-style: solid;display:flex;flex-direction:row;align-items:center" >
                      <img 
                        class="avatar"
                        src=<?php echo "https://api.dicebear.com/5.x/big-smile/svg?flip=true&size=64&seed=".$deploy_info['Agent_num'] ?>
                        alt="avatar"
                        height="128px"
                        width = "128px"
                      />
                      <h1><?php echo $deploy_info['Agent']?></h1>
                    </div>
                    <div class = "agent_reward" style = "display:flex;align-items:center" >
                    <h1><?php echo "Reward: ".$deploy_info['performance']?></h1>
                    </div>
                </div>
                <div class = "agent_para" style = "border-style: solid;height:10%;" >
                    <div class = "input_col" style = "" >
                      
                    </div>
                    <div class = "env_setting" style = "" >
                    
                    </div>
                    <div class = "env_setting" style = "" >
                    
                    </div>
                </div>
            </div>
            <div class = "performance_graph" style = "border-style: solid;" >
                <img src="" alt="績效圖片" id = "performance_img" width="450" height="350">
            </div>
        </div>
        <div
          class="agent_mode"
          style="background: linear-gradient(to right, #ffbf14, #f76759)"
        >
          <img 
            class="avatar"
            src="https://api.dicebear.com/5.x/big-smile/svg?flip=true&size=64&seed=60"
            alt="60"
            id="mode_1"
            height="158px"
            width = "158px"
          />
          <div class="model_container">
            <p class="model_name">TD3 Agent</p>
            <button type="button" onclick="change_1()" class="change_btn">
              Change!
            </button>
          </div>
          <form
           id = "TD3-LSTM"
            class="form"
            action="data_collection.php?model_type=TD3-LSTM&agent_num=60"
            method="post"
            style="align-self: flex-end"
          >
            <button type="submit" name="button" class="agent_choosen">
              <span> Choose This Agent </span>
            </button>
          </form>
        </div>
        <script >
        
              
          </script>
    
    </div>
  </body>
</html>
