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
		/**
		 * Получение всех телефонов с их характеристиками
		 */
		FRIS::getAllPhones();
		/**
		 * Нормализация параметров телефонов так, чтобы каждый параметр был от 0 до 1
		 */
		FRIS::normalizeParametersOfPhones();
		/**
		 * Калькуляция первичной важности для каждого телефона
		 */
		FRIS::calculateFirstImportant();
		/**
		 * Получение первых N телефонов на основе первоначальной важности
		 */
		FRIS::getFirstNPhones();
		/**
		 * Разбить телефоны обучающей выборки на первые N классов
		 */
		FRIS::splitPhonesByNClasses();
		/**
		 * Получение первых эталонов внутри каждого класса п.1
		 */
		FRIS::getFirstEtalonsForEachOfNClasses();
		/**
		 * Получение вторых эталонов внутри каждого класса п.2
		 */
		FRIS::getEtalonsForEachOfNClasses();
		/**
		 * Класификация телефонов п.4-6
		 */
		FRIS::classificationPhones();
		/**
		 * Разбиение телефонов по кластерам (приложение D)
		 */

		FRIS::splitPhoneByClasters();
		/**
		 * Разбиение телефонов которые не классифицированы, по текущим классам.
		 */
		FRIS::splitPhonesNonClassesByClasses();
		/**
		 * Получение данных для фильтра
		 */
		$characters = Characters::find()
								->where('id IN ('.FRIS::getFiltersIdForSQL().')')
								->orderBy('id ASC')
								->asArray()
								->all();
		/**
		 * Получение результирующих телефонов для вывода
		 */
		$phones = PhonesContainer::getContainer();
		/**
		 * Сортирока телефонов по классам для более удобного вывода
		 */
		usort($phones, function($a, $b){
			return $a->classID>$b->classID?1:-1;
		});
		/**
		 * Вывод данных в шаблон
		 */
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
