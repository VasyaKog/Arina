<?php
return array(
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=khpk',
            'emulatePrepare' => true,
            'username' => 'root',
            //'password' => 'svenacer1ABC',
            'charset' => 'utf8',
        ),
    ),
    'params' => array(
        'adminEmail' => 'admin@admin.com',
    ),
);
