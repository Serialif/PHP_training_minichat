<?php


class DBFactory
{
    public static function getMysqlConnexionWithPDO()
    {
        return new PDO('mysql:host=localhost;dbname=training;charset=utf8mb4',
            'root', 'root',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public static function createTableIfNotExistsWithPDO()
    {
        $query = DBFactory::getMysqlConnexionWithPDO()->query('CREATE TABLE IF NOT EXISTS message
            (
                id         int(10) unsigned NOT NULL AUTO_INCREMENT,
                author     varchar(30)      NOT NULL,
                message    varchar(100)     NOT NULL,
                createdAt  datetime         NOT NULL,
                PRIMARY KEY (id)
            ) DEFAULT CHARSET = utf8mb4;');
    }
}