<?php

namespace common\modules\order\models;

use common\base\models\BaseActiveRecord;
use common\models\entities\User;
use common\models\entities\UserAddresses;
use Yii;

/**
 * This is the model class for table "temporary_order".
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_address_id
 * @property array $delivery_options
 * @property array $payment_method
 * @property array $order_data
 *
 * @property UserAddresses $userAddress
 * @property User $user
 */
class TemporaryOrder extends BaseActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temporary_order';
    }
    
    public function jsonAttributes() {
        return ['delivery_options', 'payment_method', 'order_data'];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_address_id'], 'integer'],
            [['delivery_options', 'payment_method', 'order_data'], 'required'],
            [['delivery_options', 'payment_method', 'order_data'], 'array_check'],
            [['user_address_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAddresses::class, 'targetAttribute' => ['user_address_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    /**
     * @param $attr
     *
     * @return bool
     */
    public function array_check($attr) {
        if (!is_array($this->$attr)) {
            $this->addError($attr, "Не правильное значение поля {$attr}");
        }
        
        return empty($this->errors);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_address_id' => 'User Address ID',
            'delivery_options' => 'Delivery Options',
            'payment_method' => 'Payment Method',
            'order_data' => 'Order Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddress()
    {
        return $this->hasOne(UserAddresses::class, ['id' => 'user_address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return TemporaryOrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TemporaryOrderQuery(get_called_class());
    }
    
    public function notifyEveryone() {
        foreach(Yii::$app->params['managersEmails'] as $email) {
            $this->sendMail($email);
        }
        $this->sendMail($this->user->email);
    }
    
    public function sendMail($to){
        $fromName = Yii::$app->params['domains'][Yii::$app->params['domain']];
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'clientNotify-html', 'text' => 'clientNotify-text'],
                ['form' => $this]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'Робот ' . $fromName])
            ->setTo($to)
            ->setSubject("Заказ '" . Yii::$app->name . "'")
            ->send();
        
    }
}
