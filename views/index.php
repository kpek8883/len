<div class="container">

    <div class="page-header">
        <h1>Новости портала lenta.ru</h1>
    </div>

    <a href="add.php" class="button28">Получить последние новости</a>
    <a href="export.php" class="button28">Экспорт новостей за сутки</a>

    <h3>50 последних новостей:</h3>

    <?php

    require_once('/../controllers/NewsController.php');

    $controller = new NewsController();
    $newsList = $controller->actionSelectNews(50);

    if (empty($newsList)) {
        $link = 'https://lenta.ru/rss';       //адрес RSS ленты
        $newsNumber = 50;
        $controller->actionFirstAddNews($link, $newsNumber);

        $newsList = $controller->actionSelectNews(50);
    }

    foreach ($newsList as $new) {
        $title = mb_strimwidth($new['title'], 0, 200);
        $title .= '...<a href="../news.php?id='.$new['id'].'">Подробнее</a>';
        $output = '<div class="row">';
        $output .= "<div class='col-md-2'>".date('H:i:s d.m.Y', $new['pubDate'])."</div>";        //выводим на печать описание статьи
        $output .= "<div class='col-md-10'>".$title."</div>";        //выводим на печать описание статьи
        $output .= '</div>';
        echo $output;
    }
    ?>
</div>
