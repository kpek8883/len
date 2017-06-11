<?php
require_once('database/db.php');
require_once('model/news.php');
require_once('simple_html_dom.php');

class NewsController
{
    //Функция получения новостей из БД. Требуется задать колличество получаемых новостей, а так же можно зделать выборку по категории новости
    public function actionSelectNews($limit, $category = null)
    {
        //Соединяемся с БД
        $pdo = DataBase::Connection();

        //Формируем SQL запрос
        $sql = "SELECT * FROM news";
        if ($category != null)
            $sql .= " WHERE `category` = '$category'";

        $sql .= " ORDER BY `pubDate` DESC LIMIT $limit";

        //Осущетсвляем запрос к БД
        $stmt = $pdo->query($sql);

        //Записываем полученные данные в массив массивов, где подмассивы - новости.
        $news = array();
        if (count($stmt)) {
            while ($row = $stmt->fetch()) {
                $output['id'] = $row['id'];
                $output['title'] = $row['title'];
                $output['text'] = $row['text'];
                $output['pubDate'] = $row['pubDate'];
                $output['link'] = $row['link'];
                $output['category'] = $row['category'];
                $output['imageURL'] = $row['imageURL'];
                $news[] = $output;
            }
        }
        return $news;
    }

    //Функция добавления новостей в БД при первом преходе на страницу списка новостей (index.php)
    public function actionFirstAddNews($url, $newsNumber)
    {
        $rss = simplexml_load_file($url);       //интерпретируем XML-файл в объект
        $count = 0;                             //счётчик обработанных новостей

        //Цикл для обхода всей RSS ленты
        foreach ($rss->channel->item as $item) {

            //Объявляем экземпляр класса News, задаём ему поля
            $news = new News();

            //определяем поля
            $news->title = $item->title;
            $news->pubDate = strtotime($item->pubDate);
            $news->link = $item->link;
            $news->category = $item->category;
            if (isset($item->enclosure))
                $news->imageURL = $item->enclosure->attributes()->url;

            //Добавляем данные в БД
            $news->insertNews();

            //Останавливаем парсинг после того, как спарсели newsNumber новости
            $count++;
            if ($count >= $newsNumber)
                break;
        }
    }

    //Функция добавления последних новостей
    public function actionAddLastNews($url)
    {
        $rss = simplexml_load_file($url);       //Интерпретирует XML-файл в объект

        //Получаем из БД последнюю новость
        $lastNews = $this->actionSelectNews(1);

        //Извлекаем время публикации последней новости, хранаящейся в БД
        $lastNewsPubDate = '';
        foreach ($lastNews as $news)
            $lastNewsPubDate = $news['pubDate'];

        //Цикл для обхода всей RSS ленты
        foreach ($rss->channel->item as $item) {

            //Проверяем, что обрабатываемая новость была опубликованна не раньше последней новости из БД
            if (strtotime($item->pubDate) <= $lastNewsPubDate)
                break;

            //Т.к. экспортировать можно только новости за сутки,
            //то и добавлять в базу необходимо новости только за последние 24 часа.
            //Проверяем, что новость добавлена за последние 24 часа
            if (strtotime($item->pubDate) <= (time() - 86400))
                break;

            //объявляем экземпляр класса News, задаём ему поля
            $news = new News();

            //Определяем поля
            $news->title = $item->title;
            $news->pubDate = strtotime($item->pubDate);
            $news->link = $item->link;
            $news->category = $item->category;
            if (isset($item->enclosure))
                $news->imageURL = $item->enclosure->attributes()->url;

            //Добавляем данные в БД
            $news->insertNews();
        }
    }

    //Функция получения текста статьи
    public function actionTakeNewsText($link)
    {
        // Парсим страницу навостей, с которой необходимо получить текст статьи
        $content = simplehtmldom_1_5\file_get_html($link);      // Создаем объект DOM на основе кода, полученного по ссылке на страницу новости

        $text = '';
        foreach ($content->find('p') as $paragraph) {       // находим все элеметы <р> html страницы новости
            if (substr($paragraph, 0, 3) != '<p>')     //проверяем, что был найтен именно элемент <p> без атрибутов
                continue;
            $text .= $paragraph;                                    // записываем абзацы в переменную $text
        }

        return $text;
    }

    //Функция добавления текста статьи в БД
    public function actionAddNewsTextToDB($text, $id)
    {
        $pdo = DataBase::Connection();
        $stmt = $pdo->prepare("UPDATE news SET `text` = :text WHERE `id` = :id");
        $stmt->bindValue(":text", $text);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }
}
