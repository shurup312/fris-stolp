<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 16.06.2015
 * Time: 13:00
 */
namespace frontend\controllers;

use app\models\CharactersValues;
use app\models\Phones;

class FRIS
{

	const COUNT_OF_FIRST_PHONES = 50;
	const COUNT_FIRST_CLASSES = 10;
	const LAMBDA = 0.5;
	public static $filterIDArray = [
		5,
		8,
		10,
		12,
		22,
		28,
		32,
		34,
	];
	const CALCULATE_NON_CLASSES = false;

	/**
	 * Получение данных, присланных пользователем из фильтра
	 * @return array
	 */
	public static function getPriorityParams()
	{
		$priorityParams = [];
		if (isset($_POST['filter'])) {
			foreach ($_POST['filter'] as $value) {
				if (!$value) {
					$value = 1;
				}
				$priorityParams[] = $value;
			}
		} else {
			foreach (FRIS::$filterIDArray as $filter) {
				$priorityParams[] = 1;
			}
		}
		return $priorityParams;
	}

	/**
	 * Получение всего списка телефонов в виде объектов PhoneObject.
	 */
	public static function getAllPhones()
	{
		$phones = Phones::find()
						->all();
		foreach ($phones as $item) {
			$phoneObject        = new PhoneObject();
			$phoneObject->id    = $item->id;
			$phoneObject->name  = $item->name;
			$phoneObject->photo = $item->photo;
			$phoneObject->price = $item->price;
			PhonesContainer::setContainer($phoneObject);
		}
		$parameters = CharactersValues::find()
									  ->where('cid IN ('.FRIS::getFiltersIdForSQL().')')
									  ->orderBy('cid ASC')
									  ->all();
		foreach ($parameters as $parameter) {
			$phone           = PhonesContainer::getContainer($parameter->pid);
			$phone->params[] = $parameter->value;
			PhonesContainer::setContainer($phone);
		}
	}

	/**
	 * Вспомогательная функция для формирования SQL запроса по получению характеристик в фильтр,
	 * то есть она возвращает часть SQL кода.
	 * @return string
	 */
	public static function getFiltersIdForSQL()
	{
		return implode(',', FRIS::$filterIDArray);
	}

	/**
	 * Нормализация параметров каждого телефона, чтобы можно было нормально считать первичную важность телефонов.
	 */
	public static function normalizeParametersOfPhones()
	{
		foreach (PhonesContainer::getContainer() as $phone) {
			foreach ($phone->params as $key => $param) {
				switch ($param) {
					case 'Да':
					case 'есть':
						$phone->params[$key] = 1;
						break;
					case 'Нет':
					case 'нет':
						$phone->params[$key] = 0;
						break;
				}
				if (strpos($param, '"')) {
					$phone->params[$key] = (float)substr($param, 0, -1);
				}
				if (strpos($param, 'x')) {
					list($x, $y) = explode('x', $param);
					$phone->params[$key] = $x*$y;
				}
			}
			PhonesContainer::setContainer($phone);
		}
		$maxValueOfParameter = [];
		foreach (FRIS::$filterIDArray as $filter) {
			$maxValueOfParameter[] = 0;
		}
		foreach (PhonesContainer::getContainer() as $phone) {
			foreach ($phone->params as $key => $value) {
				if ($value > $maxValueOfParameter[$key]) {
					$maxValueOfParameter[$key] = $value;
				}
			}
		}
		foreach (PhonesContainer::getContainer() as $phone) {
			foreach ($phone->params as $key => $value) {
				$phone->params[$key] = $value/$maxValueOfParameter[$key];
			}
			PhonesContainer::setContainer($phone);
		}
	}

	/**
	 * Рассчет первичной важности каждого телефона по 8ми параметрам, которые мы отобрадил,
	 * плюс учитываются коэффициенты, присланные польлзователем в фильтре.
	 */
	public static function calculateFirstImportant()
	{
		foreach (PhonesContainer::getContainer() as $phone) {
			foreach ($phone->params as $key => $param) {
				$phone->firstImportant += $param*FRiS::getPriorityParams()[$key];
			}
			PhonesContainer::setContainer($phone);
		}
	}

