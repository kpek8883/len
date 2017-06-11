<?php

header ( "Content-type: application/msexcel; charset=utf-8" );
header ( "Content-Disposition: inline; filename=lastNews.csv");

//Устанавливаем временную зону Europe/Moscow
date_default_timezone_set( 'Europe/Moscow' );

require_once('controllers/ExportController.php');

//Создаём экземпляр класса ExportController и экспортируем новости в csv-файл
$controller = new ExportController();
$fileText = $controller->actionExport();

//Преобразовываем $fileText в кодировку windows-1251 для корректного отображения русского текста в Windows MS Excel
$fileText = iconv('utf-8', 'windows-1251', $fileText);

echo $fileText;