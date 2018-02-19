<?php session_start();

if(isset($_SESSION["username"])){

echo "<h3>Hai.Welcome., <br> " . $_SESSION["username"] . "<h3><br>Are you sure you want to logout?<h3><br>";

}else{

	header("Location:login.php");
}

?>

<link href="../css/style2.css" rel="stylesheet">
<body class="home">

<ul>
<a href = "logout.php">Logout</a><br><br>
</ul>

<a href = "addproduct.php">+add</a><br><br>

<hr>



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


function addproductData($userData){

		try{
		      $connection = new PDO("mysql:host=" . "localhost" . ";dbname=" . "myshop","root","root");
		      $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		      $query = "INSERT INTO product (email,name,color,price,user_id) VALUES(:email,:name,:color,:price,:user_id)";
		      $stmt = $connection->prepare($query);
		     
		      $stmt->bindParam(":email",$userData["email"]);
		      $stmt->bindParam(":name",$userData["name"]);
		      $stmt->bindParam(":color",$userData["color"]);
		      $stmt->bindParam(":price",$userData["price"]);
		      $stmt->bindParam(":user_id",$userData["userid"]);
		      $stmt->execute();

		  
		       
		    } catch (PDOException $e){
		      die("Failed to connect with Mysql Database" . $e->getMessage());
		    }

		 

}
 
 function getproducts(){
      
      try{
         
         $connection = new PDO("mysql:host=" ."localhost" .";dbname="."myshop","root","root");
         $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
        $query = "SELECT * FROM  product WHERE user_id = :user_id" ;

        $stmt = $connection->prepare($query);
        $stmt->bindParam(":user_id",$_SESSION["userid"]);
		   $stmt->execute();

		 $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $result;
		     
      }catch(PDOException $e){
	   die("Failed to connect with Mysql Database" . $e->getMessage());
		    }
   
}






if(isset($_POST["addproduct"])){

    $sanitized = sanitizeInputs($_POST);
       
        addproductData($sanitized);
        

} 

 


 
 $productlist = getproducts(); 


     echo "<h1>ProductList</h1>"; 
      foreach($productlist as $key => $value){
        echo "<h2>ProductName</h2><h4>";
            echo  $value["name"]."</h4></br>";
        echo "<h2>Color</h2><h4>";
            echo  $value["color"]."</h4></br>";
        echo "<h2>Price</h2><h4>";
            echo  $value["price"]."</h4></br>";   
        }      
?>

</body>




