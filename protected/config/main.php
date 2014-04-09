<?php
/**
 *
 */
return CMap::mergeArray(
    array(
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => 'ХПК АСУ',
        'theme' => 'booster',
        // preloading 'log' component
        'preload' => array(
            //'log',
            'booster',
        ),
        'language' => 'uk',
        //
        'aliases' => array(
            'root' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
            'vendor' => 'root.vendor',
            'public' => 'root.public',
            'booster' => 'root.vendor.clevertech.yii-booster.src',
            'bootstrap' => 'booster',
            'modules' => 'application.modules',
        ),
        // autoloading model and component classes
        'import' => array(
            'application.models.*',
            'application.helpers.*',
            'application.components.*',
            'vendor.yiiext.activerecord-relation-behavior.EActiveRecordRelationBehavior',
            'booster.components.*',
            'booster.helpers.*'
        ),

        'modules' => array(
            'studyPlan',
            'journal',
            'student',
            'hr',
            'dictionaries',
        ),

        'components' => array(
            'excel' => array(
                'class' => 'application.components.ExcelMaker',
            ),
            'word' => array(
                'class' => 'application.components.WordMaker',
            ),
            'user' => array(
                'class' => 'WebUser',
                'allowAutoLogin' => true,
                'loginUrl' => '/user/login',
            ),

            'bootstrap' => array(
                'class' => 'booster.components.Bootstrap',
                'responsiveCss' => true,
                'coreCss' => false,
            ),

            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'caseSensitive' => true,
                'rules' => array(
                    '<controller:\w+>/<id:\d+>' => '<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                    '<module:\w+>' => '<module>',
                    '<module:\w+>/<controller:\w+>' => '<module>/<controller>/index',
                    '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view/<id>',
                    '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                    '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                ),
            ),
            'errorHandler' => array(
                'errorAction' => 'site/error',
            ),
            /*'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
                    // uncomment the following to show log messages on web pages

                    array(
                        'class'=>'CWebLogRoute',
                    ),

                ),
            ),*/
        ),

        'params' => array(),
    ),
    require(__DIR__ . '/env/dev.php')
);