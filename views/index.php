<div class="container">

    <div class="page-header">
        <h1>Новости портала lenta.ru</h1>
    </div>

    <a href="add.php" class="button28">Получить последние новости</a>
    <a href="export.php" class="button28">Экспорт новостей за сутки</a>

    <h3>50 последних новостей:</h3>

    <?php

    require_once('controllers/NewsController.php');

    $controller = new NewsController();
    $newsList = $controller->actionSelectNews(50);

    if (empty($newsList)) {
        $link = 'https://lenta.ru/rss';       //адрес RSS ленты
        $newsNumber = 50;
        $controller->actionFirstAddNews($link, $newsNumber);

        $newsList = $controller->actionSelectNews(50);
    }

    foreach ($newsList as $news) {
        //Функция substr() не совсем корректно обрезает русские символы, поэтому
        //приходится обрезать не на 200, а 400 символов, чтобы в итоге получилось 200.
        //Чтобы корректно использовать работу с русскими строками надо использовать
        // функции для работы с Многобайтными строками. В данном случае корректно работате
        //$title = mb_substr($news['title'], 0, 200);
        //Однако, по всей видимости, php на виртуальной машине, на которой разворачию приложение,
        //не поддерживает данные функции, поэтому приходится использовать, что есть.
        $title = substr($news['title'], 0, 400);
        $title .= "...<a href='news.php?id=".$news['id']."'>Подробнее</a>";
        $output = "<div class='row'>";
        $output .= "<div class='col-md-2'>".date('H:i:s d.m.Y', $news['pubDate'])."</div>";      //выводим на печать время опубликования статьи
        $output .= "<div class='col-md-10'>".$title."</div>";                                           //выводим на печать название статьи
        $output .= "</div>";
        echo $output;
    }
    ?>
</div>
