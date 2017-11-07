<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Загрузка файла</title>
    <style type="text/css" media="screen">
        * {
            font-family: "Arial", sans-serif;
        }

        label, input {
            display: block;
        }

        .container {
            width: 1000px;
            margin:  0 auto;
        }        
    </style>
</head>
<body>
    <div class="container">
        <form action="admin.php" enctype="multipart/form-data" method="POST">
            <label for="file">Выберите файл</label>
            <input type="file" name="file">
            <input type="submit" name="submit" value="Загрузить">
        </form>
        <?php 
            var_dump($_FILES);
            if (isset($_FILES['file'])) {
                $type = $_FILES['file']['type'];
                $name = $_FILES['file']['name'];
                $tmpName = $_FILES['file']['tmp_name'];
                if ($type == "application/json") {
                    move_uploaded_file($tmpName, "tests/".$name);
                    // echo __DIR__;
                    echo "<p>Файл $name успешно загружен</p>";

                    $json = file_get_contents("tests/".$name);
                    echo $json.PHP_EOL;
                    
                    var_dump(json_decode($json, true));
                }
                else {
                    echo "<p>Файл неверного типа! Допускаются только файлы в формате json.</p>";
                }
            }
        ?>
    </div>
</body>
</html>