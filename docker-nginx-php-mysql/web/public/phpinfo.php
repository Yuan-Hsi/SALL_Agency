<?php
session_start();
if(!isset($_SESSION['user_name'])){
    header("location:index.php");
    exit;
}?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <body>
    <form class="form" action="index.php" method="post">
            <button type="submit" name="button"> 回首頁 </button>
    </form>
    </body>
</html>
<?php
phpinfo();
?>