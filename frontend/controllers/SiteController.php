<?php
namespace frontend\controllers;

use app\models\CharactersValues;
use app\models\Phones;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

	public $layout = 'public';
	private $param = [];

	public function actionIndex()
	{
		$phones = Phones::find()
						->all();
		return $this->renderPartial('index', ['phones' => $phones]);
	}

	public function actionDetails($id)
	{
		$phone       = Phones::findOne($id);
		$phoneParams = CharactersValues::find()
									   ->with('names')
									   ->where(['pid' => $id])
									   ->all();
		return $this->renderPartial('details', ['phone'       => $phone,
												'phoneParams' => $phoneParams
											 ]
		);
	}
}
