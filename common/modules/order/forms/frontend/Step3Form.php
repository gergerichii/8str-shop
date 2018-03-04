<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22.02.2018
 * Time: 12:00
 */

namespace common\modules\order\forms\frontend;

use common\models\entities\UserAddresses;
use elisdn\compositeForm\CompositeForm;
use yii\helpers\ArrayHelper;

class Step3Form extends CompositeForm {
    
    public $addressId;
    
    public function __construct($config = [])
    {
        $this->addresses = [new UserAddresses()];
        parent::__construct($config);
    }
    
    public function rules() {
        return [
            ['addressId', 'required'],
            ['addressId', 'integer'],
            ['addressId', 'in', $this->getAddressesIds()],
        ];
    }
    
    public function getAddressesIds() {
        return ArrayHelper::getColumn($this->addresses, function(UserAddresses $address) {
            return ($address->id) ? $address->id : 0;
        });
    }
    
    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected
    function internalForms() {
        return ['addresses'];
    }
}