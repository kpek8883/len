<?php
require_once('/../database/db.php');
require_once('/../model/news.php');

class ExportController
{
    //Функция получения новостей из БД за последние 24 часа и формирования файла
    public function actionExport()
    {
        //Соединяемся с БД
        $pdo = DataBase::Connection();

        //Формируем SQL запрос
        $dayStart = time() - 86400;
        $sql = "SELECT * FROM news WHERE `pubDate` >  $dayStart ORDER BY `pubDate` DESC";

        //Осущетсвляем запрос к БД
        $stmt = $pdo->query($sql);

        //Делаем заготовку файла:
        $textFile = "Название статьи; Дата публикации; Оригинальная ссылка \n";

        //Записываем полученные данные в переменную $textFile.
        $textFile = '';
        while ($news = $stmt->fetch()) {
            $row = $news['title'].'; ';
            $row .= date('Y-m-d H:i',$news['pubDate']).'; ';
            $row .= $news['link'];
            $row .= " \n";

            $textFile .= $row;
        }

        return $textFile;
    }
}