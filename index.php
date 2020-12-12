<?php

function autoload(string $className)
{
    require 'src/class/' . ucfirst($className) . '.php';
}

spl_autoload_register('autoload');

DBFactory::createTableIfNotExistsWithPDO();

$pdo = DBFactory::getMysqlConnexionWithPDO();

$manager = new MessageManagerPDO($pdo);

