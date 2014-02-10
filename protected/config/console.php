<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array_merge(
	array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name'=>'My Console Application',
        'import' => array(
            'application.models.*',
            'application.components.*',
            'application.extensions.*',
        ),
		// preloading 'log' component
		'preload'=>array('log'),

		// application components
		'components'=>array(
			'log'=>array(
				'class'=>'CLogRouter',
				'routes'=>array(
					array(
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					),
				),
			),
		),
	),
	require(__DIR__ . '/env/dev.php')
);