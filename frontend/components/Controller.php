<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 11.12.2014
 * Time: 14:21
 */


namespace app\components;

class Controller extends \yii\web\Controller{

	/**
	 * @param string $id
	 * @param array  $params
	 * @throws \Exception
	 * @return mixed|void
	 */
	public function runAction ($id, $params = []) {
		try {
			return parent::runAction($id, $params);
		} catch (\Exception $e){
			$this->exception();
			throw new \Exception($e->getMessage(), $e->getCode(), $e);
		}

	}

	/**
	 * Обработка исключений
	 */
	private function exception () {

	}
} 