	/**
	 * Сортировка телефонов и выделение первых N телефонов для обучающей выборки
	 */
	public static function getFirstNPhones()
	{
		FRIS::sortPhonesByFirstImportant();
	}

	/**
	 * Сортировка телефонов по паервичной важности
	 */
	private static function sortPhonesByFirstImportant()
	{
		$phones = PhonesContainer::getContainer();
		usort(
			$phones, function ($a, $b) {
			return $a->firstImportant < $b->firstImportant?1:-1;
		}
		);
		PhonesContainer::resetContainer();
		$phones = array_slice($phones, 0, FRIS::COUNT_OF_FIRST_PHONES*1.7);
		foreach ($phones as $phone) {
			PhonesContainer::setContainer($phone);
		}
	}

	/**
	 * Разбиение телефонов из обучающей выборки на N классов
	 */
	public static function splitPhonesByNClasses()
	{
		$min = 100;
		$max = 0;
		foreach (PhonesContainer::getFirstNPhones() as $phone) {
			if ($phone->firstImportant > $max) {
				$max = $phone->firstImportant;
			}
			if ($phone->firstImportant < $min) {
				$min = $phone->firstImportant;
			}
		}
		$step = ($max - $min)/(FRIS::COUNT_FIRST_CLASSES - 1);
		foreach (PhonesContainer::getFirstNPhones() as $phone) {
			$classID        = ($max - $phone->firstImportant)/$step;
			$phone->classID = (int)round($classID);
			PhonesContainer::setContainer($phone);
		}
	}

	/**
	 * Выделение первичных эталонов в каждом классе (п.1)
	 */
	public static function getFirstEtalonsForEachOfNClasses()
	{
		for ($i = 0;$i < self::COUNT_FIRST_CLASSES;$i++) {
			$phones = PhonesContainer::getPhonesByClassID($i);
			if (!$phones) {
				continue;
			}
			$sum = 0;
			foreach ($phones as $phone) {
				$sum += $phone->firstImportant;
			}
			$average  = $sum/sizeof($phones);
			$margin   = $average;
			$etalonID = 0;
			foreach ($phones as $phone) {
				$phoneMargin = $phone->firstImportant - $average;
				if ($phoneMargin < $margin) {
					$margin   = $phoneMargin;
					$etalonID = $phone->id;
				}
			}
			$etalonPhone           = PhonesContainer::getContainer($etalonID);
			$etalonPhone->isEtalon = true;
			PhonesContainer::setContainer($etalonPhone);
		}
	}

	/**
	 * Выделение эталонов внутри каждого класса (п.2)
	 */
	public static function getEtalonsForEachOfNClasses()
	{
		$start = microtime(true);
		for ($i = 0;$i < self::COUNT_FIRST_CLASSES;$i++) {
			$phonesInClass    = PhonesContainer::getPhonesByClassID($i);
			$phonesOutOfClass = PhonesContainer::getPhonesNotByClassID($i);
			self::findStandard($phonesInClass, $phonesOutOfClass);
		}
	}

	/**
	 *
	 * Всопомгательная функция для рассчета обороноспособности телефона
	 * @param PhoneObject   $x объект телефона, для которго считаем обороноспособность
	 * @param PhoneObject[] $X выборка телефонов класса, к которому принадлежит телефон из первого параметра
	 *
	 * @return float
	 */
	private static function calcDefensesForPhone($x, $X)
	{
		$sum = 0;
		foreach ($X as $u) {
			if ($x->id==$u->id) {
				continue;
			}
			$omega = PhonesContainer::getAllEtalons();
			$nn    = FRIS::NN($u, $omega);
			$sum += FRIS::S($u, $x, $nn);
		}
		return $sum/(sizeof($X) - 0.9999999);
	}

