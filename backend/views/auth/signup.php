<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Daftar · File Fast';
?>

<div class="row">
  <div class="col-md-6">
    <div class="d-flex h-100 flex-column justify-content-center" style="margin: 0 auto; width: 100%; max-width: 400px">
      <h3 class="mb-3 text-center">Daftar</h3>
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Nama</label>
          <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan email">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Email</label>
          <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan email">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Masukkan password">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Ulangi Password</label>
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Masukkan password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Daftar</button>
      </form>
    </div>
  </div>
  <div class="col-md-6 border-left">
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