<?php

namespace common\models\entities;

use common\base\models\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "user_addresses".
 *
 * @property int $id
 * @property int $user_id
 * @property string $region
 * @property string $city
 * @property string $address
 *
 * @property User $user
 */
class UserAddresses extends BaseActiveRecord
{
    public const SCENARIO_REGISTER = 'register';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'city', 'address'], 'required'],
            [['user_id'], 'integer'],
            [['region', 'city', 'address'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            ['city', 'default', 'value' => 'Москва'],
            ['region', 'default', 'value' => 'Москва'],
        ];
    }
    
    public function scenarios() {
        $ret = parent::scenarios();
        $ret[self::SCENARIO_REGISTER] = ['region', 'city', 'address'];
        return $ret;
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'region' => Yii::t('app', 'Region'),
            'city' => Yii::t('app', 'City'),
            'address' => Yii::t('app', 'Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserAddressesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserAddressesQuery(get_called_class());
    }
}
