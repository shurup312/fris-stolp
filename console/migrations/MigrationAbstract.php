<?php
/**
 * User: Oleg Prihodko
 * Mail: shuru@e-mind.ru
 * Date: 08.12.2014
 * Time: 16:08
 */
namespace console\migrations;
use yii\console\Exception;
use yii\db\Migration;

class MigrationAbstract extends Migration{

	public function up () {
		$transaction = $this->db->beginTransaction();
		try {
			$this->safeUp();
			$transaction->commit();
		} catch(Exception $e) {
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollback();
			return false;
		}
	}

	public function down () {
		$transaction = $this->db->beginTransaction();
		try {
			$this->safeDown();
			$transaction->commit();
		} catch(Exception $e) {
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollback();
			return false;
		}
	}

	/**
	 * Добавление комментария к столбцу таблицы
	 *
	 * @param string $tableName имя таблицы
	 * @param string $colunmName имя колонки в таблице
	 * @param string $comment текст комментария
	 */
	protected function addCommentToColumn($tableName, $colunmName, $comment) {

	}

	protected function updateSequence($tableName, $value) {
		//SELECT pg_catalog.setval('users_id_seq', 4, false) еще как вариант
		$this->execute('ALTER SEQUENCE '.$tableName.'_id_seq RESTART WITH '.$value);
	}
} 
