<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.02.2015
 * Time: 17:05
 */


namespace app\assets;

use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'publishers/global/css/components.css',
		'publishers/global/css/plugins.css',
		'publishers/admin/layout3/css/layout.css',
		'publishers/admin/layout3/css/themes/default.css',
		'publishers/admin/layout3/css/custom.css',
	];
	public $js = [
		'publishers/global/plugins/respond.min.js',
		'publishers/global/plugins/excanvas.min.js',
	 	/*IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip */
		'publishers/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js',
		'publishers/global/plugins/bootstrap/js/bootstrap.min.js',
		'publishers/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
		'publishers/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
		'publishers/global/plugins/jquery.blockui.min.js',
		'publishers/global/plugins/jquery.cokie.min.js',
		'publishers/global/plugins/uniform/jquery.uniform.min.js',
		'publishers/global/scripts/metronic.js',
		'publishers/admin/layout3/scripts/layout.js',
	];
	public $depends = [
		'app\assets\GlobalAsset',
		'app\assets\DataPickersAsset',
	];

}
