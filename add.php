<?php

//Возвращаемся на index.php
header ("Location: index.php");

require_once('controllers/NewsController.php');

$link = 'https://lenta.ru/rss';

//Создаём экземпляр класса NewsController и добавляем последние новости в БД
$controller = new NewsController();
$controller->actionAddLastNews($link);