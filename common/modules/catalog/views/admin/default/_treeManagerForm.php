<?php

use common\modules\catalog\models\ProductRubric;
use kartik\switchinput\SwitchInput;

/** @var ProductRubric $node */

echo $form->field($node, 'visible_on_home_page')->widget(SwitchInput::class);