	/**
	 * Нахождение ближайшего соседа для телефона из предоставленной выборки
	 *
	 * @param PhoneObject   $u телефон, для которого ищем ближайщшего соседа
	 * @param PhoneObject[] $omega выборка, из которой ищем ближайшего соседа
	 *
	 * @return PhoneObject
	 */
	private static function NN($u, $omega)
	{
		$nn          = false;
		$minDistance = 100;
		foreach ($omega as $etalon) {
			$euclidianDistance = FRIS::euclidianDistance($u, $etalon);
			if ($euclidianDistance < $minDistance) {
				$minDistance = $euclidianDistance;
				$nn          = $etalon;
			}
		}
		return $nn;
	}

	/**
	 * FRIS-функция на тройке параметров
	 * @param $u
	 * @param $x
	 * @param $nn
	 *
	 * @return float
	 */
	private static function S($u, $x, $nn)
	{
		$euclidianDistance1 = FRIS::euclidianDistance($u, $nn);
		$euclidianDistance2 = FRIS::euclidianDistance($u, $x);
		$f                  = $euclidianDistance1 + $euclidianDistance2;
		if ($f==0) {
			$f = 1;
		}
		return ($euclidianDistance1 - $euclidianDistance2)/$f;
	}

	/**
	 * Рассчет евклидова расстояния между двумя телефонами, переданными в качестве параметров
	 * @param PhoneObject $u
	 * @param PhoneObject $etalon
	 *
	 * @return float
	 */
	private static function euclidianDistance($u, $etalon)
	{
		$euclidianDistance = 0;
		for ($i = 0;$i < sizeof($u->params);$i++) {
			$euclidianDistance += pow($etalon->params[$i] - $u->params[$i], 2);
		}
		return sqrt($euclidianDistance);
	}

	/**
	 * Рассчет толерантности для телефона
	 * @param PhoneObject $phone телефон, для которого считает толерантность
	 * @param PhoneObject[] $phones выборка, на основании которой для телефона считаем толерантность
	 *
	 * @return float
	 */
	private static function calcToleranceForPhone($phone, $phones)
	{
		return FRIS::calcDefensesForPhone($phone, $phones);
	}

	/**
	 * Проверка кадого из телефонов обучабщей выборки на правильность классификации (п. 4-6)
	 * @throws \Exception
	 */
	public static function classificationPhones()
	{
		$phones = PhonesContainer::getFirstNPhones();
		$i      = 0;
		do {
			$phones = self::deleteTrueClassificated($phones);
			if ($phones) {
				for ($i = 0;$i < FRIS::COUNT_FIRST_CLASSES;$i++) {
					$phonesInClass    = PhonesContainer::getPhonesByClassID($i, $phones);
					$phonesNotInClass = PhonesContainer::getPhonesNotByClassID($i, $phones);
					FRIS::findStandard($phonesInClass, $phonesNotInClass);
				}
			}
			$i++;
			if ($i==5) {
				throw new \Exception('Функция не может классифицировать телефоны.');
			}
		} while(!empty($phones));
	}

	/**
	 * Всопомогательна функция для удаления из выборки телефонов, которые надо классифицировать, тех, которые уже
	 * правильно классифицированы (п.5)
	 * @param $phones
	 */
	public static function deleteTrueClassificated($phones)
	{
		foreach ($phones as $key => $phone) {
			if ($phone->isEtalon) {
				unset($phones[$key]);
				continue;
			}
			$x         = $phone;
			$omegaY    = [];
			$omegaNotY = [];
			foreach (PhonesContainer::getAllEtalons() as $etalon) {
				if ($x->classID==$etalon->classID) {
					$omegaY[] = $etalon;
				} else {
					$omegaNotY[] = $etalon;
				}
			}
			$nn1  = FRIS::NN($x, $omegaY);
			$nn2  = FRIS::NN($x, $omegaNotY);
			$fris = FRIS::S($x, $nn1, $nn2);
			if ($fris > 0) {
				unset($phones[$key]);
			}
		}
		return $phones;
	}

