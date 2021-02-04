<?php
    session_start();
    include "functions.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokalbis</title>

    <style>
        * {
            padding: 0px;
            margin: 0px;
            background-color: rgb(220, 220, 220);
            cursor: default;
            font-family: Arial;
        }

        #header {
            background-color: rgb(51, 51, 51);
            width: 100%;
        }

        .select_button {
            background-color: rgb(51, 51, 51);
            border: none;
            color: white;
            text-transform: uppercase;
            font-weight: 700;
            padding: 20px 3px;
        }

        .select_button:hover {
            background-color: rgb(204, 88, 12);
        }

        #input textarea {
            border: none;
            padding: 6px;
            width: calc(100% - 83px);
            border-radius: 4px;
            font-family: Arial;
            font-size: medium;
        }

        #input input[type="Submit"] {
            border: none;
            padding: 5px 16px;
            background-color: rgb(86, 86, 86);
            color: white;
        }

        #input input[type="Submit"]:hover {
            background-color: rgb(51, 51, 51);
            border-bottom: 1px solid white;
            border-right: 1px solid white;
        }

        #messages {
            white-space: normal;
        }

        .messageother {
            background-color: rgb(51, 51, 51);
            color: black;
            padding: 14px;
            margin: 8px 0px;
            color: white;
            white-space: normal;
        }

        .messageuser {
            background-color: rgb(51, 51, 51);
            color: black;
            padding: 14px;
            margin: 8px 0px;
            color: white;
            text-align: right;
            width: auto;
            white-space: normal;
        }

        .messageuser input,
        .messageother input {
            border: none;
            background-color: rgb(86, 86, 86);
            color: white;
            padding: 2px;
            float: left;
            border: none;
        }

        .messageuser input:hover,
        .messageother input:hover {
            background-color: rgb(51, 51, 51);
            border-right: 1px solid white;
            border-bottom: 1px solid white;
        }

        .messageother input {
            float: right;
        }
    </style>
</head>

<body>
    <div id="header">
        <input type="button" value="Atgal" class="select_button" onclick="go('/')" />
    </div>
    <div id="content" style="margin-top: 20px;">
        <div id="chat">
            <div id="messages">
                <?php
                    foreach (scandir("chat") as $fl) {
                        if ($fl == '.' || $fl == '..') {continue;}
                        $name = substr(f_optionGet("chat/".$fl,1),0,strlen(f_optionGet("chat/".$fl,1)));
                        $text = f_optionGet("chat/".$fl,2);
                        if (strtolower($name) == strtolower($_SESSION['name'])) {
                            echo '<div class="messageuser" style="word-wrap: break-word">';
                            if (f_optionGet("accounts/".$_SESSION['name'].".txt",4) >= 1) {
                                echo <<<HTML
                                    <form method="get">
                                        <input type="text" name="id" value="{$fl}" hidden/>
                                        <input type="submit" name="erase" value="X"/>
                                    </form>
HTML;
                            }
                            echo '<b style="background-color: rgb(51,51,51); text-transform: uppercase;">'.$name.'</b><br />'.$text;
                            echo '</div>';
                        } else {
                            echo '<div class="messageother" style="word-wrap: break-word">';
                            if (f_optionGet("accounts/".$_SESSION['name'].".txt",4) >= 1) {
                                echo <<<HTML
                                    <form method="get">
                                        <input type="text" name="id" value="{$name}" hidden/>
                                        <input type="submit" name="erase" value="X"/>
                                    </form>
HTML;
                            }
                            echo '<b style="background-color: rgb(51,51,51); text-transform: uppercase;">'.$name.'</b><br />'.$text;
                            echo '</div>';
                        }
                    }
                ?>

            </div>

            <div id="input" style="background-color: rgb(51,51,51); padding: 7px; margin-top: 20px;">
                <form method="get">
                    <!--<input type="text" placeholder="Pranešimo tekstas" name="text" />-->
                    <textarea style="width: 100%; resize: none;" name="text" rows="2"
                        placeholder="Pranešimo tekstas"></textarea><br />
                    <input type="Submit" name="send" value="Pateikti" style="width: 100%" />
                </form>
            </div>
        </div>


    </div>
    <script>
    </script>
</body>

</html>
<?php
    if (isset($_GET['send'])) {
        $id = count(scandir("chat"))-2;
        $path = "chat/mess".$id.".txt";
        f_create($path);
        f_optionAdd($path, $_SESSION['name']);
        f_optionAdd($path, $_GET['text']);
        echo "<script>go('/messenger.php')</script>";
    }
    if (isset($_GET['erase'])) {
        f_delete('chat/'.$_GET['id']);
        $id = substr($_GET['id'],4);
        for ($i = 0; $i < strlen($id); $i++) {
            if ($id[$i] == '.') {
                $id = substr($id,0,$i);
                break;
            }
        }
        //rename("chat/mess".strval($id+1).".txt","chat/mess".strval($id).".txt");
        for ($i = $id+1; $i < count(scandir("chat"))-1; $i++) {
            rename("chat/mess".strval($i).".txt", "chat/mess".strval($i-1).".txt");
        }
        //echo "<script>go('/messenger.php')</script>";
    }
?>