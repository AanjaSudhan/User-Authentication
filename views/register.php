

<?php

function sanitizeInputs($postData){

  $sanitizeArray=[];

	foreach($postData as $key => $value){
     
     $data = trim($value);
     $data = stripslashes($data);
     $data = htmlspecialchars($data,ENT_QUOTES,'UTF-8');
     $sanitizeArray[$key]= $data;

	}
  return $sanitizeArray;

}

function  validateInputs($sanitized){

  $error =[];
  $fieldValue =[];


   foreach($sanitized as $key => $value){

	   	switch($key){

	   		case "email":
         if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
              	$error['emailError']="Email is NOT valid";
              }
               $fieldValue[$key] = $value;
              
	   		break;

        case "name":
         if(empty($value)){
            $error['nameError']="name cannot be empty";

         }
         $fieldValue[$key] = $value;

        break;

        case "password":
        if(!filter_var($value,FILTER_VALIDATE_REGEXP,["options" => array("regexp" => "^\d^")])){

          $error['passwordError'] = "Password must contain at least one digit";

        }
       $fieldValue[$key] = $value;
        break;

	   	}
   	
   }

  return [$error,$fieldValue];
 
}

  function duplicateEmailCheck($email){


    try{
      $connection = new PDO("mysql:host=" . "localhost" . ";dbname=" . "myshop","root","root");
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "SELECT email FROM user WHERE email =  :email";
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":email",$email);
      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if(empty($result)){
          return true;
            
        }else{
          return false;
        }

    } catch (PDOException $e){
      die("Failed to connect with Mysql Database" . $e->getMessage());
    }

 }


 function registerUser($userData){
      

    try{
      $connection = new PDO("mysql:host=" . "localhost" . ";dbname=" . "myshop","root","root");
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "INSERT INTO user (name,email,password,hash_key,active) VALUES(:name,:email,:password,:hash_key,:active)";
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":name",$userData["name"]);
      $stmt->bindParam(":email",$userData["email"]);
      $stmt->bindParam(":password",$userData["password"]);
      $stmt->bindParam(":hash_key",$userData["hash_key"]);
      $stmt->bindParam(":active",$userData["active"]);
      $stmt->execute();


      $id = $connection->lastInsertId();


      $activateURLParams=[

        "email" => $userData["email"],
        "hash_key" => $userData["hash_key"],
        "id" => $id
                ];

         sendVerificationEmail($activateURLParams);

       
    } catch (PDOException $e){
      die("Failed to connect with Mysql Database" . $e->getMessage());
    }

 }

function  sendVerificationEmail($params){


  $headers = "From:Admin\r\n";

  $headers .= "MIME-Version:1.0\r\n";
  $headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";

  $to = $params["email"];

  $subject = "Please activate your account in myshop";
 
  $actual_Link = "http://localhost/myshop/activate.php?id=" .$params["id"]."&hash_key=".$params["hash_key"]."&email=".$params["email"];
 

 $message = "Please click this link to activate your account" ."<a href =" . $actual_Link .">Click here</a>";


     if(mail($to,$subject,$message,$headers)){

        echo "Email has been sent.Please check inbox to activate your account.";
     }

}




if(isset($_POST["signup-form-submitted"])){

   $sanitized = sanitizeInputs($_POST);

  $validateError = validateInputs($sanitized)[0];
  $validateValues = validateInputs($sanitized)[1];

  

      if(empty($validateError)){

         if( duplicateEmailCheck($validateValues["email"]) ){

          $options =["cost"=> 12];


         $validateValues["password"] = password_hash($validateValues["password"] ,PASSWORD_BCRYPT, $options);

         $validateValues["hash_key"] = sha1(mt_rand(10000, 99999) . time() . $validateValues["email"]);
        

        $validateValues["active"] = 0;

        registerUser($validateValues);

  

         }else{

          $validateError["emailError"]= "Email is already register";

         }
    
     }

  

} 

if(empty($validateValues)){
 
   $validateValues= ["name"=>"","email"=>""];
 }
 
?>


<link rel="stylesheet" href="../css/style.css">

<!DOCTYPE html>
<html>
<head>  
	<title>Register Application</title>
</head>
<body>
 <section class ="style"> 
  
    <h1 style ="color:white">Registration Form</h1>
   

  <ul>
	<form action="register.php" method="post" novalidate>

	<div style ="color:blue"><br>
  	<label>UserName:</label><br>	
  	<input type ="text" name="name" value="<?php echo $validateValues["name"]; ?>" placeholder="Enter your name"/><br>
    <?php if(isset($validateError['nameError'])){ ?>
       <div style ="color:red"><?php echo $validateError['nameError']; ?></div>

     <?php }?>

  </div>
  <div style ="color:blue">
  	<label>Email:</label><br>	
  	<input type ="text" name="email" value="<?php echo $validateValues["email"]; ?>" placeholder="Enter your email"/><br>
      <?php if(isset($validateError['emailError'])){ ?>
        <div style ="color:red"><?php echo $validateError['emailError']; ?></div>

    <?php } ?>

   </div>
   <div style ="color:blue">
      <label>Password:</label><br>	
  	  <input type ="password" name="password" placeholder="Enter your password"><br>
       <?php if(isset($validateError['passwordError'])){ ?>
        <div style ="color:red"><?php echo $validateError['passwordError']; ?></div>

       <?php } ?>

    </div><br>
 
	<input type ="submit"  value= "Signup/Register" name="signup-form-submitted"/>

 
	</form>
</section>
</body>
</html>
</ul>




