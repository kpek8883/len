<div class="row">

    <div class="col-sm-8 blog-main">

        <div class="blog-post">

            <?php

            //Устанавливаем временную зону Europe/Moscow
            date_default_timezone_set( 'Europe/Moscow' );

            require_once('model/news.php');
            require_once('controllers/NewsController.php');

            $id = $_GET['id'];

            //Создаём объект класса News
            $news = new News();
            //Выполняем метод selectNews, извлекающий из БД новость с индексом id и определяющий поля объекта
            $news->selectNews($id);

            //Проверяем на наличие в БД текста статьи
            if (empty($news->text))
            {
                //Если текста нет - создаём объект NewsController, вызываем метод  actionTakeNewsText
                //которые парсит страницу новостей по передваемой ссылке вы возвращает тектс
                $controller = new NewsController();
                $news->text = $controller->actionTakeNewsText($news->link);                     //Опретеляем поле text объекта $news
                $controller->actionAddNewsTextToDB($news->text, $id);                           //PЗаписываем в БД текст статьи
            }

            //Выводим на печать новость, "заворачивая" поля в контейнеры
            $output = '<h2 class="blog-post-title">'.$news->title.'</h2>';                      //выводим название статиь
            $pubDate = date('H:i:s - d.m.Y', $news->pubDate);                            //преобразовываем время из timestamp в H:i:s - d.m.Y
            $output .= '<p class="blog-post-meta">'.$pubDate.'</p>';                            //выводим время публикации
            $output .= isset($news->imageURL) ? '<img src="'.$news->imageURL.'" alt="'.$news->title.'">' : ''; //выводим картинку, если имеется
            $output .= $news->text;                                                             //выводим текст статьи
            $output .= '<p><a href="'.$news->link.'">Cсылка на оригинальную статью</a>';        //выводим оригинальную ссылку

            echo $output;
            ?>

        </div>

        <a href="index.php" class="button28">На главную</a>   <!-- Кнопка с ссылкой на главную страницу -->
    </div>

    <!-- Выводим справа от новости блок "Рекомендуем посмотреть" -->
    <!-- В качестве рекомендованных новостей будут последние 10  -->
    <!-- новостей такой же категории, что и текущая новость      -->
    <!-- Если, кроме текущей новости в БД нет новостей данной    -->
    <!-- категории - рекомендуем 10 последних новостей           -->
    <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
        <div class="sidebar-module">
            <h4>Рекомендуем посмотреть:</h4>
            <ol class="list-unstyled">
                <?php

                require_once('controllers/NewsController.php');

                //Создаём объект класса NewsController
                $controller = new NewsController();
                //Получаем из БД 10 последних новостей текущей категории
                $newsList = $controller->actionSelectNews(10, $news->category);

                //Проверяем, если данная новость единственная данной категории,
                // то извлекаем из БД 10 последних новостей любой категории
                if (count($newsList) == 1)
                    $newsList = $controller->actionSelectNews(10);

                //Выводим списком в блок названия новостей, которые являются ссылками
                foreach ($newsList as $news) {
                    //Проверяем, чтобы текущая открытая новость не попала в список рекомендуемых
                    if ($news['id'] == $id)
                        continue;
                    echo  '<hr><a href="news.php?id='.$news['id'].'">'.$news['title'].'</a></hr>';
                }
                
                ?>
            </ol>
        </div>
    </div>

</div>