<?php 
    session_start();
    include 'functions.php';
    if (!$_SESSION['isloggedin']) {
        echo '<script>go("/")</script>';
    }
    $accpath = "accounts/".$_SESSION['name'].".txt";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Archives</title>
    <style>
        * {
            padding: 0px;
            margin: 0px;
            cursor: default;
        }

        body {
            background: #324761;
        }

        a {
            text-decoration: none;
            color: #57007F;
            transition: color 0.3s;
        }

        a:hover {
            color: black;
        }

        #header {
            background: rgb(51, 51, 51);
            padding: 0;
            margin: 0;
            list-style: none;
            position: relative;
            color: white;
        }

        #header input[type="button"] {
            padding: 13px 5px;
            border: none;
            background: rgb(51, 51, 51);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
        }

        #header input[type="button"]:hover {
            background-color: rgb(204, 88, 12);
        }



        #editor {
            background-color: rgb(255, 255, 255);
            position: absolute;
            border: 2px solid rgb(210, 210, 210);
            border-radius: 5px;
            padding: 12px;
            padding-right: 23px;
            left: 50%;
            top: 50%;
            transform: translateX(-50%) translateY(-50%);
            box-shadow: 0px 0px 5px 0px #A1A3C4;
        }

        #editor input,
        textarea {
            background-color: white;
            border: 1.5px solid rgb(200, 200, 200);
        }

        #editor input:hover {
            box-shadow: 0px 0px 9px -3px rgba(0, 0, 0, 0.75);
        }

        #editor textarea:hover {
            box-shadow: 0px 0px 9px -3px rgba(0, 0, 0, 0.75);
        }

        #editor .btn {
            background-color: white;
            border: 1px solid rgb(190, 190, 190);
        }

        #editor .btn:hover {
            box-shadow: inset 0px 0px 3px 0px rgba(0, 0, 0, 0.75);
        }
    </style>
</head>

<body>
    <div id="header" style="">
        <input type="button" onclick="go('/');" value="Atgal" />
        <select hidden style="background:rgb(51, 51, 51); color: white; margin-left: 5px;">
            <option>Line</option>
            <option>Date</option>
        </select>
    </div>
    <table width="100%" style="background-color: white" cellpadding="2" cellspacing="3">
        <tr style='background-color: rgb(235,235,235); font-family: Arial;'>
            <th>Linija</th>
            <th>Detalė</th>
            <th>Vardas</th>
            <th>Data</th>
            <th>Komentavimas</th>
            <th>Kas taiso</th>
            <th>Kaip suremontuota</th>
            <th>Kada remontuota</th>
            <th>Vaizdas</th>
        </tr>
        <?php 
                foreach (scandir("history") as $ln) {
                    if ($ln=='.'|| $ln=='..') {
                        continue;
                    }

                    foreach (scandir("history/".$ln) as $det) {
                        if ($det=='.'|| $det=='..' || file("history/".$ln."/".$det)[7]=="false") {
                            continue;
                        }
                        
                        $fl=file("history/".$ln."/".$det);
                        $fl[7]=substr($fl[7], 0, strlen($fl[7]));
                        $name=substr($fl[0], 0, strlen($fl[0])-1);
                        $date=substr($fl[1], 0, strlen($fl[1])-1);
                        $id="history/".$ln."/".$det;
                        $rep=$fl[7];
                        $image=substr($fl[6], 0, strlen($fl[6])-1);
                        $how=substr($fl[4], 0, strlen($fl[4])-1);

                        echo "<tr style='background-color: rgb(235,235,235);' onclick='show(\"$name\", \"$date\", \"$id\", \"$image\", \"$how\", \"$rep\")'>";
                        

                        $det=substr($det, 0, strlen($det)-4);
                        echo "<td class='panel'>";
                        echo $ln;
                        echo "</td>";
                        echo "<td class='panel'>";
                        echo $det;
                        echo "</td>";

                        //echo $ln . ", " . $det . "<br/>";
                        for ($i=0; $i < count($fl)-1; $i++) {
                            echo "<td class='panel'>";

                            if ($i==6) {
                                echo "<a href='".$fl[$i]."'>";
                                echo "Vaizdas";
                                echo "</a>";
                            }

                            else {
                                echo $fl[$i];
                            }

                            echo "</td>";
                        }
                    }
                }
            ?>
    </table>
    <div align="left" id="editor">
        <input type="button" class="btn" style="float: right; margin-right: -18px; padding: 2px;" value="X" onclick="hide()" />
        <form method="get">
            <?php 
                if (f_optionGet($accpath, 4) >= 0) {
                    echo '<input hidden name="path" id="path"/>';
                    echo '<input hidden name="imgpath" id="imgpath">';

                    if (f_optionGet($accpath, 4) >=1) {
                        echo '<input name="Name" placeholder="Name" id="name"/><br/>';
                        echo '<input name="Date" placeholder="Date" id="date"/><br/>';
                    }
                
                    echo '<textarea name="Howrepair" placeholder="Kaip suremontuota" id="how"></textarea><br/><input name="repair" id="rep" type="checkbox">Suremontuota?<br/><input class="btn" name="change" type="submit" value="Redaguoti" style="padding: 0px 7px"/>';
                
                    if (f_optionGet($accpath, 4) >=1) {
                        echo '<input class="btn" name="delete" type="submit" value="Ištrinti" style="margin-left: 10px; padding: 0px 7px"/>';
                    }
                }

            ?>
        </form>
    </div>
    <script>
        document.getElementById("editor").style.display = "none";

        function show(name, date, id, imgpath, how, r) {
            document.getElementById("editor").style.display = "inline";
            document.getElementById("name").value = name;
            document.getElementById("date").value = date;
            document.getElementById("path").value = id;
            document.getElementById("imgpath").value = imgpath;
            document.getElementById("how").value = how;
            if (r == "true") document.getElementById("rep").checked = true;
            if (r == "false") document.getElementById("rep").checked = false;
        }

        function hide() {
            document.getElementById("editor").style.display = "none";
        }
    </script>
</body>

</html>

<?php 
if (isset($_GET['change'])) {
    //echo $_GET['name'].", ";
    // echo $_GET['img'];
    f_optionRewrite($_GET['path'], 1, $_GET['Name']);
    f_optionRewrite($_GET['path'], 2, $_GET['Date']);

    if (isset($_GET['repair'])) {
        f_optionRewrite($_GET['path'], 4, f_optionGet($id, 1));
        f_optionRewrite($_GET['path'], 5, $_GET['Howrepair']);
        f_optionRewrite($_GET['path'], 6, date("j.m.Y"));
        f_optionRewrite($_GET['path'], 8, "true", true);
    }

    if ( !isset($_GET['repair'])) {
        f_optionRewrite($_GET['path'], 4, "");
        f_optionRewrite($_GET['path'], 5, "");
        f_optionRewrite($_GET['path'], 6, "");
        f_optionRewrite($_GET['path'], 8, "false", true);
    }

    echo '<script>go("/archives.php")</script>';
}

if (isset($_GET['delete'])) {
    unlink($_GET['path']);
    unlink($_GET['imgpath']);
    echo '<script>go("/archives.php")</script>';
}

?>