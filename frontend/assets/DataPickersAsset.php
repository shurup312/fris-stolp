<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 10.02.2015
 * Time: 17:23
 */


namespace app\assets;

use yii\web\AssetBundle;

class DataPickersAsset extends AssetBundle{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'publishers/global/plugins/bootstrap-datepicker/css/datepicker3.css',
		'publishers/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css',
		'publishers/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css',
		'',
	];
	public $js = [
		'publishers/global/plugins/moment.min.js',
		'publishers/admin/pages/scripts/components-pickers.js',
		'publishers/global/plugins/bootstrap-daterangepicker/daterangepicker.js',
		'publishers/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
		'publishers/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
	];
}
