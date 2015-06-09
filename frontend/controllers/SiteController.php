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
		$phones             = Phones::find()->innerJoin('characters_value', 'phones.id = characters_value.pid');
		if(isset($_POST['filter'])){
			foreach ($_POST['filter'] as $id=>$value) {
				if($value){
					$phones = $phones->where(['cid'=>$id, 'value'=>$value]);
				}
			}
		}

		$phones = $phones->all();

		$allValuesForFilter = CharactersValues::find()
											  ->with('names')->where('cid in (4,6,7,8,9,10,22.23,24,25,26,27)')
											  ->all();
		$filtersList        = [];

		foreach ($allValuesForFilter as $item) {

			if(!isset($filtersList[$item->cid])){
				$filtersList[$item->cid] = ['params'=>[], 'name'=>$item->names->name];
			}
			$filtersList[$item->cid]['params'][] = $item->value;
		}

		foreach ($filtersList as $name => $list) {
			$filtersList[$name]['params'] = array_unique($list['params']);
		}

		return $this->renderPartial('index', ['phones'     => $phones,
											  'filterList' => $filtersList
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
