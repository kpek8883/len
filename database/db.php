<?php

//Объявление класса DataBase
class DataBase
{
    const DB = 'heroku_9daea7154993846';
    const HOST = 'eu-cdbr-west-01.cleardb.com';
    const USER = 'b0a8caf41a30f5';
    const PASSWORD = 'bd0e6d2e';
    const CHARSET = 'utf8';

    //Соединение с БД
    public static function connection()
    {
        $dsn = 'mysql:host='.self::HOST.';dbname='.self::DB.';charset='.self::CHARSET;
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn,self::USER, self::PASSWORD, $opt);
    }
}
