<?php
namespace common\modules\order;

use common\models\entities\User;
use common\models\entities\UserAddresses;
use common\modules\files\models\File;
use common\modules\order\behaviors\ShippingCost;
use common\modules\order\forms\frontend\OrderForm;
use common\modules\order\models\TemporaryOrder;
use Mpdf\Gif\ImageHeader;
use yii;

class Module extends \yii\base\Module
{
    const EVENT_ORDER_CREATE = 'create';
    const EVENT_ORDER_DELETE = 'delete';
    const EVENT_ELEMENT_DELETE = 'delete_element';
    const EVENT_ORDER_UPDATE_STATUS = 'update_status';
    
    const ORDER_MODE_GUEST = 'guest';
    const ORDER_MODE_LOGIN = 'login';
    const ORDER_MODE_REGISTER = 'register';
    const ORDER_MODES = [
        self::ORDER_MODE_GUEST => 'Продолжить как гость',
        self::ORDER_MODE_LOGIN => 'Войти под своим именем',
        self::ORDER_MODE_REGISTER => 'Зарегистрироваться',
    ];

    public $countryCode = 'RU';

    public $adminRoles = ['admin', 'superadmin'];
    public $operatorRoles = ['manager', 'admin', 'superadmin'];
    public $operatorOpenStatus = 'process';

    public $orderStatuses = ['new' => 'Новый', 'approve' => 'Подтвержден', 'cancel' => 'Отменен', 'process' => 'В обработке', 'done' => 'Выполнен'];
    public $defaultStatus = 'new';

    public $successUrl = '/order/info/thanks/';
    public $orderCreateRedirect = 'order/view';

    public $robotEmail = "no-reply@localhost";
    public $dateFormat = 'd.m.Y H:i:s';
    public $robotName = 'Robot';
    public $adminNotificationEmail = false;
    public $clientEmailNotification = true;

    public $currency = ' р.';
    public $currencyPosition = 'after';
    public $priceFormat = [2, '.', ''];

    public $cartCustomFields = ['Остаток' => 'amount'];

    public $paymentFormAction = false;
    public $paymentFreeTypeIds = false;

    public $superadminRole = 'superadmin';

    public $createOrderUrl = false;

    public $userModel = '\common\modules\client\models\Client';
    public $userSearchModel = '\common\modules\client\models\client\ClientSearch';

    public $userModelCustomFields = [];

    public $productModel = 'common\modules\common\modules\models\Product';
    public $productSearchModel = 'common\modules\common\modules\models\product\ProductSearch';
    public $productCategories = null;

    public $orderColumns = ['client_name', 'phone', 'email', 'payment_type_id', 'shipping_type_id'];

    public $elementModels = []; //depricated

    public $sellers = null; //collable, return seller list

    public $sellerModel = '\common\models\User';

    public $workers = [];

    public $elementToOrderUrl = false;

    public $showPaymentColumn = false;
    public $showCountColumn = true;

    private $mail;

    public $discountDescriptionCallback = '';

    public $searchByElementNameArray = null;

    public function init()
    {
        if(yii::$app->has('cart') && $orderShippingType = yii::$app->session->get('orderShippingType')) {
            if($orderShippingType > 0) {
                yii::$app->cart->attachBehavior('ShippingCost', new ShippingCost);
            }
        }

        return parent::init();
    }

    public function getMail()
    {
        if ($this->mail === null) {
            $this->mail = yii::$app->getMailer();
            $this->mail->viewPath = __DIR__ . '/mails';

            if ($this->robotEmail !== null) {
                $this->mail->messageConfig['from'] = $this->robotName === null ? $this->robotEmail : [$this->robotEmail => $this->robotName];
            }
        }

        return $this->mail;
    }

    public function getWorkersList()
    {
        if(is_callable($this->workers)) {
            $values = $this->workers;

            return $values();
        } else {
            return $this->workers;
        }

        return [];
    }

    public function getProductCategoriesList()
    {
        if(is_callable($this->productCategories))
        {
            $values = $this->productCategories;

            return $values();
        }

        return [];
    }

    public function getSellerList()
    {
        if(is_callable($this->sellers)) {
            $values = $this->sellers;

            return $values();
        }

        return [];
    }
    
