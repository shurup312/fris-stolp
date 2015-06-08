<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%languages}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property LandingLanguages[] $landingLanguages
 */
class Languages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%languages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название языка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLandingLanguages()
    {
        return $this->hasMany(LandingLanguages::className(), ['language_id' => 'id']);
    }
}
