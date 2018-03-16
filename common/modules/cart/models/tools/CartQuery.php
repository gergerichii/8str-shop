<?php
namespace common\modules\cart\models\tools;

use common\modules\cart\models\Cart;
use yii\web\Session;
use yii;

class CartQuery extends \yii\db\ActiveQuery
{
    /**
     * @param bool $userId
     *
     * @return array|\common\modules\cart\models\Cart|null|\yii\db\ActiveRecord
     */
    public function my($userId = false)
    {
        $session = yii::$app->session;

        if ($userId) {
            $one = $this->orWhere(['user_id' => $userId])->orWhere(['tmp_user_id' => $userId])->limit(1)->one();
        } elseif(!$userId = yii::$app->user->id) {
            if (!$userId = $session->get('tmp_user_id')) {
                $userId = md5(time() . '-' . yii::$app->request->userIP . Yii::$app->request->absoluteUrl);
                $session->set('tmp_user_id', $userId);
            }
            $one = $this->andWhere(['tmp_user_id' => $userId])->limit(1)->one();
        } else {
            $one = $this->andWhere(['user_id' => $userId])->limit(1)->one();
        }

        if (!$one) {
            $one = new Cart();
            $one->created_time = time();
            if(yii::$app->user->id && is_int($userId)) {
                $one->user_id = $userId;
            }
            else {
                $one->tmp_user_id = $userId;
            }
            $one->updated_time = time();
            $one->save();
        }
        
        return $one;
    }
}
