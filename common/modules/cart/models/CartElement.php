<?php
namespace common\modules\cart\models;

use common\modules\cart\models\Cart;
use common\modules\cart\events\CartElement as CartElementEvent;
use common\modules\cart\events\Cart as CartEvent;
use common\modules\cart\interfaces\Element;
use yii;
use yii\base\Exception;
use common\base\models\BaseActiveRecord;

/**
 *
 * @property mixed $modelName
 * @property mixed $itemId
 * @property mixed $price
 * @property mixed $name
 * @property array $options
 * @property mixed $count
 * @property mixed $model
 * @property mixed $comment
 * @property mixed $id
 * @property mixed $cart
 * @property int   $parent_id [int(55)]
 * @property int   $cart_id   [int(11)]
 * @property int   $item_id   [int(55)]
 * @property string $hash     [varchar(255)]
 */
class CartElement extends BaseActiveRecord implements Element
{
    const EVENT_ELEMENT_UPDATE = 'element_count';
    const EVENT_ELEMENT_DELETE = 'element_delete';

    public function getId()
    {
        return $this->id;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getName()
    {
        return $this->getModel()->getCartName();
    }

    public function getItemId()
    {
        return $this->item_id;
    }

    public function getComment()
    {
        return $this->comment;
    }
    
    /**
     * @param bool $withCartElementModel
     *
     * @return mixed|string
     * @throws \yii\base\Exception
     */
    public function getModel($withCartElementModel = true)
    {
        if(!$withCartElementModel) {
            return $this->model;
        }

        $model = '\\'.$this->model;
        if(is_string($this->model) && class_exists($this->model)) {
            $productModel = new $model();
            if ($productModel = $productModel::findOne($this->item_id)) {
                $model = $productModel;
            } else {
                yii::$app->get('cartService')->truncate();
                throw new Exception('Element model not found');
            }
        } else {
            throw new Exception('Unknow element model');
        }

        return $model;
    }

    public function getModelName()
    {
        return $this->model;
    }
    
    /**
     * @return array|mixed
     */
    public function getOptions()
    {
        if(empty($this->options)) {
            return [];
        }

        return json_decode($this->options, true);
    }

    public function setItemId($itemId)
    {
        $this->item_id = $itemId;
    }

    public function setCount($count, $andSave = false)
    {
        $this->count = $count;

        if($andSave) {
            if ($this->save()) {
                $elementEvent = new CartEvent([
                    'cart' => yii::$app->get('cartService')->getElements(),
                    'cost' => yii::$app->get('cartService')->getCost(),
                    'count' => yii::$app->get('cartService')->getCount(),
                ]);

                $cartComponent = yii::$app->get('cartService');
                $cartComponent->trigger($cartComponent::EVENT_CART_UPDATE, $elementEvent);
            }
        }
    }

    public function countIncrement($count)
    {
        $this->count = $this->count+$count;

        return $this->save();
    }

    public function getPrice($withTriggers = true)
    {
        $price = $this->price;

        $cart = yii::$app->get('cartService');

        if($withTriggers) {
            $elementEvent = new CartElementEvent(['element' => $this, 'cost' => $price]);
            $cart->trigger($cart::EVENT_ELEMENT_PRICE, $elementEvent);
            $price = $elementEvent->cost;
        }

        $elementEvent = new CartElementEvent(['element' => $this, 'cost' => $price]);
        $cart->trigger($cart::EVENT_ELEMENT_ROUNDING, $elementEvent);
        $price = $elementEvent->cost;

        return $price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setOptions($options, $andSave = false)
    {
        if(is_array($options)) {
            $this->options = json_encode($options);
        } else {
            $this->options = $options;
        }

        if($andSave) {
            $this->save();
        }
    }

    public function setComment($comment, $andSave = false)
    {
        $this->comment = $comment;

        if($andSave) {
            $this->save();
        }
    }

    public static function tableName()
    {
        return '{{%cart_element}}';
    }

    public function getCost($withTriggers = true)
    {
        $cost = 0;
        $costProduct = $this->getPrice($withTriggers);
        $cart = \Yii::$app->get('cartService');

        for($i = 0; $i < $this->count; $i++) {
            $currentCostProduct = $costProduct;
            if($withTriggers) {
                $elementEvent = new CartElementEvent(['element' => $this, 'cost' => $currentCostProduct]);
                $cart->trigger($cart::EVENT_ELEMENT_COST_CALCULATE, $elementEvent);
                $currentCostProduct = $elementEvent->cost;
            }
            $cost = $cost+$currentCostProduct;
        }

        if($withTriggers) {
            $elementEvent = new CartElementEvent(['element' => $this, 'cost' => $cost]);
            $cart->trigger($cart::EVENT_ELEMENT_COST, $elementEvent);
            $cost = $elementEvent->cost;
        }

        return $cost;
    }

    public function getCart()
    {
        return $this->hasOne(Cart::class, ['id' => 'cart_id']);
    }

    public function rules()
    {
        return [
            [['cart_id', 'model', 'item_id'], 'required'],
            [['model'], 'validateModel'],
            [['hash', 'options', 'comment'], 'string'],
            [['price'], 'double'],
            [['item_id', 'count', 'parent_id'], 'integer'],
        ];
    }

    public function validateModel($attribute, $param)
    {
        $model = $this->model;
        if (class_exists($model)) {
            $elementModel = new $model();
            if (!$elementModel instanceof \common\modules\cart\interfaces\CartElement) {
                $this->addError($attribute, 'Model implement error');
            }
        } else {
            $this->addError($attribute, 'Model not exists');
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => yii::t('cart', 'ID'),
            'parent_id' => yii::t('cart', 'Parent element'),
            'price' => yii::t('cart', 'Price'),
            'hash' => yii::t('cart', 'Hash'),
            'model' => yii::t('cart', 'Model name'),
            'cart_id' => yii::t('cart', 'Cart ID'),
            'item_id' => yii::t('cart', 'Item ID'),
            'count' => yii::t('cart', 'Count'),
            'comment' => yii::t('cart', 'Comment'),
        ];
    }

    public function beforeSave($insert)
    {
        $cart = yii::$app->get('cartService');

        $cart->cart->updated_time = time();
        $cart->cart->save();

        $elementEvent = new CartElementEvent(['element' => $this]);

        $this->trigger(self::EVENT_ELEMENT_UPDATE, $elementEvent);

        if($elementEvent->stop) {
            return false;
        } else {
            return true;
        }
    }

    public function beforeDelete()
    {
        $elementEvent = new CartElementEvent(['element' => $this]);

        $this->trigger(self::EVENT_ELEMENT_DELETE, $elementEvent);

        if($elementEvent->stop) {
            return false;
        } else {
            return true;
        }
    }
}
