<?php
    session_start();
    include 'functions.php';
    if (!$_SESSION['isloggedin']) {echo 'go("/");';}
    $id = $id = "accounts/". $_SESSION['name'] . ".txt";
    if (f_optionGet($id,4) < 1) {
        echo '<script>go("/")</script>';
    }

    $data = [];
    $i = 0;
    while (true) {
        $path = "accounts/acc.".$i.".txt";
        if (file_exists($path)) {
            $data[$i] = [
                f_optionGet($path,1),
                f_optionGet($path,2),
                f_optionGet($path,3),
                f_optionGet($path,4),
                f_optionGet($path,5)
            ];
        } else {break;}
        $i++;
    } 
    //echo $data[0][0];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Request</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        html body {
            cursor: default;
            margin: 0px;
            padding: 0px;
            background-color: rgb(220, 220, 220);
        }

        body {
            font-family: "Arial";
        }

        .accs {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-columns: repeat(2, 1fr);
            grid-column-gap: 10px;
        }

        #edit div input {
            background: none;
            color: white;
            border: 1px solid white;
            padding: 3px;
            font-size: medium;
        }

        #edit {
            padding: 6px;
        }

        #back {
            text-transform: uppercase;
            border: none;
            background: none;
            color: white;
            height: 5.6%;
        }

        #back:hover {
            background-color: rgb(204, 88, 12);
        }

        #header {
            background-color: rgb(51, 51, 51);
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div id="header">
        <input value="atgal" id="back" type="button" style="float: right;" onclick="go('/')" />
        <form method="get" id="edit">
            <div style="display: inline">
                <input placeholder="Vardas" name="name" id="namein" />
                <input placeholder="Slaptažodis" name="pass" id="passin" />
                <input placeholder="Administratoriaus lygis" name="adml" id="admlin" style="" />
                <input value="Create" name="create" type="Submit" style="padding: 3px 12px;" />
            </div>
        </form>
    </div>
    <div class='accs'>
        <?php
        foreach (scandir("accounts") as $acc) {
            if ($acc == '.' || $acc == '..') {continue;}
            $path = "accounts/".$acc;
            echo "<div class='oneacc' style='background-color: #333333; margin-bottom: 5px; padding: 5px; color: white' align='center'>";
            $data = file($path);
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = substr($data[$i],0,strlen($data[$i])-1);
                if ($data[$i] == "true") {
                    echo "<span class='".$acc."' style='color: #5DB23C'>Prisijungęs</span>";
                    continue;
                }
                if ($data[$i] == "false") {
                    echo "<span class='$acc' style='color: red'>Išėjo</span>";
                    continue;
                }
                echo "<span class='$acc' style=' padding: 3px; margin-left: 4px'>";
                echo $data[$i];
                echo "</span>";
            }
            if ($id != "accounts/".$acc) {
                echo "<form method='get' style='display: inline; margin-left: 7px' action='accounts.php'>";
                
                $acc = substr($acc,0,strlen($acc)-4);
                echo "<input type='Submit' name='del".$acc."' value='Ištrinti'/>";
                //echo 'del'.$acc.", -";
                echo "</form>";
            }

            echo "</div>";
        }
    ?>
    </div>
    <script>
        document.body.addEventListener("mousedown", function (e) {
            if (e.target.className != "acc0" && e.target.className != "acc1" && e.target.className != "acc3" &&
                e.target.className != "acc4") {
                if (e.target.classList[0] == "oneacc") {
                    document.getElementById("namein").value = e.target.childNodes[0].innerHTML;
                    document.getElementById("passin").value = e.target.childNodes[1].innerHTML;
                    document.getElementById("ipin").value = e.target.childNodes[3].innerHTML;
                    document.getElementById("admlin").value = e.target.childNodes[4].innerHTML;
                }
            }
        });
    </script>
</body>

</html>

<?php
    foreach (scandir("accounts") as $acc) {
        if ($acc == '.' || $acc == '..') {continue;}
        $acc = substr($acc,0,strlen($acc)-4);
        if (isset($_GET['del'.$acc])) {
            f_delete("accounts/".$acc.".txt");
            echo "<script>go('accounts.php')</script>";
        }
        if (isset($_GET['chg'.$acc])) {
            
        }
    }
    if (isset($_GET['create'])) {
        if ($_GET['name'] != "") {
            if (!file_exists(("accounts/".$_GET['name'].".txt"))) {
                $path = "accounts/".$_GET['name'].".txt";
                f_create($path);
            } else {
                echo "Аккаунт с таким именем, уже существует";
            }
        } else {
            $path = "accounts/Account.txt";
            f_create($path);
        }
        if ($_GET['name'] != "") {
            f_optionAdd($path,$_GET['name']);
        } else {
            f_optionAdd($path,"Name");
        }
        if ($_GET['pass'] != "") {
            f_optionAdd($path,$_GET['pass']);
        } else {
            f_optionAdd($path,"123456");
        }
        f_optionAdd($path,"false");
        if ($_GET['adml'] != "") {
            f_optionAdd($path,$_GET['adml']);
        } else {
            f_optionAdd($path,"0");
        }
        echo "<script>go('accounts.php')</script>";
    }

?>