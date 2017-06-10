<?php

require_once('database/db.php');

//Объявление класса News
class News
{
    public $title;
    public $text;
    public $pubDate;
    public $link;
    public $category;
    public $imageURL = null;

    //Добавление новости в БД
    public function insertNews()
    {
        $pdo = DataBase::Connection();
        $stmt = $pdo->prepare("INSERT INTO news (title, text, pubDate, link, category, imageURL) VALUES (:title, :text, :pubDate, :link, :category, :imageURL)");
        $stmt->execute(array(
            "title" => $this->title,
            "text" => $this->text,
            "pubDate" => $this->pubDate,
            "link" => $this->link,
            "category" => $this->category,
            "imageURL" => $this->imageURL,
        ));
    }

    //Извлечение новости из БД
    public function selectNews($id)
    {
        $pdo = DataBase::Connection();
        $sql = "SELECT * FROM news WHERE `id` = '$id'";
        $stmt = $pdo->query($sql);

        $stmt = $stmt->fetch();

        $this->title = $stmt['title'];
        $this->text = $stmt['text'];
        $this->pubDate = $stmt['pubDate'];
        $this->link = $stmt['link'];
        $this->category = $stmt['category'];
        $this->imageURL = $stmt['imageURL'];
    }
}