    /**
     * @param \common\modules\order\forms\frontend\OrderForm $form
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public function processOrder(OrderForm &$form) {
        $result = false;
        $newAddress = new UserAddresses(['scenario' => UserAddresses::SCENARIO_REGISTER]);
        switch($form->orderStep) {
            case 1:
                if ($form->orderMode === OrderForm::ORDER_MODE_GUEST) {
                    $user = $form->signupForm->signup(false);
                    $form->user = $user;
                    if ($user->userAddresses) {
                        $addresses = $user->userAddresses;
                        $form->userAddresses = yii\helpers\ArrayHelper::merge([$newAddress], $addresses);
                    } else {
                        $form->userAddresses = [$newAddress];
                    }
                    $form->orderStep = 2;
                    $result = true;
                } elseif ($form->orderMode === OrderForm::ORDER_MODE_LOGIN) {
                    $result = $form->loginForm->login();
                    /** @var \common\models\entities\User $user */
                    $user = Yii::$app->user->identity;
                    $form->user = $user;
                    if ($user->userAddresses) {
                        $form->userAddresses = $user->userAddresses;
                    } else {
                        $form->userAddresses = [$newAddress];
                    }
                    $form->orderStep = 2;
                }
                break;
            case 2:
                if ($form->deliveryMethod !== $form::DELIVERY_METHOD_SELF) {
                    if ((int)$form->deliveryAddressId === 0) {
                        try{
                            $userAddress = $form->userAddresses[0];
                            $form->user->link('userAddresses', $userAddress);
                            $form->userAddresses = array_merge([$newAddress], $form->user->userAddresses);
                            foreach($form->userAddresses as $i => $ua) {
                                if ($ua->id === $userAddress->id) {
                                    $form->deliveryAddressId = $i;
                                    break;
                                }
                            }
                            $result = true;
                        } catch(\Exception $e) {
                            $form->addError('userAddresses', $e->getMessage());
                        }
                    } else {
                        /** @var UserAddresses $address */
                        $address = $form->userAddresses[$form->deliveryAddressId];
                        $result = $address->save(false);
                    }
                    if ($result) $form->orderStep = 3;
                } else {
                    $form->orderStep = 3;
                }
                break;
            case 3:
                $form->cartElements = \Yii::$app->get('cartService')->elements;
                $form->orderStep = 4;
                $result = true;
                break;
            case 4:
                $result = $this->checkOut($form);
                break;
            default:
                throw new yii\base\Exception('Нет такого шага');
        }
        
        return (bool) $result;
    }
    
    /**
     * @param OrderForm $form
     *
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function checkOut(&$form) {
        if (!$form->orderModel) {
            $form->orderModel = new TemporaryOrder();
        }
        $order = &$form->orderModel;
        $order->user_id = $form->user->id;
        $userAddresses = $form->userAddresses;
        $order->user_address_id = $userAddresses[$form->deliveryAddressId]->id;
        if ($form->deliveryMethod === $form::DELIVERY_METHOD_SELF) {
            $address = Yii::$app->params['contacts']['Адрес'];
        } else {
            $address = $userAddresses[$form->deliveryAddressId];
            $address = $address->region . ', ' . $address->city . ', ' . $address->address;
        }
        $order->delivery_options = [
            'method' => $form->deliveryMethod,
            'methodName' => $form::DELIVERY_METHODS[$form->deliveryMethod],
            'methodPrice' => $form::DELIVERY_METHODS_PRICES[$form->deliveryMethod],
            'address' => $address,
            'contact' => $form->user->first_name . ' ' . $form->user->last_name,
            'email' => $form->user->email,
            'phone' => $form->user->phone_number,
            'comment' => $form->deliveryComment,
        ];
        $order->payment_method = [
            'method' => $form->paymentForm->methodId,
            'methodName' => $form->paymentForm::PAYMENT_METHODS[$form->paymentForm->methodId],
            'requisites' => $form->paymentForm->requisites,
        ];
        
        if ($cartElements = $form->cartElements) {
            /** @var \common\modules\cart\models\Cart $cart */
            $cart = reset($cartElements)->cart;
    
    
            $orderData = [
                'count' => $cart->getCount(),
                'cost' => $cart->getCost(),
                'products' => [],
            ];
            /** @var \common\modules\cart\models\CartElement $element */
            foreach($cartElements as $i => $element) {
                $dataItem = [];
                /** @var \common\modules\catalog\models\Product $product */
                $product = $element->getModel();
                $productPrice = $element->getPrice();
                $productCost = $element->getCost();
                $productCount = $element->getCount();
        
                $dataItem['name'] = $product->name;
                $dataItem['code'] = $product->id + 10000000;
                $dataItem['price'] = $productPrice;
                $dataItem['count'] = $productCount;
                $dataItem['cost'] = $productCost;
        
                /** @var \common\modules\files\Module $fileManager */
                $fileManager = Yii::$app->getModule('files');
                if (count($product->images)) {
                    $image = $product->images[0];
                    $filePath = $fileManager->getFilePath('products/images/little', $image, null, true, true);
                    $dataItem['imageMIME'] = yii\helpers\FileHelper::getMimeType($filePath);
                    $dataItem['imageName'] = pathinfo($filePath)['basename'];
                    $imageContent = file_get_contents($filePath);
                    $dataItem['image'] = base64_encode($imageContent);
                }
        
                $orderData['products'][] = $dataItem;
            }
    
            $order->order_data = $orderData;
        } else {
            $order->order_data = [];
        }
        $ret = $order->validate() && $order->save();
        
        if ($ret)
            $order->notifyEveryone();
        
        return $ret;
    }
}
