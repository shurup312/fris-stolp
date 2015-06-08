<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.02.2015
 * Time: 16:44
 */
namespace app\assets;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {

	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'publishers/global/plugins/font-awesome/css/font-awesome.min.css',
		'publishers/global/plugins/simple-line-icons/simple-line-icons.min.css',
		'publishers/global/plugins/bootstrap/css/bootstrap.min.css',
		'publishers/global/plugins/uniform/css/uniform.default.css',
	];
	public $js = [
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
