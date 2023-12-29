<?php 
session_start();
function connection(){
    $conn=mysqli_connect("mysql", "root", "root","AP"); 
    if(!$conn){
        die('could not connect:'.mysqli_connect_error());
    }
    return $conn;
  }
$conn = connection();

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
      <div class="member-button">
        <a href="index.php#enttrance" class="pure-menu-link"
          ><p class="login_portal">Log in</p></a
        >
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
        <form action="index.php#enttrance">
          <button class="button-success pure-button">Sign up</button>
        </form>
      </div>
    </div>

    <div class="section_3" style="background: #eace5e; height: 300px">
      <!--績效表-->
    </div>

    <div class="user_content" style="margin-top: 30px">
      <!--Content-->

        <div class="menu" style=" width: 18%; height: 700px; padding: 20px; padding-left: 40px"><!--左選單-->
        <a href="demo.php"><h2 style = "line-height: 1.5;"> Agent Building </h2></a>
          <ul class = "MLOps_list">
          <li>
                <p><span> Choose Your Agent <span></p>
            </li>
            <li>
                <p> Data Collection</p>
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

      <div class="wrapper" style="margin-left: 30px; width: 75%"><!--右選單-->
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

        <div
          class="agent_mode"
          style="background: linear-gradient(to right, #b8cae9, #111775)"
        >
          <img
            class="avatar"
            src="https://api.dicebear.com/5.x/bottts/svg?flip=true&size=64&seed=19"
            alt="19"
            id="mode_2"
            height="158px"
            width = "158px"
          />
          <div class="model_container">
            <p class="model_name">A3C Agent</p>
            <button
              type="button"
              onclick="change_2()"
              class="change_btn change_blue"
            >
              Change!
            </button>
          </div>
          <form
            class="form"
            id = "A3C-LSTM"
            action="data_collection.php?model_type=A3C-LSTM&agent_num=19"
            method="post"
            style="align-self: flex-end"
          >
            <button type="submit" name="button" class="agent_choosen">
              <span> Choose This Agent </span>
            </button>
          </form>
        </div>
        <div
          class="agent_mode"
          style="background: linear-gradient(to right, #f8f2d7, #ec6db6)"
        >
          <img
            class="avatar"
            src="https://api.dicebear.com/5.x/micah/svg?flip=true&size=64&seed=286"
            alt="286"
            id="mode_3"
            height="158px"
            width = "158px"
          />
          <div class="model_container">
            <p class="model_name">PPO Agent</p>
            <button
              type="button"
              onclick="change_3()"
              class="change_btn change_pink"
            >
              Change!
            </button>
          </div>
          <form
            id = "PPO-LSTM"
            class="form"
            action="data_collection.php?model_type=PPO-LSTM&agent_num=286"
            method="post"
            style="align-self: flex-end"
          >
            <button type="submit" name="button" class="agent_choosen">
              <span> Choose This Agent </span>
            </button>
          </form>
        </div>
        <div></div>
        <div></div>
        <script>
          function change_1() {
            let number = Math.floor(Math.random() * 300);
            var obj = document.getElementById("mode_1");
            obj.src = `https://api.dicebear.com/5.x/big-smile/svg?flip=true&size=64&seed=${number}`;
            obj.alt = number;
            var fo = document.getElementById("TD3-LSTM");
            fo.action = `data_collection.php?model_type=TD3-LSTM&agent_num=${number}`
          }
          function change_2() {
            let number = Math.floor(Math.random() * 300);
            var obj = document.getElementById("mode_2");
            obj.src = `https://api.dicebear.com/5.x/bottts/svg?flip=true&size=64&seed=${number}`;
            obj.alt = number;
            var fo = document.getElementById("A3C-LSTM");
            fo.action = `data_collection.php?model_type=A3C-LSTM&agent_num=${number}`
          }
          function change_3() {
            let number = Math.floor(Math.random() * 300);
            var obj = document.getElementById("mode_3");
            obj.src = `https://api.dicebear.com/5.x/micah/svg?flip=true&size=64&seed=${number}`;
            obj.alt = number;
            var fo = document.getElementById("PPO-LSTM");
            fo.action = `data_collection.php?model_type=PPO-LSTM&agent_num=${number}`
          }
        </script>
      </div>
    </div>

    <?php
        $state_1 = 0.4;
        $state_2 = 0.5;
        $state_3 = 0.6;
        $state_4 = 0.2;
        $state_5 = 0.3;
        $state_6 = 0.1;
        $state_7 = 0.9;
        $state_8 = 0.0;
        if(isset($_REQUEST['log_out'])){
          user_logout();
        }
        function user_logout(){
          if(isset($_SESSION['email']))echo "已將您登出";
          unset($_SESSION['email']);
          unset($_SESSION['account']);
      }
    ?>

    <div class="bottom" style="height: 200px">
      <p id="test_1">10</p>
      <p id="test_2">20</p>
      <script>

        		const api_url = 'http://localhost:6050/data_preprocessing';
        		async function getPrice() {
            	const response = await fetch(api_url, {
            		method: 'POST',
        			headers: {
              		'Accept': 'application/json',
              		'Content-Type': 'application/json'
            	},
          		body: JSON.stringify({
        			"state_1": <?php echo $state_1; ?> ,
        			"state_2": <?php echo $state_2; ?>,
        			"state_3": <?php echo $state_3; ?>,
        			"state_4": <?php echo $state_4; ?>,
        			"state_5": <?php echo $state_5; ?>,
        			"state_6": <?php echo $state_6; ?>,
        			"state_7": <?php echo $state_7; ?>,
              "state_8": <?php echo $state_8; ?>
        			})
        			});
        		const data = await response.json();
        		//console.log(data);
        		//alert(JSON.stringify(data));
        		var s_1= data.state_1;
        		var s_2= data.state_2;
        		//alert(infer4);
        		document.getElementById("test_1").textContent = s_1;
        		document.getElementById("test_2").textContent = s_2;
        }
        // Calling the function
        getPrice();
      </script>
    </div>
  </body>
</html>
