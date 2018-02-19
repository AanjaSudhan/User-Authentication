<?php ob_start();

 function sanitizeInputs($postData){

  $sanitizeArray =[];

	foreach($postData as $key => $value){
     
     $data = trim($value);
     $data = stripslashes($data);
     $data = htmlspecialchars($data,ENT_QUOTES,'UTF-8');
     $sanitizeArray[$key]= $data;

	}

  return $sanitizeArray;

}

  function  validateLoginUser($sanitized){

  $error="";
 


     foreach($sanitized as $key => $value){

	   	switch($key){

	   		case "email":
          if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
              	$error="username or password is invalid";
              }
              
	   		break;
          
          case "password":

	       if(empty($value)){

            $error="username or password is invalid";

	   	    }
	   	    break;

        }
    }

    return $error;
 }


function loginUserData($userData){


	$active = 1;
     
try{
     $connection = new PDO("mysql:host=" . "localhost" . ";dbname=" . "myshop","root","root");
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "SELECT * FROM user WHERE email =  :email AND active = :active";
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":email",$userData["email"]);
      $stmt->bindParam(":active",$active);

      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
     

        if(empty($result)){
           echo "invalid or inactive user";
            
        }else{

        	if(password_verify($userData["password"], $result["password"])){
    
        		     session_start();
        		     $_SESSION["username"] = $result["email"];
                  $_SESSION["userid"] = $result["id"];
        		     header("Location: home.php");

        	}else {

        		echo "password does not match";
        	}
          
        }

    } catch(PDOException $e){
      die("Failed to connect with Mysql Database" . $e->getMessage());
    }

 }


 





if(isset($_POST["submit-login"])){

   $sanitizedUserData = sanitizeInputs($_POST);

   $error =  validateLoginUser($sanitizedUserData);

   if(empty($error)){
   	    
   	    loginUserData($sanitizedUserData);
   }

}


if(isset($_GET["active"])){

echo "<span style = color:blue> Your account has been activated.. you can log in now</span>";

}


?>



<link rel="stylesheet" href="../css/style2.css">



<body class="bodylogin">

 <form  action = "login.php" method ="post" novalidate>
  <section  class="login">
	<h1>Signin/login</h1>
 <ul>

  <?php if(isset($error)){ ?>
      <div style ="color:red"><?php echo $error; ?></div>

 <?php }?>

    </div>

<div style ="color:blue">
	<label for = "email">Email</label><br>
 
	<input type ="text"  name ="email" id = "email" placeholder="Enter your email" reqiured><br>

</div>

<div style ="color:blue">
	<label for ="password">Password</label><br>

	<input type ="password"  name ="password" id ="password" placeholder="Enter your password" reqiured><br><br>

</div>

  <input type ="submit" name ="submit-login" value = "login/Signin">

</ul>
</section>
</form>
</body>



