<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Загрузка файла</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container">
        <nav>
            <ul>
                <li>Загрузка теста</li>
                <li><a href="list.php" title="Список тестов">Список тестов</a></li>
                <li><a href="test.php" title="Прохождение теста">Прохождение теста</a></li>
            </ul>
        </nav>
        <hr>

        <form action="admin.php" enctype="multipart/form-data" method="POST">
            <label for="file">Выберите файл</label>
            <input type="file" name="file">
            <input type="submit" name="submit" value="Загрузить">
        </form>
        <?php 
            // var_dump($_FILES);
            if (isset($_FILES['file'])) {
                $type = $_FILES['file']['type'];
                // $name = $_FILES['file']['name'];
                $tmpName = $_FILES['file']['tmp_name'];
                if ($type == "application/json") {
                    move_uploaded_file($tmpName, "tests/test.json");
                    // echo __DIR__;
                    echo "<p>Файл $name успешно загружен</p>";

                    // $json = file_get_contents("tests/test.json");
                    // echo $json.PHP_EOL;
                    
                    // var_dump(json_decode($json, true));
                }
                else {
                    echo "<p>Файл неверного типа! Допускаются только файлы в формате json.</p>";
                }
            }
        ?>
    </div>
</body>
</html>