<?php session_start();
?>
<link href="../css/style2.css" rel="stylesheet">
<body>
<form  action = "home.php" method ="post" novalidate>

<h1>Add Products</h1>
<ul>
<input type="hidden" name="userid" value= "<?php  echo $_SESSION['userid'];?>">
 
<div style ="color:blue">
	<label for = "email">Email</label><br>
	<input type ="text"  name ="email" id = "email"><br>
</div>

<div style ="color:blue">
	<label for ="name">Name</label><br>
	<input type ="text"  name ="name" id ="name"><br>
</div>

<div style ="color:blue">
	<label for ="color">Color</label><br>
	<input type ="text"  name ="color" id ="color"><br>
</div>
<div style ="color:blue">
	<label for ="price">Price</label><br>
	<input type ="text"  name ="price" id ="price"><br><br>
</div>

  <input type ="submit" name ="addproduct" value = "Add">
</ul>
</form>
</body>
