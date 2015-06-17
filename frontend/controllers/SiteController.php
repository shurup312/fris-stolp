<?php
namespace frontend\controllers;

use app\models\Characters;
use app\models\CharactersValues;
use app\models\Phones;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

	public $enableCsrfValidation = false;
	public $layout = 'public';

	public function actionIndex()
	{
		FRIS::getAllPhones();
		FRIS::normalizeParametersOfPhones();
		FRIS::calculateFirstImportant();
		FRIS::getFirstNPhones();
		FRIS::splitPhonesByNClasses();
		FRIS::getFirstEtalonsForEachOfNClasses();
		FRIS::getEtalonsForEachOfNClasses();
		FRIS::classificationPhones();
		FRIS::splitPhoneByClasters();
		FRIS::splitPhonesNonClassesByClasses();
		$characters = Characters::find()
								->where('id IN ('.FRIS::getFiltersIdForSQL().')')
								->orderBy('id ASC')
								->asArray()
								->all();
		$phones = PhonesContainer::getContainer();
		usort($phones, function($a, $b){
			return $a->classID>$b->classID?1:-1;
		});
		return $this->renderPartial(
			'index', [
					   'characters' => $characters,
					   'phones'     => $phones
				   ]
		);
	}

	public function actionDetails($id)
	{
		$phone       = Phones::findOne($id);
		$phoneParams = CharactersValues::find()
									   ->with('names')
									   ->where(['pid' => $id])
									   ->all();
		return $this->renderPartial(
			'details', [
						 'phone'       => $phone,
						 'phoneParams' => $phoneParams
					 ]
		);
	}
}
