<?php
session_start();
	include 'functions.php';

    if (!$_SESSION['isloggedin']) {echo '<script>go("/")</script>';}
	$id = "accounts/". $_SESSION['name']. ".txt";
	
    if (f_optionGet($id,4) < 1) {
        echo '<script>go("/")</script>';
    }
?>

<html>

<head>
	<title>Destruction</title>
	<meta charset="utf-8">
	<style>
		body {
			cursor: default;
			margin: 0px;
			padding: 0px;
			background-color: rgb(220, 220, 220);
		}

		html,
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		#header {
			background-color: rgb(51, 51, 51);
		}

		#header input {
			background: rgb(51, 51, 51);
			border: none;
			padding: 15px 3px;
			color: white;
			font-family: sans-serif;
			text-transform: uppercase;
			font-weight: 700;
			margin-right: 5px;
		}

		#header input:hover {
			background: rgb(204, 88, 12);
		}

		input[type="text"] {
			background-color: rgb(255, 255, 255);
			border: none;
			border-left: 1px solid black;
			font-size: medium;
		}

		input[type="submit"] {
			background-color: black;
			border: 1px solid white;
			color: white;
			transition: 0.22s;
			padding: 1px 10px;
		}

		input[type="submit"]:hover {
			background-color: white;
			color: black;
			border-color: black;
		}
		input[type="text"]:hover {
			border-bottom: 1px solid black;
		}

		ul {
			list-style-type: none;
			counter-reset: num;
		}

		li {
			padding: 6px;
			/* padding-top: 5px; */
			margin-bottom: 12px;
			width: 95%;
			border: 1px solid rgb(160, 160, 160);
			border-radius: 5px;
		}

		li:not(.no-num):before {
			content: counter(num);
			counter-increment: num;
			padding: 5px;
		} 

		#text {
			display: inline;
			padding: 3px;
			margin: 3px;
			padding-bottom: 5px;
		}
	</style>
</head>

<body>
	<div id="header">
		<input value="Atgal" type="button" style="" onclick="go('/')" />
	</div>
	<form method='get' style="border: none; margin: 3px; padding-left: 3px;">
		<input name='name' type="text" placeholder="Name" />
		<input name='add' type='Submit' value='+' style='font-size: medium; margin-right: 5px;' />
	</form>
	<?php
			foreach (scandir("alldetails") as $ln) {
				if ($ln == '.' || $ln == '..') {continue;}
				echo "<ul>";
				echo "<li class='no-num'>";
				echo "<span id='text'>";
				echo $ln. " ";
				echo "<form method='get' style='display: inline'>";
				echo "<input type='text' style='margin-left: 12px;' name='name'/>";
				echo "<input hidden name='path' value='".$ln."'>";
				echo "<input name='addat' type='Submit' value='+' style='font-size: medium; margin-right: 5px;'/>";
				echo "<input hidden name='delid' value='{$ln}'/>";
				echo "<input name='delat' type='submit' value='Delete' style='float: right; font-size: medium'>";
				echo "</form>";
				echo "</span>";
				echo "</li>";
				echo "<ul>";
				foreach (scandir("alldetails/".$ln) as $det) {
					if ($det == '.' || $det == '..') {continue;}
					$det = substr($det,0,strlen($det)-4);
					echo "<li style='margin-top: 5px'>";
					echo "<span id='text'>";
					echo $det." ";
					echo "<form method='get' style='display: inline'>";
					echo "<input hidden name='delid' value='{$ln}/{$det}.txt'/>";
					echo "<input name='del' type='submit' value='Delete' style='float:right; font-size: medium'>";
					echo "</form>";
					echo "</span>";
					echo "</li>";
				}
				echo "</ul>";
				echo "</ul><br/>";
			}
		?>
	<script>
	</script>
</body>

</html>

<?php
	//echo "<script>window.scrollTo(0,".$_GET['scroll'].")</script>";
	if (isset($_GET['add']) && !file_exists("alldetails/".$_GET['name'])) {
		mkdir("alldetails/".$_GET['name']);
		echo "<script>go('/detailadd.php?scroll='+window.pageYOffset)</script>";
	}
	if (isset($_GET['delat'])) {
		foreach (scandir("alldetails/".$_GET['delid']) as $dir) {
			if ($dir == '.' || $dir == '..') {continue;}
			unlink("alldetails/".$_GET['delid']."/".$dir);
		}
		rmdir("alldetails/".$_GET['delid']);
		echo "<script>go('/detailadd.php?scroll='+window.pageYOffset)</script>";
	}
	if (isset($_GET['del'])) {
		unlink("alldetails/".$_GET['delid']);
		echo "<script>go('/detailadd.php?scroll='+window.pageYOffset)</script>";
	}
	if (isset($_GET['addat'])) {
		fclose(fopen("alldetails/".$_GET['path']."/".$_GET['name'].".txt",'w'));
		echo "<script>go('/detailadd.php?scroll='+window.pageYOffset)</script>";
	}
?>