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
		12,
		22,
		32,
		34
	];

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
		return PhonesContainer::getContainer();
	}

	public static function getFiltersIdForSQL()
	{
		return implode(',', FRIS::$filterIDArray);
	}

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

	public static function calculateFirstImportant()
	{
		foreach (PhonesContainer::getContainer() as $phone) {
			foreach ($phone->params as $key => $param) {
				$phone->firstImportant += $param*FRiS::getPriorityParams()[$key];
			}
			PhonesContainer::setContainer($phone);
		}
	}

	public static function getFirstNPhones()
	{
		FRIS::sortPhonesByFirstImportant();
	}

	private static function sortPhonesByFirstImportant()
	{
		$phones = PhonesContainer::getContainer();
		usort(
			$phones, function ($a, $b) {
			return $a->firstImportant < $b->firstImportant?1:-1;
		}
		);
		PhonesContainer::resetContainer();
		foreach ($phones as $phone) {
			PhonesContainer::setContainer($phone);
		}
	}

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

	public static function getFirstEtalonsForEachOfNClasses()
	{
		for($i = 0; $i<self::COUNT_FIRST_CLASSES;$i++){
			$phones = PhonesContainer::getPhonesByClassID($i);
			if(!$phones){
				continue;
			}
			$sum = 0;
			foreach ($phones as $phone) {
				$sum += $phone->firstImportant;
			}
			$average = $sum/sizeof($phones);
			$margin = $average;
			$etalonID = 0;
			foreach ($phones as $phone) {
				$phoneMargin = $phone->firstImportant - $average;
				if($phoneMargin<$margin){
					$margin = $phoneMargin;
					$etalonID = $phone->id;
				}
			}
			$etalonPhone = PhonesContainer::getContainer($etalonID);
			$etalonPhone->isEtalon = true;
			PhonesContainer::setContainer($etalonPhone);
		}
	}

	public static function getEtalonsForEachOfNClasses()
	{
		for($i = 0; $i<self::COUNT_FIRST_CLASSES;$i++){
			$phonesInClass = PhonesContainer::getPhonesByClassID($i);
			$phonesOutOfClass = PhonesContainer::getPhonesNotByClassID($i);
			self::findStandard($phonesInClass, $phonesOutOfClass);
		}

	}

	/**
	 * @param PhoneObject   $x
	 * @param PhoneObject[] $X
	 *
	 * @return float
	 */
	private static function calcDefensesForPhone($x, $X)
	{

		$sum = 0;
		foreach ($X as $u) {
			if($x->id == $u->id){
				continue;
			}
			$omega = PhonesContainer::getAllEtalons();
			$nn = FRIS::NN($u, $omega);
			$sum += FRIS::S($u, $x, $nn);
		}

		return $sum/(sizeof($X)-0.9999999);
	}

	/**
	 * @param PhoneObject   $u
	 * @param PhoneObject[] $omega
	 *
	 * @return PhoneObject
	 */
	private static function NN($u, $omega)
	{
		$nn = false;
		$minDistance = 100;
		foreach ($omega as $etalon) {
			$euclidianDistance = FRIS::euclidianDistance($u, $etalon);
			if($euclidianDistance<$minDistance){
				$minDistance = $euclidianDistance;
				$nn = $etalon;
			}
		}
		return $nn;
	}

	private static function S($u, $x, $nn)
	{
		$euclidianDistance1 = FRIS::euclidianDistance($u, $nn);
		$euclidianDistance2 = FRIS::euclidianDistance($u, $x);
		$f = $euclidianDistance1 + $euclidianDistance2;
		if($f == 0){
			$f = 1;
		}
		return ($euclidianDistance1 - $euclidianDistance2)/$f;
	}

	/**
	 * @param PhoneObject $u
	 * @param PhoneObject $etalon
	 *
	 * @return float
	 */
	private static function euclidianDistance($u, $etalon)
	{
		$euclidianDistance = 0;
		for($i=0;$i<sizeof($u->params); $i++){
			$euclidianDistance+= pow($etalon->params[$i]-$u->params[$i], 2);
		}
		return sqrt($euclidianDistance);
	}

	private static function calcToleranceForPhone($phone, $phones)
	{
		return FRIS::calcDefensesForPhone($phone, $phones);
	}

	public static function classificationPhones()
	{
		$phones = PhonesContainer::getFirstNPhones();
		do {
			$phones = self::deleteTrueClassificated($phones);
			if($phones){
				for($i=0; $i<FRIS::COUNT_FIRST_CLASSES; $i++){
					$phonesInClass = PhonesContainer::getPhonesByClassID($i, $phones);
					$phonesNotInClass = PhonesContainer::getPhonesNotByClassID($i, $phones);
					FRIS::findStandard($phonesInClass, $phonesNotInClass);
				}
			}
		} while(!empty($phones));
	}

	/**
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
	 * @param $phonesInClass
	 * @param $phonesOutOfClass
	 */
	public static function findStandard($phonesInClass, $phonesOutOfClass)
	{
		if(!$phonesInClass){
			return ;
		}
		$maxEfficienty = -100;
		foreach ($phonesInClass as $phone) {
			$defenses   = FRIS::calcDefensesForPhone($phone, $phonesInClass);
			$tolerance  = FRIS::calcToleranceForPhone($phone, $phonesOutOfClass);
			$efficienty = FRIS::LAMBDA*$defenses + (1 - FRIS::LAMBDA)*$tolerance;
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
		if (!$etalonPhoneForClass) {
			$etalonPhoneForClass = $etalon[0];
		}
		if ($etalonPhoneForClass) {
			$etalonPhoneForClass->isEtalon = true;
			PhonesContainer::setContainer($etalonPhoneForClass);
		}
	}

	public static function splitPhoneByClasters()
	{
		for($i=0; $i<FRIS::COUNT_FIRST_CLASSES;$i++){

		}
	}
}

