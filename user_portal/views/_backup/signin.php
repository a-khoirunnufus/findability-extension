<?php

/** @var yii\web\View $this */
/** @var app\models\SigninForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = 'Login · QuickNav';
?>

<div class="row">
  <div class="col-md-8">
    <div class="d-flex h-100 flex-column justify-content-center text-center">
      <h1 class="display-4"><i class="bi bi-lightning-charge-fill"></i> QuickNav</h1>
      <p class="lead">Temukan file di Google Drive anda dengan lebih cepat.</p>
    </div>
  </div>
    
  <div class="col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="d-flex h-100 flex-column justify-content-center" style="margin: 0 auto;">
          <h5 class="mb-4 text-center font-weight-bolder">Login</h5>
    
          <?php $form = ActiveForm::begin([
            'id' => 'signin-form',
            'layout' => 'default',
            'fieldConfig' => [
              'template' => "{label}\n{input}\n{error}",
              'labelOptions' => ['class' => ''],
              'inputOptions' => ['class' => 'form-control'],
              'errorOptions' => ['class' => 'invalid-feedback'],
            ],
          ]); ?>
            <?= $form->field($model, 'email')->input('email') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'remember_me')->checkbox([
              'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
    
            <div class="form-group">
              <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100', 'name' => 'signin-button']) ?>
            </div>
          <?php ActiveForm::end(); ?>
    
          <p class="mt-3">Belum punya akun? <a href="<?= Url::toRoute('signup') ?>">Daftar sekarang</a></p>
    
          <div class="w-100 border-bottom mb-4"></div>
          <p class="text-center">Atau lanjutkan dengan akun google.</p>
          <div id="g_id_onload"
            data-client_id="<?= CLIENT_ID ?>"
            data-login_uri="<?= Url::toRoute('auth/signin-with-google-callback', true) ?>"
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
  </div>
</div>