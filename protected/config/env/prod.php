<?php
return array(
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1:3306;dbname=khpk',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
    ),
    'params' => array(
        'adminEmail' => 'admin@admin.com',
    ),
);
