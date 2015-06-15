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
		Yii::$app->session->open();
		$phones             = Phones::find();
		$phones             = $this->getFiltersForQuery($phones);
		$phones             = $phones->limit(30)
									 ->all();
		$allValuesForFilter = CharactersValues::find()
											  ->with('names')
											  ->where('cid in (1,2,24,14,9,10, 15)')
											  ->all();
		$filtersList        = $this->getDataListForFilters($allValuesForFilter);
		$likes              = $this->getStorage();
		return $this->renderPartial(
					'index', [
							   'phones'     => $phones,
							   'likes'      => $likes,
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

	/**
	 * @param $phones
	 *
	 * @return mixed
	 */
	private function getFiltersForQuery($phones)
	{
		if (isset($_POST['filter'])) {
			$phones = $phones->innerJoin('characters_value', 'phones.id = characters_value.pid');
			foreach ($_POST['filter'] as $id => $value) {
				if ($value) {
					$phones = $phones->where(
									 [
										 'cid'   => $id,
										 'value' => $value
									 ]
					);
				}
			}
			return $phones;
		}
		return $phones;
	}

	/**
	 * @param $allValuesForFilter
	 *
	 * @return mixed
	 */
	private function getDataListForFilters($allValuesForFilter)
	{
		$filtersList = [];
		foreach ($allValuesForFilter as $item) {
			if (!isset($filtersList[$item->cid])) {
				$filtersList[$item->cid] = [
					'params' => [],
					'name'   => $item->names->name
				];
			}
			$filtersList[$item->cid]['params'][] = $item->value;
		}
		foreach ($filtersList as $name => $list) {
			$filtersList[$name]['params'] = array_unique($list['params']);
		}
		return $filtersList;
	}

	public function actionLike()
	{
		$this->setStorage($_GET['id'], 1);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function actionDislike()
	{
		$this->setStorage($_GET['id'], -1);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function actionDellike()
	{
		$this->delStorage($_GET['id']);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function getStorage($key = false)
	{
		$arr = json_decode(file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'data.txt'), true);
		if ($key) {
			return $arr[$key];
		}
		return $arr;
	}

	public function setStorage($key, $value)
	{
		$arr       = $this->getStorage();
		$arr[$key] = $value;
		file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'data.txt', json_encode($arr));
	}

	public function delStorage($key)
	{
		$arr = $this->getStorage();
		unset($arr[$key]);
		file_put_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'data.txt', json_encode($arr));
	}
}
