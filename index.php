<?php
	session_start();
	include 'functions.php';
	$_SESSIOM['isloggedin'] = false;
	if (isset($_SESSION['isloggedin']) && $_SESSION['isloggedin']) {
		echo '<script>go("/requests.php")</script>';
	}

	if (isset($_GET['send'])) {
		foreach (scandir("accounts") as $acc) {
			if ($acc == '.' || $acc == '..') {continue;}
			$path = "accounts/".$acc;
			$data = file($path);
			$data[0] = substr($data[0],0,strlen($data[0])-1);
			$data[1] = substr($data[1],0,strlen($data[1])-1);
			if ($data[0] == $_GET['login'] && $data[1] == $_GET['pass']) {
				$_SESSION['isloggedin'] = true;
				$_SESSION['name'] = $data[0]; 
				f_optionRewrite("accounts/".$data[0].".txt",3,"true");
				$c = f_optionGet("allins.txt",1);
				$c+=1;
				f_optionRewrite("allins.txt",1,$c);
				echo '<script>go("/requests.php")</script>';
			}
		}
	}
?>

<html>
	<head>
		<title>Destruction</title>
		<meta charset="utf-8">
		<style>
			html, body {
				font-family: sans-serif;
				background: #324761;
				cursor: default;
				margin: 0px;
				padding: 0px;
				width: 100%;
				height: 100%;
			}
			#box {
				background-color: #1B191B;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translateX(-50%) translateY(-50%);
				text-align: center;
				width: 300px;
				padding: 40px;
				border-top: 3px solid white;
			}
			#box input[type = "text"], #box input[type = "password"] {
				border: none;
				margin: 20px auto;
				background: none;
				display: block;
				text-align: center;
				border: 2px solid #3498db;
				padding: 14px 10px;
				width: 200px;
				outline: none;
				color: white;
				border-radius: 24px;
				transition: 0.25s;
			}
			#box input[type = "text"]:focus,#box input[type = "password"]:focus {
				width: 280px;
				border-color: #2ecc71;
			}
			#box h1 {
				color: white;
				text-transform: uppercase;
				font-weight: 500px;
			}

			#box input[type="submit"] {
				border: none;
				margin: 20px auto;
				background: none;
				display: block;
				text-align: center;
				border: 2px solid #2ecc71;
				padding: 14px 40px;
				outline: none;
				color: white;
				border-radius: 24px;
				transition: 0.25s;
				cursor: pointer;
			}
			#box input[type="submit"]:focus {
				background-color: #2ecc71;
			}
			canvas {
				position: absolute;
				left: 0px;
				top: 0px;
			}
		</style>
	</head>
	<body>
		<canvas id="canvas"></canvas>
		<form method="GET" id="box" align="center">
			<h1>Prisijungimas</h1>
			<input name="login" type="text" placeholder="Prisijungimo vardas" autocomplete="off"/>
			<input name="pass" type="password" placeholder="Slaptažodis"/>
			<input name="send" value="Prisijungti" type="submit" id="goinbutton"/>
		</form>
		<script src="JS mouseAPI new.js" type="text/javascript"></script>
		<script src="particles.js" type="text/javascript"></script>
	</body>
</html>