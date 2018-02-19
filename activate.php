<?php  ob_start();
 
 function activateUser($id){

 	$activate = 1;

 try{
      $connection = new PDO("mysql:host=" . "localhost" . ";dbname=" . "myshop","root","root");
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "UPDATE  user SET active = :active WHERE id = :id";
    
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":active" ,$activate);
      $stmt->bindParam(":id",$id);
     
      $stmt->execute();

       header("Location:views/login.php?active=true");
       exit;
   
     
    } catch (PDOException $e){
      die("Failed to connect with Mysql Database" . $e->getMessage());
    }


 }




 if(isset($_GET["id"]) && isset($_GET["hash_key"]) && isset($_GET["email"])){

    $id = $_GET["id"];
   $hash_key = $_GET["hash_key"];
    $email= $_GET["email"];
    $active = 0;

    try{
      $connection = new PDO("mysql:host=" . "localhost" . ";dbname=" . "myshop","root","root");
      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query = "SELECT * FROM user WHERE id =  :id AND email = :email AND hash_key =:hash_key AND active = :active";
      
      $stmt = $connection->prepare($query);
      $stmt->bindParam(":id" ,$id);
      $stmt->bindParam(":email",$email);
      $stmt->bindParam(":hash_key" ,$hash_key);
      $stmt->bindParam(":active" ,$active);
      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      

     if(empty($result)){

      	echo "Already Activated . you can login now";
      }else{

      	activateUser($id);
      }

     
    
     
    } catch (PDOException $e){
      die("Failed to connect with Mysql Database" . $e->getMessage());
    }

 }
 
   else{

   	echo "Forbidden";
   	die();
   }

 ?>