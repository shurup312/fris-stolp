<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.02.2015
 * Time: 17:23
 */


namespace app\assets;

use yii\web\AssetBundle;

class DashboardAsset extends AssetBundle{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'publishers/global/plugins/jqvmap/jqvmap/jqvmap.css',
		'publishers/global/plugins/morris/morris.css',
		'publishers/admin/pages/css/tasks.css',
		'',
	];
	public $js = [
		'publishers/global/plugins/flot/jquery.flot.min.js',
		'publishers/global/plugins/flot/jquery.flot.resize.min.js',
		'publishers/global/plugins/flot/jquery.flot.time.min.js',
	];
	public $depends = [
		'app\assets\ThemeAsset',
	];
}