class PhoneObject
{

	public $id;
	public $name;
	public $price;
	public $photo;
	public $weight;
	public $isEtalon;
	public $classID;
	public $params;
	public $firstImportant = 0;
}

class PhonesContainer
{
	/**
	 * @var PhoneObject[] $container
	 */
	public static $container = [];

	public static function resetContainer()
	{
		self::$container = [];
	}

	/**
	 * @param bool $id
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
	 * @param PhoneObject $phone
	 */
	public static function setContainer($phone)
	{

		self::$container[$phone->id] = $phone;
	}

	/**
	 * @return PhoneObject[]
	 */
	public static function getFirstNPhones()
	{
		return array_slice(self::$container, 0, FRIS::COUNT_OF_FIRST_PHONES);
	}

	/**
	 * @param       $id
	 * @param PhoneObject[] $phones
	 *
	 * @return PhoneObject[]
	 */
	public static function getPhonesNotByClassID($id, $phones = []){
		$phonesArray = [];
		if(!$phones){
			$phones = self::getContainer();
		}
		foreach ($phones as $phone) {

			if(is_null($phone->classID)){
				continue;
			}
			if($phone->classID !== $id){
				$phonesArray[] = $phone;
			}
		}
		return $phonesArray;
	}

	/**
	 * @param $id
	 * @param PhoneObject[] $phones
	 *
	 * @return PhoneObject[]
	 */
	public static function getPhonesByClassID($id, $phones = []){
		$phonesArray = [];
		if(!$phones){
			$phones = self::getContainer();
		}
		foreach ($phones as $phone) {

			if($phone->classID === $id){
				$phonesArray[] = $phone;
			}
		}
		return $phonesArray;
	}

	/**
	 * @return PhoneObject[]
	 */
	public static function getAllEtalons()
	{
		$arrayOfEtalons = [];
		foreach (self::getContainer() as $phone) {
			if(!$phone->isEtalon){
				continue;
			}
			$arrayOfEtalons[] = $phone;
		}
		return $arrayOfEtalons;
	}
}
