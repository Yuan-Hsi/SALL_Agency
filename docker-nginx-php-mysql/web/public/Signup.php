<?php 
session_start();
function connection(){
    $conn=mysqli_connect("mysql", "root", "A!Lab502","AP"); 
    if(!$conn){
        die('could not connect:'.mysqli_connect_error());
    }
    return $conn;
  }
$conn = connection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Sign up!</title>
</head>
<body>
    <h1> 歡迎蒞臨事務所，請註冊已儲存操作 </h1>
    <form class="form" action="Signup.php" method="post">
        <p> 信箱： </p><input type="text" name="signup_email" value="">
        <p> 帳號名稱： </p><input type="text" name="signup_account" value="">
        <p> 密碼： </p><input id = "input" type="text" name="signup_password" value="">
        <button type="submit" name="button"> 註冊 </button>
     </form>

     <label>
        Choose an ice cream flavor:
        <select class="ice-cream" name="ice-cream">
            <option value="">Select One …</option>
            <option value="chocolate">Chocolate</option>
            <option value="sardine">Sardine</option>
            <option value="vanilla">Vanilla</option>
        </select>
    </label>

    <div class="result"></div>
    <div class= 'period' style="margin-top:20px;">
        <span style="margin-left: 10px;">＊訓練資料期間: 
        <!-- ✨✨✨ max-->
        <input id = "start" type="date" id="start" name="start" min='2011-11-02' max='<?php echo '$max_date'; ?>' style="width: 120px;" value='2010-01-01' > ～
        <!-- ✨✨✨ max-->
        <input  id = "end" type="date" id="end" name="end"  min='2011-11-02' max='<?php echo '$max_date;' ?>' style="width: 120px;" value='2010-02-01'>
           <!-- ✨✨✨ -->
           <!-- js 當日日期 -> 刪除 script內容-->
           <br>
        <!-- ✨✨✨ -->
        <span style="margin-left: 20px;">說明：可選擇的期間為 2011-11-02～資料庫最新資料日期，現在最新資料日期為<?php echo '$max_date;'?>。
      </div>

    <script>
        function difference(date1, date2) {
        const date1utc = Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate());
        const date2utc = Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate());
            day = 1000*60*60*24;
        const total_day = (date2utc - date1utc)/day
        const weekday1 = date1.getDay();
        const weekday2 = date2.getDay();
        let sum = 0;
        if(weekday1 <= 3){sum += 1;};
        if(weekday2 >= 3){sum += 1;};
        return Math.floor((total_day - (7 - weekday1) - weekday2)/7) + sum ;
        }

        const selectElement = document.querySelector('.period');

        let start = document.getElementById('start').value;
        let end = document.getElementById('end').value;
        let start_date = new Date(start);
        let end_date = new Date(end);
        let time_difference = difference(start_date, end_date);
        const result = document.querySelector('.result');
        result.textContent = `中間共有 ： ${time_difference} 週`;

        selectElement.addEventListener('change',good);

        function good(){
        start = document.getElementById('start').value;
        end = document.getElementById('end').value;
        let start_date = new Date(start);
        let end_date = new Date(end);
        let time_difference = difference(start_date, end_date);
        const result = document.querySelector('.result');
        result.textContent = `中間共有 ： ${time_difference} 週`;
        let size = document.getElementById('window_size').value;
        let step = document.getElementById('window_step').value;
        let total_step =Number(size)+Number(step);
        if(time_difference < total_step){alert("所選週數不可少於 Moving Window + Step ！");}
        }
    </script>
          <!-- moving window -->
    <div class = 'settings_content'>
        <div style="margin-left: 10px;">＊Moving Window </div>
        <span style="margin-left:20px;">Window Size<input type="number" max="300" min="100" id="window_size" style="margin-left:10px;" name='window_size' value=200 > （window的範圍，意即每次訓練涵蓋的資料筆數）<br>
        <span style="margin-left:20px;">Window Step<input type="number" max="50" min="1" id="window_step" style="margin-left:10px;"name='window_step' value=4> （test data的範圍，意即每次測試涵蓋的資料筆數）<br>
        <span style="margin-left:20px;">說明：為因應訓練長期資料，避免訓練效果受特定事件影響，將資料切割成片段訓練。由於紅銅價格的波動性極高，為了提升模型未來的預測準確度，我們在訓練過程中採用moving window的方式，利用window size設定每次欲訓練的資料範圍，並透過window step設定每次作為測試資料的筆數，並且每次window的測試資料皆不重複，如此迭代進行數次，直到訓練完成。例如當總資料筆數有100筆，而window size/window step分別設定為10/2時，則表示第一次訓練是由第1-10筆作為訓練資料，第11-12筆作為測試資料；第二次訓練則是由第3-12筆作為訓練資料，第13-14筆作為測試資料……以此類推。
     </div>

     <?php
     if(isset($_POST['signup_password']) && isset($_POST['signup_email']) && isset($_POST['signup_account'])){ insertuser();
       echo "註冊成功。歡迎您!" ;}

        function insertuser(){
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $mysqli = connection();

            foreach ($_POST as $var_name => $var_val){
                $$var_name = $mysqli -> real_escape_string($var_val);
            }
            $signup_password = password_hash($_POST['signup_password'], PASSWORD_DEFAULT);

            $instruction = "INSERT INTO `Account` (`Email`,`Account`,`Password`,`Token`) VALUES
            ('{$signup_email}','{$signup_account}','{$signup_password}','User')";

            $mysqli->query($instruction) or die($mysqli->connect_error);
            return 'complete';
        }
     ?>

<input type='submit' name="submit" value='開始訓練'class="button-30" onclick= "good()" style="font-size: 20px; margin: 20px 45%;" ></input>
</body>
</html>