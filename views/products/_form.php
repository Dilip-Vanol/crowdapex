<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'store_id')->textInput() ?>

    <?= $form->field($model, 'date_inserted')->textInput() ?>

    <?= $form->field($model, 'date_removed')->textInput() ?>

    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_sku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_model_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'product_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'variant_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
