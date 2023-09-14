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

      <div class="wrapper" style="margin-left: 30px; width:75%;  background: linear-gradient(to right, #ffffff, #fdfdfd);box-shadow: 3px 3px 3px #cbced1, -3px -3px 3px white;border-radius: 5%;"><!--右選單-->
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
          $set_missing = "`AVG_fill` = "."1";
        }
        else{
          $set_missing = "`Custom_fill` = ".$_POST["missing"][1];
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

        $where = "`Account` = '".$_SESSION['account']."' AND `Agent` = '"."{$_POST["agent_name"]}'";
        $query = "UPDATE `Agent_data` SET ".$set_missing." WHERE ".$where.";";
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
        <form action='<?php echo $_SERVER['PHP_SELF'];?>' method="POST"> 
            <button class="button-small pure-button" type="submit" name="fix" id="test" style = "margin-top:2%;margin-left:3%"value= <?php echo $_POST["agent_name"]; ?> >往前一頁</button>
        </form>
        
      <div class="setting_area" style="justify-content:flex-start;flex-direction:column;width:auto;padding-right:10px;" >
        
        <!--<form class="form" action="index.php" method="post"> -->
        <div style = "width:auto;height:auto;margin-right:30px">
        <button type="submit" name="agent_name" value=<?php echo $_POST["agent_name"]?>  id="save">保存</button>
        </div>
        <div id = 'coding_area' style = 'background : #cacbd4;height:1000px;overflow: scroll;width:95%'>

        <?php
        // 讀取文件
        $code = file_get_contents("env_set.py");
        // 設定預設原始碼
        echo "<textarea id='coding_editor' name='code' style='height:100%;width:100%'>$code</textarea>";
        ?>
        </div>
        <!--</form>-->

        <div id = 'error_occur' style = 'background : #cacbd4;margin: 20px;overflow: scroll;'>

        </div>

        <script >
        
        // 初始化編輯器
        var editor = CodeMirror.fromTextArea(document.getElementById("coding_editor"), {
          theme: "bespin",
          mode: "python",
          tabSizes:5,
          lineNumbers:true,
        });

        // the line numbers to be "readonly"
        var readOnlyLines =[];
        for (let i = 0; i <= 70; i++) {
          readOnlyLines.push(i);
        }

        // listen for the beforeChange event, test the changed line number, and cancel
        editor.on('beforeChange',function(cm,change) {
            if ( ~readOnlyLines.indexOf(change.from.line) ) {
                change.cancel();
            }
        });
        // 取得 wrapper div 的寬度
        var wrapperWidth = document.querySelector(".wrapper").offsetWidth;

        // 設定 setting_area div 的寬度
        document.querySelector(".setting_area").style.width = wrapperWidth-30 + "px";

        document.querySelector(".cm-s-bespin").style.height = "100%";
        
        // 添加按鈕事件處理程序
        document.getElementById("save").addEventListener("click", function() {
          alert("儲存");
          // 保存編輯器內容
          editor.save();
          document.getElementById("coding_editor").textContent =  editor.getValue();
          coding_test();
        });

        async function coding_test() {
            const api_url = 'http://localhost:6001/coding_test';
            const response = await fetch(api_url, {
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
            const code = await response.json();
            console.log(code);
            }
        </script>
      </div>
    
    </div>
  </body>
</html>

