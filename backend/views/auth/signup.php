<?php

/** @var yii\web\View $this */
/** @var app\models\SignupForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Daftar · File Fast';
?>

<div class="row">
  <div class="col-md-7">
    <div class="d-flex h-100 flex-column justify-content-center" style="margin: 0 auto; width: 100%; max-width: 400px">
      <h3 class="mb-3 text-center">Daftar</h3>
      
      <?php $form = ActiveForm::begin([
        'id' => 'signup-form',
        'layout' => 'default',
        'fieldConfig' => [
          'template' => "{label}\n{input}\n{error}",
          'labelOptions' => ['class' => ''],
          'inputOptions' => ['class' => 'form-control'],
          'errorOptions' => ['class' => 'invalid-feedback'],
        ],
      ]); ?>
        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'email')->input('email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password_repeat')->passwordInput() ?>
        <div class="form-group">
          <?= Html::submitButton('Daftar', ['class' => 'btn btn-primary w-100', 'name' => 'signup-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>

    </div>
  </div>
  <div class="col-md-5 border-left">
    <div class="d-flex h-100 flex-column justify-content-center text-center">
      <p>atau lanjutkan dengan akun google</p>
      <div id="g_id_onload"
        data-client_id="<?= CLIENT_ID ?>"
        data-login_uri="<?= SIGNIN_CALLBACK_URL ?>"
        data-auto_prompt="false"
        data-ux_mode="redirect">
      </div>
      <div class="g_id_signin"
        data-type="standard"
        data-size="large"
        data-theme="outline"
        data-text="sign_in_with"
        data-shape="rectangular"
        data-logo_alignment="center"
        style="display: flex; justify-content: center">
      </div>
    </div>
  </div>
</div>