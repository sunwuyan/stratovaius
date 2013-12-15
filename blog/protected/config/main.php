<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Blog Demo',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'ext.yiiredis.*',
        'application.modules.srbac.controllers.SBaseController',
	),

	'defaultController'=>'post',

   'modules'=>array(
       'srbac'=>array(
           'userclass'=>'User',
           'userid'=>'id',
           'username'=>'username',
           'debug'=>true,
           'pageSize'=>10,
           'superUser'=>'Authority',
           'css'=>'srbac.css',
           'layout'=>'application.views.layouts.main',
           'notAuthorizedView'=>'srbac.views.authitem.unauthorized',
           'alwaysAllowed'=>array(),
           'userActions'=>array('show','View','List'),
           'listBoxNumberOfLines'=>15,
           'imagesPath'=>'srbac.images',
           'imagesPack'=>'noia',
           'iconText'=>false,
           'header'=>'srbac.views.authitem.header',
           'footer'=>'srbac.views.authitem.footer',
           'showHeader'=>true,
           'showFooter'=>true,
           'alwaysAllowedPath'=>'srbac.components',
       ),
	   'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'look',
            #'ipFilters'=>array('127.0.0.1',),
            // 'newFileMode'=>0666,
            // 'newDirMode'=>0777,
        ),
   ),

	// application components
	'components'=>array(
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'connectionID'=>'db',
			'itemTable'=>'items',
			'assignmentTable'=>'assignments',
			'itemChildTable'=>'itemchildren',
		),
		'redis'=>array(
			'class'=>"ext.yiiredis.ARedisConnection",
			'hostname'=>'localhost',
			'port'=>6379,
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
		'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			//'connectionString' => 'sqlite:C:\Music\testdrive.db',
			'tablePrefix' => 'tbl_',
		),
/*
		'cache'=>array(
            	      'class'=>'ext.yiiredis.ARedisCache',
		 ),
*/
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=10.232.31.69;dbname=hupan',
			'emulatePrepare' => true,
			'username' => 'hupan',
			'password' => 'hupan',
			'charset' => 'utf8',
			#'tablePrefix' => 'tbl_',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
		
		'request'=>array(
			'enableCsrfValidation'=>false,
			'enableCookieValidation'=>true,
		),

        'urlManager'=>array(
        	'urlFormat'=>'path',
		//'urlSuffix'=>'.html',
		//'showScriptName'=>false,	
        	'rules'=>array(
			'gii'=>'gii',
            		'gii/<controller:\w+>'=>'gii/<controller>',
            		'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
/*
        		'<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
        		'<module:\w+>/<controller:\w+>/<action:\w+>/*'=>'<module>/<controller>/<action>',
		*/	),	
        ),
    
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
					'showInFireBug'=>true,
					'ignoreAjaxInFireBug'=>false,
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
