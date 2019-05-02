<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Prices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'product')->textInput() ?>

    <?= $form->field($model, 'normal_price')->textInput() ?>

    <?= $form->field($model, 'sale_price')->textInput() ?>

    <?= $form->field($model, 'sale_start')->textInput() ?>

    <?= $form->field($model, 'sale_end')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
