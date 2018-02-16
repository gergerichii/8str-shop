<?php

use common\modules\catalog\models\ProductRubric;
use kartik\switchinput\SwitchInput;

/** @var ProductRubric $node */

echo $form->field($node, 'active')->widget(SwitchInput::class);