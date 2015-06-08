<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.02.2015
 * Time: 16:44
 */
namespace app\assets;

use yii\web\AssetBundle;

class AuthAsset extends AssetBundle {

	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'publishers/admin/pages/css/login.css',
	];
	public $js = [
		'publishers/global/plugins/jquery-validation/js/jquery.validate.min.js',
		'publishers/global/plugins/jquery-migrate-1.2.1.min.js',
	];
	public $depends = [
		'app\assets\ThemeAsset',
	];
}
