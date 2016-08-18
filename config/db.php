<?php

return [
    'class' => 'yii\db\Connection',
    //'dsn' => 'mysql:host=localhost;dbname=sport',
    'dsn' => 'mysql:host=5.45.124.29;dbname=sport',
    //'username' => 'root',
    //'password' => 'root',
    'username' => 'setyes',
    'password' => 'a748b5rk9',
    'charset' => 'utf8',
    'attributes'=> [
        PDO::MYSQL_ATTR_LOCAL_INFILE => true,
        //PDO::MYSQL_ATTR_LOCAL_INFILE => true
    ]
    /*'dsn' => 'mysql:host=localhost;dbname=blacar',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'attributes'=>array(
        PDO::MYSQL_ATTR_LOCAL_INFILE => true
    ),*/
    /*'tablePrefix' => 'bla_',
    'on afterOpen' => function($event) {
        $event->sender->createCommand("SET time_zone = '+00:00'")->execute();
    }*/
];
