<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%landing_languages}}".
 *
 * @property integer $id
 * @property integer $landing_id
 * @property integer $language_id
 *
 * @property Landings $landing
 * @property Languages $language
 */
class LandingLanguages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%landing_languages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['landing_id', 'language_id'], 'required'],
            [['landing_id', 'language_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'landing_id' => 'ID лэндинга',
            'language_id' => 'ID языка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanding()
    {
        return $this->hasOne(Landings::className(), ['id' => 'landing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Languages::className(), ['id' => 'language_id']);
    }
}
