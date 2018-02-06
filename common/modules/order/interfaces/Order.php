<?php
namespace common\modules\order\interfaces;

interface Order
{
    public function setDeleted($deleted);
    public function setStatus($status);
    public function saveData();
}
