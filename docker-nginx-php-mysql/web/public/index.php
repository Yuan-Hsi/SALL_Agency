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

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo "SALL Agency"; ?></title>
        <link rel="stylesheet" href="./styles/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
    </head>
    <body bgcolor = "EDEDED" style ="margin-left:120px;margin-right:120px">
        <div class = "header" >
            <a href="index.php"><img src="./img/logo(2).png" alt="logo" style=" width:90.2px ;height:58.3px;"></a>
            <div class = "pure-menu pure-menu-horizontal portal" style="margin-left: 20px">
                <ul class="pure-menu-list">
                    <li class="pure-menu-item">
                        <a href="index.php" class="pure-menu-link"><p class = "portal_name" >Home </p></a>
                    </li>
                    <li class="pure-menu-item">
                        <a href="demo.php" class="pure-menu-link"><p class = "portal_name" >Demo</p></a>
                    </li>
                    <li class="pure-menu-item">
                        <a href="#" class="pure-menu-link"><p class = "portal_name" >About me</p></a>
                    </li>
                    <li class="pure-menu-item">
                        <a href="#" class="pure-menu-link"><p class = "portal_name" >Contact</p></a>
                    </li>
                </ul>

            </div>
            <div class = "member-button" >
                <a href="#enttrance" class="pure-menu-link" ><p class = "login_portal">Log in</p></a>
                <style>
                    .button-success {
                        color: white;
                        width:120px;
                        height:53px;
                        border-radius: 8px;
                        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
                        background: #3164f4;
                    }
                </style>
                <form action="#enttrance">
                    <button class="button-success pure-button">Sign up</button>
                </form>
            </div>
        </div>
        <div class = "content">
            <div class = "section_3" style = "margin-top: 30px">
                <div class = "enttrance_intro">
                        <h1 style = "font-size:40pt; font-weight: bold;"> Get Ready! </h1>
                        <p class = "text" > If you want to save all your data. Feel free to <span style = "color : #edbb2a ; font-weight:bold"> SIGN UP! </span> my project! or <br> you can demo in here and see how good the agent you trained just once. <br> 
                        <br>
                        Appreciate your attention in my work. Hope it can blow your mind.</p>
                        <form action="demo.php">
                        <button class="button-success pure-button" style = "float : right; margin-top:30px">Demo NOW</button>
                        </form>
                </div>
                <div class = "enttrance" id = "enttrance">
                    <div class = "index_box">
                        <div>
                            <p class = "login_index" id = "login_index" >Log In</p>
                        </div>
                        <div>
                            <p class = "signup_index" id = "signup_index" >Sign Up</p>
                        </div>
                    </div>

                    <div class = "login_box" id= "login_box">
                        <form class="form" action="index.php?inAndOut=in" method="post">
                            <label>Account Name</label>
                                <input type="text" name="user_name" value=""  class= "login_input">
                            <label>Password</label> 
                            <input type="text" name="user_pw" value=""  class= "login_input">
                            <button type="submit" class= "login_btn" name="button"> Log in </button>
                        </form>
                    </div>

                    <div class = "signup_box" id= "signup_box">
                        <form class="form" action="index.php?signUp=up" method="post">
                            <label> Email </label>
                            <input type="text" name="signup_email" value="" class=login_input ">
                            <label> Account Name </label>
                            <input type="text" name="signup_account" value="" class=login_input >
                            <label style="margin-top : 10px"> Password </label>
                            <input type="text" name="signup_password" value="" class=login_input >
                            <button type="submit" name="button" style="background : gray" class="login_btn"> Sign up </button>
                        </form>
                    </div>

                    <script>
                        document.getElementById("login_index").addEventListener("click", login_float);
                        document.getElementById("signup_index").addEventListener("click", signup_float);
                        function signup_float() {
                                var box2 = document.getElementById("signup_box");
                                var box1 = document.getElementById("login_box");
                                box2.style = "z-index:3";
                                box1.style = "z-index:2";
                        }
                        document.getElementById("signup_index").addEventListener("click", myFunction);

                        function login_float() {
                                var box2 = document.getElementById("signup_box");
                                var box1 = document.getElementById("login_box");
                                box2.style = "z-index:2";
                                box1.style = "z-index:3";
                        }
                    </script>
                </div>
            </div>
        </div>


        <?php # 登入登出與註冊
        if(isset($_REQUEST['inAndOut'])){
            switch ($_REQUEST['inAndOut']){
                case 'in':
                    if (userCheck($conn)){echo "成功登入！";}
                    else {echo "<script> alert('輸入錯誤，請重新登入') </script>"; exit;}
                    break;
                case 'out':
                    user_logout();
                    break;
            }
        }     
        if(isset($_REQUEST['signUp'])){
            if (user_signup($conn)) echo "成功註冊！";
            else {echo "<script> alert('輸入錯誤，請重新註冊') </script>"; exit;}
        }
        ?>

         <?php #函式庫
        function userCheck($mysqli){
            if(!isset($_POST['user_pw']) || !isset($_POST['user_name'])) return False;
            foreach ($_POST as $var_name => $var_val){
                $$var_name = $mysqli -> real_escape_string($var_val);
            }
            $query= "SELECT * FROM Account WHERE Account = '{$user_name}'";
            $result = $mysqli->query($query) or die($mysqli->connect_error);
            while($line = $result->fetch_assoc()){
                if(password_verify($_POST['user_pw'], $line['psw'])){
                    $_SESSION['email'] = $line['Email'];
                    return True;
                }
            }
            return False;

            $user_pw = password_varify($_POST['user_pw'], PASSWORD_DEFAULT);


            
        }
        function user_logout(){
            if(isset($_SESSION['email']))echo "已將您登出";
            unset($_SESSION['email']);
        }
        function user_signup($mysqli){
            if(!isset($_POST['signup_email']) || !isset($_POST['signup_account']) || !isset($_POST['signup_password'])) return False;
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $mysqli = connection();

            foreach ($_POST as $var_name => $var_val){
                $$var_name = $mysqli -> real_escape_string($var_val);
            }
            
            # 確認信箱是否重複
            $query_email = " SELECT Email FROM `Account` WHERE Email = '{$signup_email}' " ;
            $email_result = mysqli_query($mysqli,$query_email);
            while($row = mysqli_fetch_array($email_result)){
                $email = $row["Email"]; 
            }
            if(isset($email)) return False;

            # 密碼轉換與插入資料庫資料庫
            $signup_password = password_hash($_POST['signup_password'], PASSWORD_DEFAULT);
            $instruction = "INSERT INTO `Account` (`Email`,`Account`,`psw`,`Token`) VALUES
            ('{$signup_email}','{$signup_account}','{$signup_password}','User')";
            $mysqli->query($instruction) or die($mysqli->connect_error);
            return True;
        }
         ?>
    </body>
</html>
