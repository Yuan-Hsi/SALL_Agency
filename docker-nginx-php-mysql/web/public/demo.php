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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css" integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
    <title><?php echo "SALL Agency"; ?></title>
</head>

<body  bgcolor = "EDEDED" style ="margin-left:120px;margin-right:120px">

    <div class = "header" >
                <a href="index.php"><img src="./img/logo(2).png" alt="logo" style=" width:90.2px ;height:58.3px;"></a>
                <div class = "pure-menu pure-menu-horizontal portal" style="margin-left: 20px">
                    <ul class="pure-menu-list">
                        <li class="pure-menu-item">
                            <a href="index.php" class="pure-menu-link"><p class = "portal_name" >Home </p></a>
                        </li>
                        <li class="pure-menu-item">
                            <a href="#" class="pure-menu-link"><p class = "portal_name" >Demo</p></a>
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

    <div class = "section_3" style = "background : #eace5e; height : 300px"> <!--績效表-->
          
    </div>
    
    <div class = "user_content" style = "margin-top:30px"> <!--Content-->

        <div class = "menu" style = "width :330px; height:700px"> <!--左選單-->
        
        </div>

        <div class="wrapper" style = "margin-left: 30px; width: 780px">
            <div class = "agent_mode">One</div>
            <div class = "agent_mode">Two</div>
            <div class = "agent_mode">Three</div>
            <div> </div>
            <div> </div>
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
    ?>

    <div class = "bottom" style = "height: 200px">

        <p id = "test_1"> 10 </p>
        <p id = "test_2"> 20 </p>
    <script>
		const api_url = 'http://localhost:6001/data_preprocessing';
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