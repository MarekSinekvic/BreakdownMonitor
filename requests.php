<?php
session_start();
include 'functions.php';
$accpath = "accounts/" . $_SESSION['name'] . ".txt";


if (!$_SESSION['isloggedin']) {
    echo '<script>go("/index.php")</script>';
}
$c = f_optionGet("allins.txt", 1);
echo "<script>console.log('{$c}');</script>";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Request</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        * {
            padding: 0px;
            margin: 0px;
            cursor: default;
        }

        body {
            background: #324761;
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

        #previewimage {
            position: absolute;
        }

        a {
            text-decoration: none;
            color: #57007F;
            transition: 0.3s;
        }

        a:hover {
            color: black;
        }

        #header {
            background-color: rgb(51, 51, 51);
            padding: 0px;
        }

        #header input {
            border: none;
            color: white;
            background-color: rgb(51, 51, 51);
            font-family: sans-serif;
            padding: 20px 3px;
            font-weight: 400;
            letter-spacing: -0.3px;
            text-transform: uppercase;
        }

        #header input[id="oned"]:hover {
            background-color: rgb(204, 88, 12);
        }

        #header input[id="offed"]:hover {}
    </style>
</head>

<body>
    <!--<input type="button"value="Add destruct"onclick="document.location='addnew.php'"/>-->
    <div id="container" style="width: 100%;">
        <div id="header">
            <form method="get" style="display: inline">
                <input type="Submit" id="oned" value="Išvesties" style="float: right; text-transform: uppercase; font-weight: 700" name="goout" />
                <input type="button" id="oned" value="Archyvai" style="float: left;" onclick="go('/archives.php')" />
            </form>
            <div align="center">
                <?php
                if (f_optionGet($accpath, 4) >= 0) {
                    echo '<input type="button" id="oned" value="Pridėti sunaikinti" style="margin-right: 10px;" onclick="document.location=\'addnew.php\'">';
                } else {
                    echo '<input type="button" id="offed" value="Pridėti sunaikinti" style="margin-right: 10px; color: rgb(190,190,190)">';
                }
                if (f_optionGet($accpath, 4) >= 1) {
                    echo '<input type="button" id="oned" value="Manipuliavimas sąskaita" style="margin-right: 10px" onclick="document.location=\'accounts.php\'"/>';
                    echo '<input type="button" id="oned" value="Manipuliavimas detalėmis" style="margin-right: 10px" onclick="document.location=\'detailadd.php\'">';
                }
                if (f_optionGet($accpath, 4) >= 0) {
                    echo '<input type="button" id="oned" value="Pokalbis" style="margin-right: 10px" onclick="document.location=\'messenger.php\'"/><br/>';
                }
                ?>
            </div>
        </div>

        <table style="background-color: white;" width="100%" cellpadding="2" cellspacing="3">
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
                if ($ln == '.' || $ln == '..') {
                    continue;
                }

                foreach (scandir("history/" . $ln) as $det) {
                    if ($det == '.' || $det == '..' || file("history/" . $ln . "/" . $det)[7] == "true") {
                        continue;
                    }

                    $fl = file("history/" . $ln . "/" . $det);
                    $fl[7] = substr($fl[7], 0, strlen($fl[7]));

                    if ($fl[7] == "false") {
                        $name = substr($fl[0], 0, strlen($fl[0]) - 1);
                        $date = substr($fl[1], 0, strlen($fl[1]) - 1);
                        $id = "history/" . $ln . "/" . $det;
                        $rep = $fl[7];
                        $image = substr($fl[6], 0, strlen($fl[6]) - 1);
                        $how = substr($fl[4], 0, strlen($fl[4]) - 1);
                        echo "<tr style='background-color: rgb(235,235,235)' onclick='show(\"$name\", \"$date\", \"$id\", \"$image\", \"$how\",\"$rep\")'>";
                    }

                    if ($fl[7] == "true") {
                        $name = substr($fl[0], 0, strlen($fl[0]) - 1);
                        $date = substr($fl[1], 0, strlen($fl[1]) - 1);
                        $id = "history/" . $ln . "/" . $det;
                        $rep = $fl[7];
                        $image = substr($fl[6], 0, strlen($fl[6]) - 1);
                        $how = substr($fl[4], 0, strlen($fl[4]) - 1);
                        echo "<tr onclick='show(\"$name\", \"$date\", \"$id\", \"$image\", \"$how\", \"$rep\")'>";
                    }

                    $det = substr($det, 0, strlen($det) - 4);
                    echo "<td class='panel'>";
                    echo $ln;
                    echo "</td>";
                    echo "<td class='panel'>";
                    echo $det;
                    echo "</td>";

                    //echo $ln . ", " . $det . "<br/>";
                    for ($i = 0; $i < count($fl) - 1; $i++) {
                        echo "<td class='panel'>";

                        if ($i == 6) {
                            echo "<a href='" . $fl[$i] . "'>";
                            echo "Vaizdas";
                            echo "</a>";
                        } else {
                            echo $fl[$i];
                        }

                        echo "</td>";
                    }
                }
            }
            ?>
        </table>
    </div>
    <div align="left" id="editor">
        <input type="button" class="btn" style="float: right; margin-right: -18px; padding: 2px;" value="X" onclick="hide()" />
        <form method="get">
            <?php
            if (f_optionGet($accpath, 4) >= 0) {
                echo '<input hidden name="path" id="path"/>';
                echo '<input hidden name="imgpath" id="imgpath">';

                if (f_optionGet($accpath, 4) >= 1) {
                    echo '<input name="Name" placeholder="Name" id="name"/><br/>';
                    echo '<input name="Date" placeholder="Date" id="date"/><br/>';
                }

                echo '<textarea name="Howrepair" placeholder="Kaip suremontuota" id="how"></textarea><br/><input name="repair" id="rep" type="checkbox">Suremontuota?<br/><input class="btn" name="change" type="submit" value="Redaguoti" style="padding: 0px 7px"/>';

                if (f_optionGet($accpath, 4) >= 1) {
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
        f_optionRewrite($_GET['path'], 4, f_optionGet($accpath, 1));
        f_optionRewrite($_GET['path'], 5, $_GET['Howrepair']);
        f_optionRewrite($_GET['path'], 6, date("j.m.Y"));
        f_optionRewrite($_GET['path'], 8, "true", true);
    }

    if (!isset($_GET['repair'])) {
        f_optionRewrite($_GET['path'], 4, "");
        f_optionRewrite($_GET['path'], 5, "");
        f_optionRewrite($_GET['path'], 6, "");
        f_optionRewrite($_GET['path'], 8, "false", true);
    }

    echo '<script>go("/requests.php")</script>';
}

if (isset($_GET['delete'])) {
    unlink($_GET['path']);
    unlink($_GET['imgpath']);
    echo '<script>go("/requests.php")</script>';
}
if (isset($_GET['goout'])) {
    $_SESSION['isloggedin'] = false;
    f_optionRewrite("accounts/" . $_SESSION['name'] . ".txt", 3, "false");
    $c = f_optionGet("allins.txt", 1);
    $c -= 1;
    f_optionRewrite("allins.txt", 1, $c);
    echo "<script>go('/')</script>";
}

?>