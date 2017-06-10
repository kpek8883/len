<?php
require_once('/../database/db.php');
require_once('/../model/news.php');
require_once('/../simple_html_dom.php');

class NewsController
{
    public function actionSelectNews($limit, $category = null)
    {
        $pdo = DataBase::Connection();
        $sql = "SELECT * FROM news";
        if ($category != null)
            $sql .= " WHERE `category` = '$category'";

        $sql .= " ORDER BY `pubDate` DESC LIMIT $limit";

        $stmt = $pdo->query($sql);

        $news = array();
        if (count($stmt)) {
            while ($row = $stmt->fetch()) {
                $output['id'] = $row['id'];
                $output['title'] = $row['title'];
                $output['text'] = $row['text'];
                $output['pubDate'] = date('H:i:s d.m.Y', $row['pubDate']);
                $output['link'] = $row['link'];
                $output['category'] = $row['category'];
                $output['imageURL'] = $row['imageURL'];
                $news[] = $output;
            }
        }
        return $news;
    }

    public function actionParser($url, $newsNumber)
    {
        $rss = simplexml_load_file($url);       //Интерпретирует XML-файл в объект
        $count = 0; //счётчик отображаеммых новостей

        //цикл для обхода всей RSS ленты
        foreach ($rss->channel->item as $item) {

            $content = simplehtmldom_1_5\file_get_html($item->link); // Создаем объект DOM на основе кода, полученного по ссылке на страницу новости

            // находим все элеметы <р> html страницы новости
            $text = '';
            foreach ($content->find('p') as $paragraph){
                if (substr($paragraph, 0, 3) != '<p>')
                    continue;
                $text .= $paragraph;
            }

            //объявляем экземпляр класса News, задаём ему поля
            $news = new News();

            $news->title = $item->title;
            $news->pubDate = $item->pubDate;
            $news->link = $item->link;
            $news->category = $item->category;
            $news->text = $text;
            if (isset($item->enclosure))
                $news->imageURL = $item->enclosure->attributes()->url;

            //Добавляем данные в БД
            $news->insertNews();

            //останавливаем цикл после 50-ой спарсеной новости
            $count++;
            if ($count >= $newsNumber)
                break;
        }
    }
}
