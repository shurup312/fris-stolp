<?php
$params = array_merge(
	require(__DIR__.'/../../common/config/params.php'),
	require(__DIR__.'/../../common/config/params-local.php'),
	require(__DIR__.'/params.php'),
	require(__DIR__.'/params-local.php')
);
return [
	'id'                  => 'app-frontend',
	'basePath'            => dirname(__DIR__),
	'bootstrap'           => ['log'],
	'controllerNamespace' => 'frontend\controllers',
	'components'          => [
		'authManager'  => [
			'class' => 'yii\rbac\PhpManager',
		],
		'log'          => [
			'traceLevel' => YII_DEBUG
				?3
				:0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => [
						'error',
						'warning'
					],
				],
			],
		],
		'errorHandler' => [
			'maxSourceLines' => 20,
		],
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,

		],
	],
	'modules'             => [

	],
	'params'              => $params,
];
