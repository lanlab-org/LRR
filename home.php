<?php

include 'Header.php';
  $student_id=$_SESSION["user_student_id"];
    $group_id=$_SESSION["user_group_id"];
  $c_date=  date("Y-m-d H:i");

    ?>
<!DOCTYPE html>
<html >
<head>
	<meta charset="utf-8"/>
	<title>Recherche</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	<style>
		table{width:100%;border-collapse:collapse}
		table tr,table th,table td{border:1px solid black;}
		table tr td{text-align:center;padding:1em;}
	</style>
</head>
<body>
	<div class="jumbotron text-center">
  <h1> Lab Report Repository System  </h1>
  <p>a new Version for a better future !</p>

  <!-- Grey with black text -->
<nav class="navbar navbar-expand-sm bg-light navbar-light">
  <ul class="navbar-nav">
     <li class="nav-item">
      <a class="nav-link" href="#">Home</a>
    </li>

	<li class="nav-item active">
      <a class="nav-link" href="#">Courses</a>
    </li>


    <li class="nav-item">
      <a class="nav-link disabled" href="#">Disabled</a>
    </li>
  </ul>
</nav>

<!-- Black with white text -->
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">...</nav>

<!-- Blue with white text -->


</div>

<div class="container">


 <div class="row">
    <div class="col-sm-4">
       <div class="col-md-4">
        <br><br>
		 <img src="../my_lrr/logo_text.png" style="width">


        <br><br>

		</div>
    </div>
    <div class="col-sm-4">
     <form method='post'>


		<div class="form-group">
		<br><br>
			<a>Find course by : </a>
			  <select class="form-control" id="sel1" name="identif">
				<option>Course Name</option>
				<option>Course Code</option>
			  </select>
		</div>
		<br><br>
		<input type='text' placeholder='recherche' name="recherche_valeur"/>
		<input type='submit' value="Search" onclick="resp();"/> <label id="resp"></label>
	</form>
    </div>
    <div class="col-sm-4">
     <?php include('verif-form.php');



	?>


	<table>
		<thead>
			<tr><th>Course Name</th><th>Faculty</th><th>Course Code</th><th>URL</th></tr>

		</thead>
		<tbody>
			<?php




				if(isset($_POST['recherche_valeur'])){

				 if($_POST['identif'] == "Course Name")	{

						$sql='select * from courses_table';
					$params=[];
					$sql.=' where Course_name like :Course_name';
					$params[':Course_name']="%".addcslashes($_POST['recherche_valeur'],'_')."%";

				$resultats=$connect->prepare($sql);
				$resultats->execute($params);
				if($resultats->rowCount()>0){
					while($d=$resultats->fetch(PDO::FETCH_ASSOC)){
					?>
						<tr><td><?=$d['Course_Name']?></td><td><?=$d['Faculty']?></td><td><?=$d['Course_Code']?></td><td> <a class="nav-link" href="<?=$d['URL']?>">Link</a></td></tr>
					<?php
					}
					$resultats->closeCursor();
				}
				else echo '<tr><td colspan=4>Find Courses Area</td></tr>'.
				$connect=null;
				}
				if($_POST['identif'] == "Course Code")	{
				$c_k=$_POST['identif'] ;
				$sql='select * from courses_table';
				$params=[];

				$sql.=' where Course_Code like :Course_Code';
				$params[':Course_Code']="%".addcslashes($_POST['recherche_valeur'],'_')."%";

				$resultats=$connect->prepare($sql);
				$resultats->execute($params);
				if($resultats->rowCount()>0){
					while($d=$resultats->fetch(PDO::FETCH_ASSOC)){
					?>
						<tr><td><?=$d['Course_Name']?></td><td><?=$d['Faculty']?></td><td><?=$d['Course_Code']?></td><td> <a class="nav-link" href="<?=$d['URL']?>">Link</a></td></tr>
					<?php
					}
					$resultats->closeCursor();
				}
				else echo '<tr><td colspan=4>Find Courses Area</td></tr>'.
				$connect=null;
				}

			}




			?>
		</tbody>
	</table>
    </div>
  </div>
</div>





</body>
</html>
