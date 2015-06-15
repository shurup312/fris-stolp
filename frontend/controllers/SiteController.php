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
		$sql = 'Select * from characters_value';
		$sql = $this->addFilters($sql);
		$result = \yii::$app->getDb()->createCommand($sql)->queryAll();
		$ids = $this->calculatePriority($result);
		$tmpPhones = Phones::find()->indexBy('id')->all();
		$result = [];
		foreach ($ids as $id) {
			$result[] = $tmpPhones[$id];
		}
		$characters = Characters::find()->where(['id'=>32])->orWhere(['id'=>22])->asArray()->all();
		return $this->renderPartial(
			'index', [
				'characters'=>$characters,
				'phones'=>$result,
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

	private function addFilters($sql)
	{
		$ids = [];
		if(!$_POST['filter']){
			return $sql;
		}
		foreach ($_POST['filter'] as $id=>$value) {
			$ids[] = $id;
		}
		$sql .= ' where cid IN ('.implode(',',$ids).')';
		return $sql;
	}

	private function calculatePriority($arrayCharacters)
	{
		$ids = [];
		$priority = [];
		foreach ($arrayCharacters as $character) {
			if(!isset($priority[$character['pid']])){
				$priority[$character['pid']] = 0;
			}
			$priority[$character['pid']] += $_POST['filter'][$character['cid']]*$character['value'];
		}
		arsort($priority);
		foreach ($priority as $id=>$value) {
			$ids[] = $id;
		}
		return $ids;
	}
}