	/**
	 * Реализация функции FindStandart
	 * @param PhoneObject[] $phonesInClass подборка телефонов класса, для рассчета обороноспособности
	 * @param PhoneObject[] $phonesOutOfClass подборка телефонов за пределом класса, для рассчета толерантности
	 */
	public static function findStandard($phonesInClass, $phonesOutOfClass)
	{
		$etalon = [];
		if (!$phonesInClass) {
			return;
		}
		$maxEfficienty = -100;
		foreach ($phonesInClass as $phone) {
			$defenses   = FRIS::calcDefensesForPhone($phone, $phonesInClass);
			$tolerance  = FRIS::calcToleranceForPhone($phone, $phonesOutOfClass);
			$efficienty = FRIS::getLambda()*$defenses + (1 - FRIS::getLambda())*$tolerance;
			if ($efficienty > $maxEfficienty) {
				$maxEfficienty = $efficienty;
				$etalon        = [$phone];
			}
			if ($efficienty==$maxEfficienty) {
				$etalon[] = $phone;
			}
		}
		/**
		 * @var PhoneObject[] $etalon
		 */
		$etalonPhoneForClass = false;
		if (sizeof($etalon) > 1) {
			foreach ($etalon as $phone) {
				if ($phone->isEtalon) {
					$etalonPhoneForClass = $phone;
				}
			}
		}
		if (!$etalonPhoneForClass && isset($etalon[0])) {
			$etalonPhoneForClass = $etalon[0];
		}
		if ($etalonPhoneForClass) {
			$etalonPhoneForClass->isEtalon = true;
			PhonesContainer::setContainer($etalonPhoneForClass);
		}
	}

	/**
	 * Разбиение телефонов на кластеры, приложение D
	 */
	public static function splitPhoneByClasters()
	{
		$allClusters = [];
		$j           = 0;
		for ($i = 0;$i < FRIS::COUNT_FIRST_CLASSES;$i++) {
			$clusters         = [];
			$phonesByClass    = PhonesContainer::getPhonesByClassID($i);
			$phonesNotByClass = PhonesContainer::getPhonesNotByClassID($i);
			if (!$phonesByClass) {
				continue;
			}
			foreach ($phonesByClass as $key => $phone) {
				if ($phone->isEtalon) {
					$clusters[$j++] = [$phone];
					unset($phonesByClass[$key]);
				}
			}
			foreach ($phonesByClass as $phone) {
				$clusterID = 0;
				$maxS      = -1;
				$bj        = FRIS::NN($phone, $phonesNotByClass);
				foreach ($clusters as $id => $phonesInCluster) {
					$etalon = $phonesInCluster[0];
					$s      = FRIS::S($phone, $etalon, $bj);
					if ($s > $maxS) {
						$maxS      = $s;
						$clusterID = $id;
					}
				}
				$clusters[$clusterID][] = $phone;
			}
			$allClusters += $clusters;
		}
		foreach ($allClusters as $id => $cluster) {
			foreach ($cluster as $phone) {
				/**
				 * @var PhoneObject $phone
				 */
				$phone->classID = $id;
				PhonesContainer::setContainer($phone);
			}
		}
	}

	/**
	 * Разбиение неклассифицированных телефонов на классы, приложение E
	 */
	public static function splitPhonesNonClassesByClasses()
	{
		foreach (PhonesContainer::getPhonesNonClasses() as $phone) {
			$maxS      = -100;
			$clusterID = 0;
			if (self::CALCULATE_NON_CLASSES) {
				foreach (PhonesContainer::getAllEtalons() as $etalon) {
					$u                = $phone;
					$phonesInClass    = PhonesContainer::getPhonesByClassID($etalon->classID);
					$phonesNotInClass = PhonesContainer::getPhonesNotByClassID($etalon->classID);
					$nn1              = FRIS::NN($u, $phonesInClass);
					$nn2              = FRIS::NN($u, $phonesNotInClass);
					$s                = FRIS::S($u, $nn1, $nn2);
					if ($s > $maxS) {
						$maxS      = $s;
						$clusterID = $etalon->classID;
					}
				}
				$phone->classID = $clusterID;
			} else {
				$phone->classID = FRIS::COUNT_FIRST_CLASSES + 10;
			}
			PhonesContainer::setContainer($phone);
		}
	}

