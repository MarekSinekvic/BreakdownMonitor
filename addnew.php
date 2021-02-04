<?php
    session_start();
    include 'functions.php';
    $linec = 0;
    $nodec = 0;

    $lineclk = 0;
    if (!$_SESSION['isloggedin']) {echo '<script>go("/")</script>';}
    $id = "accounts/". $_SESSION['name'] . ".txt";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Add new</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script>
        function reset() {
            document.body.innerHTML = "";
        }
    </script>
    <style>
        * {
            padding: 0px;
            margin: 0px;
        }
        body {
            background-color: rgb(220, 220, 220);
        }

        .select_button {
            width: 190px;
            height: 133px;
            display: "grid";
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-columns: repeat(3, 1fr);
            grid-column-gap: 150px;
            margin: 10px;
            border: 1px solid black;
            background-color: rgb(51, 51, 51);
            color: white;
            font-family: sans-serif;
            font-size: 15px;
            letter-spacing: 0.6px;
            box-shadow: 0px 0px 10px 0px #A1A3C4;
            white-space: normal;
        }

        #header {
            background-color: rgb(51, 51, 51);
            color: white;
            font-family: sans-serif;
            letter-spacing: 0.7px;
        }

        #header input {
            background-color: rgb(51, 51, 51);
            color: white;
            border: none;
            height: 35.5px;
            padding: 0px 5px;
            padding: 10px;
            text-transform: uppercase;
        }

        #header input:hover {
            background-color: rgb(204, 88, 12);
        }

        #head {
            background-color: rgb(51, 51, 51);
            color: white;
        }

        #btn {
            background-color: rgb(51, 51, 51);
            color: white;
            border: none;
            font-family: sans-serif;
            text-transform: uppercase;
            font-weight: 700;
            padding: 15px 3px;
        }

        #btn:hover {
            background-color: rgb(204, 88, 12);
        }

        #content {
            padding-left: 10px;
            font-family: sans-serif;
        }

        #select_button {
            border: 2px solid rgb(155, 155, 155);
            border-radius: 4px;
            padding: 5px;
        }

        #send_button {
            background-color: white;
            border: 1px solid black;
        }

        #send_button:hover {
            box-shadow: inset 0px 0px 4px 0px rgba(0, 0, 0, 0.75);
        }
    </style>
</head>

<body>
    <?php //lineid | nodeid | detid
        if (!isset($_GET['lineid']) && !isset($_GET['detid'])) {
            echo '<div id="head">';
            echo '<input id="btn" value="Atgal" type="button" style="" onclick="go(\'/\')"/>';
            echo '</div>';
            echo "<hr/><br/>";
            foreach (scandir("alldetails") as $det) {
                if ($det == '.' || $det == '..') {continue;}

                echo "<form method='get' style='display: inline;'>";
                echo '<input hidden name="lineid" value="'.$det.'">';
                echo '<input name="'.$det.'" type="submit" value="'.$det.'" class="select_button">';
                echo "</form>";
            }
        } else {
            if (isset($_GET['lineid']) && !isset($_GET['detid'])) {
                echo '<div id="head">';
                echo '<input id="btn" value="Atgal" type="button" style="" onclick="go(\'/\')"/>';
                echo '</div>';
                echo "<hr/><br/>";
                foreach (scandir("alldetails/".$_GET['lineid']) as $det) {
                    if ($det == '.' || $det == '..') {continue;}
                    $det = substr($det,0,strlen($det)-4);
                    echo "<form method='get' style='display: inline;'>";
                    echo '<input hidden name="lineid" value="'.$_GET['lineid'].'">';
                    echo '<input hidden name="detid" value="'.$det.'">';
                    echo '<input name="'.$det.'" style="text-overflow: clip" type="submit" value="'.$det.'" class="select_button">';
                    echo "</form>";
                }
            }
            if (isset($_GET['detid']) && isset($_GET['lineid'])) {
                $date = date("j.m.Y");
                $name = f_optionGet($id,1);
                $ip = f_optionGet($id,4);

                $ids = [
                    $_GET['lineid'],
                    $_GET['detid']
                ];
                echo <<<HTML
                    <div id="header">
                        <input value="Atgal" type="button" style="float: right; font-weight: 700;" onclick="go('/')"/>
                        <span>
                            Pastaba padaryta: $name<br/>
                            Komentaro data: $date
                        </span><br/>
                    </div><br/>
                    <div id="content">
                        <div style="border: 2px solid rgb(155,155,155); border-radius: 4px; padding: 5px; width: 33%;">
                            Linija: $ids[0] <br/>
                            Detalė: $ids[1]
                        </div><br/>
                        <form method="post" enctype="multipart/form-data">
                        <label for="img" id="select_button">Pasirinkite nuotraukos failą ( <span id="selected_file"></span> )</label><br/>
                        <input id="img" type="file" name="img" accept=".jpg, .jpeg, .png" style="opacity: 0; overflow: hidden; position: absolute; width: 0.1px; height:0.1px"/><br/>
                        <textarea name="comment" cols="40" rows="7" placeholder="Komentavimas"></textarea><br/><br/>
                        <input hidden name="lineid" value="{$ids[0]}"/>
                        <input hidden name="detid" value="{$ids[1]}"/>
                        <input hidden name="name" value="{$name}"/>
                        <input hidden name="date" value="{$date}"/>
                        <input name="send" id="send_button" type="Submit" value="Pateikti" style="width: 150px; height: 33px;"/>
                        </form>
                    </div>
                    <script>
                        document.addEventListener("change", function (e) {
                            document.getElementById("selected_file").innerHTML = document.getElementById("img").files[0].name;
                        });
                    </script>
HTML;
            }
        }
    ?>
</body>

</html>

<?php
    if (isset($_POST['send'])) {
        if (!file_exists("imgs/".$_POST['lineid'])) {
            mkdir("imgs/".$_POST['lineid']);
        }
        $dir = "imgs/{$_POST['lineid']}/";
        if (move_uploaded_file($_FILES['img']['tmp_name'], $dir . basename($_FILES['img']['name']))) {
            $newname = $_POST['detid'] . "." . pathinfo(basename($_FILES['img']['name']), PATHINFO_EXTENSION); 
            rename($dir . basename($_FILES['img']['name']), $dir . $newname);
            
            $path = "history/".$_POST['lineid']."/".$_POST['detid'].".txt";
            if (!file_exists($path)) {
                if (!file_exists("history/".$_POST['lineid'])) {
                    mkdir("history/".$_POST['lineid']);
                }
                fclose(fopen($path,'w'));
                f_optionAdd($path,$_POST['name']);
                f_optionAdd($path,$_POST['date']);
                f_optionAdd($path,$_POST['comment']);
                f_optionAdd($path,"");
                f_optionAdd($path,"");
                f_optionAdd($path,"");
                f_optionAdd($path,$dir . $newname);
                f_optionAdd($path,"false","true");
            } else {
                echo "<div style='margin: 10px'>";
                echo "Ši užklausa jau egzistuoja.";
                echo "</div>";
            }
        } else {
            echo "<div style='margin: 10px'>";
            echo "Įveskite nuotrauką";
            echo "</div>";
        }
    }
?>