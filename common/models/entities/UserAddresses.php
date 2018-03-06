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
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.userAddress', 'ID'),
            'user_id' => Yii::t('app.userAddress', 'User ID'),
            'region' => Yii::t('app.userAddress', 'Region'),
            'city' => Yii::t('app.userAddress', 'City'),
            'address' => Yii::t('app.userAddress', 'Address'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
