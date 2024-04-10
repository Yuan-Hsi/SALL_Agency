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
  button:disabled,
  button[disabled]{
    border: 1px solid #999999;
    background-color: #cccccc;
    color: #666666;
    pointer-events: none;
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

            $query = "SELECT * FROM `Agent_data` WHERE `Deploy`=1 AND `Account` = '{$_SESSION['account']}'";
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
          CURRENT DEPLOY
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
                        if($deploy_info['Custom_fill'] != ''){echo'Missing fill: '.$deploy_info['Custom_fill'];}
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
        <?php // 計算這是從第幾筆到第幾筆
          $per_total = $line_count;  //計算總筆數
          $per = 3;  //每頁筆數
          $pages = ceil($per_total/$per);  //計算總頁數;ceil(x)取>=x的整數,也就是小數無條件進1法
      
            if(!isset($_GET['page'])){  //!isset 判斷有沒有$_GET['page']這個變數
            $page = 1;	  
            }else{
          $page = $_GET['page'];
          }
      
          $start = ($page-1)*$per;  //每一頁開始的資料序號(資料庫序號是從0開始)
      
          $page_start = $start ;  //選取頁的起始筆數
          $page_end = $start + $per;  //選取頁的最後筆數
          if($page_end>$per_total){  //最後頁的最後筆數=總筆數
              $page_end = $per_total;
          }

          for($i = $page_start; $i < $page_end; $i++){
        ?>
        <div
          class="agent_mode"
          style="background: linear-gradient(to right, #6D90B9, #BBC7DC)"
        >
          <img 
            class="avatar"
            src=<?php echo "https://api.dicebear.com/5.x/big-smile/svg?flip=true&size=64&seed=".$agent_info[$i]['Agent_num']?>
            alt="60"
            id="mode_1"
            height="158px"
            width = "158px"
          />
          <div class="model_container" style ="text-align:center;width:150px;margin-right: 0%;">
            <h1 style="font-size:15pt;color:white;letter-spacing: 3px;"> Name </h1>
            <p class="model_name"><?php echo $agent_info[$i]['Agent']?></p>
          </div>
          <div class="model_container" style ="text-align:center;width:150px;margin-right: 0%;">
            <h1 style="font-size:15pt;color:white;letter-spacing: 3px;"> ITEM </h1>
            <p class="model_name"><?php echo $agent_info[$i]['stock_num']?></p>
          </div>
          <div class="model_container" style ="text-align:center;width:190px;margin-right: 0%;">
            <h1 style="font-size:15pt;color:white;letter-spacing: 3px;"> Performance </h1>
            <p class="model_name"><?php echo ($agent_info[$i]['performance']==1000000000)? "-" : $agent_info[$i]['performance'] ; ?></p>
          </div>
          <div style="display:flex;justify-content:center;align-items:center;flex-direction:column;margin-left:40px;">
          <div style = "margin-bottom:20px;">
          <button type="button" <?php if($agent_info[$i]['performance']==1000000000){echo ' disabled ';}?> onclick="deploy('<?php echo $agent_info[$i]['Agent']?>')" class="change_btn" style="width:100px;height:50px;margin-right:10px">
              Deploy
            </button>
          <button type="button" <?php if($agent_info[$i]['Agent']=='Default'){echo ' disabled ';}?> onclick="delete_('<?php echo $agent_info[$i]['Agent']?>')" class="change_btn change_red" style="width:100px;height:50px;">
              Delete
            </button>
          </div>
          <form
            id = "TD3-LSTM"
            class="form"
            action=<?php echo "training_log.php?agent=".$agent_info[$i]['Agent'] ?>
            method="post"
            style="align-self: flex-end"
          >
          <?php if($agent_info[$i]['performance']!=1000000000){?>
            <button type="submit" name="button" class="agent_choosen">
              <span> Training Log </span>
            </button>
            <?php } ?>
          </form>
          </div>
          <div style="display:flex;justify-content:center;align-items:center;margin-left:6.5%;">
          <div class="for_more" >!<span class="tooltip" style="font-size:10pt;">                      
                  <h3 style="font-weight: bold;text-align: center;"> - Features - </h3>
                  <h3 style="text-align: center;"><?php echo $agent_info[$i]['Start_date'].' ~ '.$agent_info[$i]['End_date']?></h3>
                  <p style="text-align: center;margin-top:0px;"><?php echo 'Training Ratio: '.$agent_info[$i]['training_set'].'%'?></p>
                      <ul style = "list-style-type: square; margin-left:20pt; line-height:120%">
                          <?php
                              for ($count = $feature_start ; $count < $feature_end ; $count++) {
                                $feature = $column_name[$count];
                                if($agent_info[$i][$feature] == 1){
                                  echo "<li style='color:#424242'>".$feature.'</li>';
                                }
                              }
                          ?>
                      </ul></span></div>
          </div>
        </div>
        <?php } ?>

        <div class = "current_page" style="text-align: center;">
        <?php
            //每頁顯示筆數明細
            echo '顯示 '.($page_start+1).' 到 '.$page_end.' 筆   共 '.$per_total.' 筆，  目前在第 '.$page.' 頁   共 '.$pages.' 頁'; 
        ?>
        </div>
        <div class = "page_log">
        <?php
  if($pages>1){  //總頁數>1才顯示分頁選單

	//分頁頁碼；在第一頁時,該頁就不超連結,可連結就送出$_GET['page']
	if($page=='1'){
		echo "首頁 ";
		echo "上一頁 ";		
	}else{
		echo "<a href=?page=1>首頁 </a> ";
		echo "<a href=?page=".($page-1).">上一頁 </a> ";		
	}

   //此分頁頁籤以左、右頁數來控制總顯示頁籤數，例如顯示5個分頁數且將當下分頁位於中間，則設2+1+2 即可。若要當下頁位於第1個，則設0+1+4。也就是總合就是要顯示分頁數。如要顯示10頁，則為 4+1+5 或 0+1+9，以此類推。	
     for($i=1 ; $i<=$pages ;$i++){ 
        $lnum = 2;  //顯示左分頁數，直接修改就可增減顯示左頁數
        $rnum = 2;  //顯示右分頁數，直接修改就可增減顯示右頁數

   //判斷左(右)頁籤數是否足夠設定的分頁數，不夠就增加右(左)頁數，以保持總顯示分頁數目。
     if($page <= $lnum){
         $rnum = $rnum + ($lnum-$page+1);
     }

     if($page+$rnum > $pages){
         $lnum = $lnum + ($rnum - ($pages-$page));
     }

        //分頁部份處於該頁就不超連結,不是就連結送出$_GET['page']
          if($page-$lnum <= $i && $i <= $page+$rnum){
              if($i==$page){
                 echo $i.' ';
                      }else{
                          echo '<a href=?page='.$i.'>'.$i.'</a> ';
                   }
              }
          }


	//在最後頁時,該頁就不超連結,可連結就送出$_GET['page']	
	if($page==$pages){
		echo " 下一頁";
		echo " 末頁";	
	}else{
		echo "<a href=?page=".($page+1)."> 下一頁</a>";
		echo "<a href=?page=".$pages."> 末頁</a>";		
	}
  }
  ?>
        </div>
        <script >
        
        async function get_img(){
          const api_url = 'http://140.119.19.81:6055/evaluation_img';
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

        async function delete_agent(agent){
          const api_url = 'http://140.119.19.81:6055/delete_agent';
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
            location.reload();
        }

        async function deploy(agent){
          const api_url = 'http://140.119.19.81:6055/deploy_agent';
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
            location.reload();
        }

        async function delete_(agent){
          let text = "請確認是否要刪除代理人。";
          if (confirm(text) == true) {
            delete_agent(agent);
          }
        }

        get_img()
          </script>
    
    </div>
  </body>
</html>
