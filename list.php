<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Список тестов</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="container">
    <nav>
        <ul>
            <li><a href="admin.php" title="Загрузка теста">Загрузка теста</a></li>
            <li>Список тестов</li>
            <li><a href="test" title="Прохождение теста">Прохождение теста</a></li>
        </ul>
    </nav>
    <hr>

    <?php 

        if(!file_exists('tests/test.json')) {
            echo '<p class="alert">Файл теста не загружен!</p>';
            exit;
        }

        $json = file_get_contents('tests/test.json');
        $data = json_decode($json, true);
        $errCount = 0;

        if (!count($data)) {
            echo '<p class="alert">Список вопросов пуст!</p>';
        }

        // Checking test consistncy
        $testParams = ['id' => 'Номер', 'name' => 'Название', 'questions' => 'Список вопросов'];

        foreach ($data as $testNum => $test) {
            if ($testNum) {
                echo '<hr>';
            }

            $missingParams = array_diff(array_keys($testParams), array_keys($test));
   
            if ($missingParams) {
                $errCount++;
                echo '<p class="alert">'.$testNum.'-й тест не имеет следующих параметров:</p><ul>';

                foreach($missingParams as $param) {
                    echo '<li>'.$testParams[$param].'</li>';
                }

                echo '</ul>';
                continue;
            }

            // Test info output
            echo '<h3><strong>Тест '.$test['id'].'. '.$test['name'].'</strong></h3>';

            if (!count($test['questions'])) {
                $errCount++;
                echo '<p class="alert">Список вопросов пуст.</p>';
                continue;
            }

            // Checking question consistncy
            $questionParams = ['id' => 'Номер', 'content' => 'Содержание вопроса', 'answers' => 'Ответы'];

            foreach($test['questions'] as $qNum => $question) {
                $missingParams = array_diff(array_keys($questionParams), array_keys($question));

                if ($missingParams) {
                    $errCount++;
                    echo '<p class="alert">'.$qNum.'-й вопрос не имеет следующих параметров</p><ul>';

                    foreach($missingParams as $param) {
                        echo "<li>$questionParams[$param]</li>";
                    }

                    echo '</ul>';
                }

                // Question info output
                echo '<p><strong>Вопрос '.$question['id'].': '.$question['content'].'</strong></p>';

                if (!count($question['answers'])) {
                    $errCount++;
                    echo '<p class="alert">Список ответов пуст.</p>';
                    continue;
                }

                // Checking answer consistncy
                $answerParams = ['content' => 'Содержание ответа', 'right' => 'Правильность'];

                echo '<ul>';
                foreach($question['answers'] as $aNum => $answer) {
                    $missingParams = array_diff(array_keys($answerParams), array_keys($answer));

                    if ($missingParams) {
                        $errCount++;
                        echo '<p class="alert">'.$aNum.'-й ответ не имеет следующих параметров</p><ul>';

                        foreach($missingParams as $param) {
                            echo "<li>$answerParams[$param]</li>";
                        }

                        echo '</ul>';
                        continue;
                    }

                    //Answer info output
                    echo '<li'.($answer['right'] ? ' class="right">' : '>')
                        .$answer['content'].'</li>';
                }
                echo '</ul>';        

                // Checking presence of right answers
                $rights = array_column($question['answers'], 'right');
                if(!in_array(true, $rights)) {
                    echo '<p class="alert">Для вопроса не указано ни одного правильного ответа</p>';
                }
            }
        }

        if($errCount) {
            echo '<p class="alert"><strong>Ошибок в файле: '.$errCount.'</strong></p>';
        }
        else {
            echo '<p class="success"><strong>Все тесты в порядке!</strong></p>';
        }

    ?>
    </div>
</body>
</html>