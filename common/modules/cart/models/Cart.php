<?php
namespace common\modules\cart\models;

use common\modules\cart\interfaces\Cart as CartInterface;
use common\modules\cart\interfaces\CartElement;
use common\modules\cart\interfaces\Element;
use yii;

/**
 *
 * @property mixed $cost
 * @property mixed $elements
 * @property mixed $count
 * @property int   $id           [int(11)]
 * @property string $user_id     [varchar(55)]
 * @property string $tmp_user_id [varchar(55)]
 * @property int $created_time   [int(11)]
 * @property int $updated_time   [int(11)]
 */
class Cart extends \common\base\models\BaseActiveRecord implements CartInterface
{
    private $element = null;
    
    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function init()
    {
        $this->element = yii::$container->get('cartElement');
    }
    
    public function my()
    {
        $query = new tools\CartQuery(get_called_class());
        return $query->my();
    }
    
    /**
     * @param \common\modules\cart\interfaces\Element $elementModel
     *
     * @return \common\modules\cart\interfaces\Element
     * @throws \Exception
     */
    public function put(Element $elementModel)
    {
        $elementModel->hash = self::_generateHash($elementModel->model, $elementModel->price, $elementModel->getOptions());

        $elementModel->link('cart', $this->my());

        if ($elementModel->validate() && $elementModel->save()) {
            return $elementModel;
        } else {
            throw new \Exception(current($elementModel->getFirstErrors()));
        }
    }
    
    public function getElements()
    {
        return $this->hasMany($this->element, ['cart_id' => 'id']);
    }
    
    public function getElement(CartElement $model, $options = [])
    {
        return $this->getElements()->where(['hash' => $this->_generateHash(get_class($model), $model->getCartPrice(), $options), 'item_id' => $model->getCartId()])->one();
    }
    
    public function getElementsByModel(CartElement $model)
    {
        return $this->getElements()->andWhere(['model' => get_class($model), 'item_id' => $model->getCartId()])->all();
    }
    
    public function getElementById($id)
    {
        return $this->getElements()->andWhere(['id' => $id])->one();
    }
    
    public function getCount()
    {
        return intval($this->getElements()->sum('count'));
    }
    
    public function getCost()
    {
        return $cost = $this->getElements()->sum('price*count');
    }
    
    public function truncate()
    {
        foreach($this->elements as $element) {
            $element->delete();
        }
        
        return $this;
    }

    public function rules()
    {
        return [
            [['created_time', 'user_id'], 'required', 'on' => 'create'],
            [['tmp_user_id'], 'string'],
            [['updated_time', 'created_time'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => yii::t('cart', 'ID'),
            'user_id' => yii::t('cart', 'User ID'),
            'tmp_user_id' => yii::t('cart', 'Tmp user ID'),
            'created_time' => yii::t('cart', 'Created Time'),
            'updated_time' => yii::t('cart', 'Updated Time'),
        ];
    }
    
    public static function tableName()
    {
        return '{{%cart}}';
    }
    
    public function beforeDelete()
    {
        foreach ($this->elements as $elem) {
            $elem->delete();
        }
        
        return true;
    }
    
    private static function _generateHash($modelName, $price, $options = [])
    {
        return md5($modelName.$price.serialize($options));
    }
}