	/**
	 * Метод возвращает значение переменной "лямбда", которая может ыть задана пользователем в фильтре
	 * @return float
	 */
	private static function getLambda()
	{
		$lambda = isset($_POST['lambda'])?(float)$_POST['lambda']:FRIS::LAMBDA;
		return $lambda;
	}
}

/**
 * Класс, описывающий объект телефона
 * Class PhoneObject
 * @package frontend\controllers
 */
class PhoneObject extends Phones
{

	public $id;
	public $name;
	public $price;
	public $photo;
	public $isEtalon;
	public $classID;
	public $params;
	public $firstImportant = 0;
}

/**
 * Класс-контейнер для хранения телефонов. Единое место, где телефоны хранятся на протяжении всего цикла работы алгоритма
 * Class PhonesContainer
 * @package frontend\controllers
 */
class PhonesContainer
{

	/**
	 * @var PhoneObject[] $container
	 */
	public static $container = [];

	/**
	 * Обнуление контейнера с телефонами
	 */
	public static function resetContainer()
	{
		self::$container = [];
	}

	/**
	 * Получение телефона из контейнера
	 *
	 * @param bool $id ID телефона, который нао получить из контейнера, если параметр не передан, то возвращаются все телефоны
	 *
	 * @return PhoneObject[]|PhoneObject
	 */
	public static function getContainer($id = false)
	{
		if ($id) {
			return self::$container[$id];
		}
		return self::$container;
	}

	/**
	 * Сохранение в контейнер телефона, параданного в качестве параметра
	 *
	 * @param PhoneObject $phone телефон для сохранения в контейнере
	 */
	public static function setContainer($phone)
	{
		self::$container[$phone->id] = $phone;
	}

	/**
	 * Получение только тех телефонов, которые входят в обучающую выборку
	 *
	 * @return PhoneObject[]
	 */
	public static function getFirstNPhones()
	{
		return array_slice(self::$container, 0, FRIS::COUNT_OF_FIRST_PHONES);
	}

	/**
	 * Получение телефонов из обучающей выборки, которые не принадлежат классу, ID которого передан в качестве параметра
	 * @param               $id
	 * @param PhoneObject[] $phones
	 *
	 * @return PhoneObject[]
	 */
	public static function getPhonesNotByClassID($id, $phones = [])
	{
		$phonesArray = [];
		if (!$phones) {
			$phones = self::getContainer();
		}
		foreach ($phones as $phone) {
			if (is_null($phone->classID)) {
				continue;
			}
			if ($phone->classID!==$id) {
				$phonesArray[] = $phone;
			}
		}
		return $phonesArray;
	}

	/**
	 * Получение телефонов из обучающей выборки, которые принадлежат классу, ID которого передан в качестве параметра
	 * @param               $id
	 * @param PhoneObject[] $phones
	 *
	 * @return PhoneObject[]
	 */
	public static function getPhonesByClassID($id, $phones = [])
	{
		$phonesArray = [];
		if (!$phones) {
			$phones = self::getContainer();
		}
		foreach ($phones as $phone) {
			if ($phone->classID===$id) {
				$phonesArray[] = $phone;
			}
		}
		return $phonesArray;
	}

	/**
	 * Получение всех эталонных телефонов из обучающей выборки
	 *
	 * @return PhoneObject[]
	 */
	public static function getAllEtalons()
	{
		$arrayOfEtalons = [];
		foreach (self::getContainer() as $phone) {
			if (!$phone->isEtalon) {
				continue;
			}
			$arrayOfEtalons[] = $phone;
		}
		return $arrayOfEtalons;
	}

	/**
	 * Список всех неклассифицированных телефонвв (для приложения E)
	 * @param array $phones
	 *
	 * @return PhoneObject[]
	 */
	public static function getPhonesNonClasses($phones = [])
	{
		$phonesArray = [];
		if (!$phones) {
			$phones = self::getContainer();
		}
		foreach ($phones as $phone) {
			if (is_null($phone->classID)) {
				$phonesArray[] = $phone;
			}
		}
		return $phonesArray;
	}
}
