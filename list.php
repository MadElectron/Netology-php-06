<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Список тестов</title>
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
    <?php 
        $json = file_get_contents('tests/test.json');
        $data = json_decode($json, true);

        function checkTestData($data) 
        {
            // Checking test consistncy
            $testParams = ['id' => 'Номер', 'name' => 'Название', 'questions' => 'Список вопросов'];

            foreach ($data as $test) {
                $missingParams = array_diff(array_keys($testParams), array_keys($test));
                // var_dump(array_keys($test), array_keys($testParams), $missingParams);
                
                if ($missingParams) {
                    echo '<p>Один из тестов не имеет следующих параметров:</p><ul>';

                    foreach($missingParams as $param) {
                        echo "<li>$testParams[$param]</li>";
                    }
 
                    echo '</ul>';
                    return 0;
                }

                if (!count($test['questions'])) {
                    echo "<p>Список вопросов теста номер {$test['id']} пуст.</p>";
                    return 0;
                }

                // Checking question consistncy
                $questionParams = ['id' => 'Номер', 'content' => 'Сожержание вопроса', 'answers' => 'Ответы'];

                foreach($test['questions'] as $question) {
                    $missingParams = array_diff(array_keys($questionParams), array_keys($question));

                    if ($missingParams) {
                        echo '<p>Один из вопросов не имеет следующих параметров</p><ul>';

                        foreach($missingParams as $param) {
                            echo "<li>$questionParams[$param]</li>";
                        }

                        echo '</ul>';
                        return 0;
                    }

                    if (!count($test['answers'])) {
                        echo "<p>Список ответов вопроса номер {$question['id']} пуст.</p>";
                        return 0;
                    }

                    // Checking answer consistncy
                    $answerParams = ['content' => 'Содержание ответа', 'right' => 'Правильность'];

                    foreach($question['answers'] as $answer) {
                        $missingParams = array_diff(array_keys($answerParams), array_keys($answer));

                        if ($missingParams) {
                            echo '<p>Один из ответов не имеет следующих параметров</p><ul>';

                            foreach($missingParams as $param) {
                                echo "<li>$answerParams[$param]</li>";
                            }

                            echo '</ul>';
                            return 0;
                        }

                        $rights = array_column($answer, 'right');

                        if(!in_array(true, $rights)) {
                            echo "<p>В вопросе номер {$question['id']} не указано ни одного правильного ответа</p>";

                            return 0;
                        }

                    }
                }
            }

            return 1;
        }

        if (checkTestData($data)) {
            echo "Все тесты в порядке!";
        }

    ?>
    </div>
</body>
</html>