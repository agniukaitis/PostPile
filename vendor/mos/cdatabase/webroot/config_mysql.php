<?php

return [
    'dsn'     => "mysql:host=localhost;dbname=test;",
    'username'        => "root",
    'password'        => "root",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "comments_",
    'verbose' => true,
    //'debug_connect' => 'true',
];
