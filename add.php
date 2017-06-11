<?php

//Возвращаемся на index.php
header ("Location: index.php");

//Устанавливаем временную зону Europe/Moscow
date_default_timezone_set( 'Europe/Moscow' );

require_once('controllers/NewsController.php');

$link = 'https://lenta.ru/rss';

//Создаём экземпляр класса NewsController и добавляем последние новости в БД
$controller = new NewsController();
$controller->actionAddLastNews($link);