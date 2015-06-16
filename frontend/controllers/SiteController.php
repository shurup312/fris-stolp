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
		$sql       = $this->getCharacters();
		$result    = \yii::$app->getDb()
							   ->createCommand($sql)
							   ->queryAll();
		$ids       = $this->calculatePriority($result);
		$tmpPhones = Phones::find()
						   ->indexBy('id')
						   ->all();
		$result    = [];
		foreach ($ids as $id) {
			$result[] = $tmpPhones[$id];
		}
		$characters = Characters::find()
								->where('id IN ('.FRIS::getFiltersIdForSQL().')')
								->orderBy('id ASC')
								->asArray()
								->all();
		return $this->renderPartial(
			'index', [
					   'characters' => $characters,
					   'phones'     => $result,
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

	private function getCharacters()
	{
		$sql = 'Select * from characters_value';
		$ids = [];
		if (!isset($_POST['filter'])) {
			return $sql;
		}
		foreach ($_POST['filter'] as $id => $value) {
			$ids[] = $id;
		}
		$sql .= ' where cid IN ('.implode(',', $ids).')';
		return $sql;
	}

	private function calculatePriority($arrayCharacters)
	{
		$ids      = [];
		$priority = [];
		foreach ($arrayCharacters as $character) {
			if (!isset($priority[$character['pid']])) {
				$priority[$character['pid']] = 0;
			}
			if (in_array(
				$character['value'], [
				'Да',
				'есть'
			]
			)) {
				$character['value'] = 10;
			}
			if (in_array($character['value'], ['нет'])) {
				$character['value'] = 0;
			}
			if (isset($_POST['filter'][$character['cid']])) {
				$priority[$character['pid']] += $_POST['filter'][$character['cid']]*$character['value'];
			}
		}
		arsort($priority);
		foreach ($priority as $id => $value) {
			$ids[] = $id;
		}
		return $ids;
	}
}
