<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * CharactersValues model
 *
 * @property integer $id
 * @property string  $cid
 * @property string  $pid
 * @property string  $value
 */
class CharactersValues extends ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName () {
		return '{{%characters_value}}';
	}
	public function getNames()
	{
		return $this->hasOne(Characters::className(), ['id' => 'cid']);
	}

}
