<?php

/** @var yii\web\View $this */
/** @var app\models\SigninForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$clientId = \Yii::$app->params['googleCloudClientId'];
$this->title = 'Login · QuickNav';
?>

<div class="d-flex flex-column align-items-center">
  <div class="mb-5 text-center">
    <h1 class="display-4"><i class="bi bi-lightning-charge-fill"></i> QuickNav</h1>
    <p class="lead">Temukan file di Google Drive anda dengan lebih cepat.</p>
  </div>
    
  <div>
    <div class="card shadow-sm" style="width: 400px">
      <div class="card-body text-center">
        <h5 class="mb-4 font-weight-bolder">Login</h5>
        <p>Silahkan masuk dengan akun google anda.</p>
        <div id="g_id_onload"
          data-client_id="<?= $clientId ?>"
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