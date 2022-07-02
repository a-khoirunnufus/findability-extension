<?php

/** @var yii\web\View $this */

use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · File Fast</title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<body class="d-flex flex-column" style="min-height: 100vh">
<?php $this->beginBody() ?>
    <header class="border-bottom py-3" style="position: fixed; width: 100%; z-index: 1; background-color: white;">
        <div class="container">
            <div class="d-flex align-items-center">
                <h5><i class="bi bi-file-earmark-text-fill"></i> File Fast</h5>
                <div class="flex-grow-1"></div>
                <a role="button" href="<?= Url::toRoute('signin') ?>" class="btn btn-outline">Masuk</a>
                <a role="button" href="<?= Url::toRoute('signup') ?>" class="btn btn-outline">Daftar</a>
            </div>
        </div>
    </header>
    <div class="container flex-grow-1 d-flex flex-column justify-content-center" style="margin-top: 71px; padding: 100px 0">
        <div class="row">
            <div class="col-md-7">
                <div class="d-flex h-100 flex-column justify-content-center text-center">
                    <h1 class="display-4"><i class="bi bi-file-earmark-text-fill"></i> File Fast</h1>
                    <p class="lead">Temukan file di Google Drive anda dengan lebih cepat.</p>
                </div>
            </div>
            
            <div class="col-md-5 border-left">
                <div class="d-flex h-100 flex-column justify-content-center" style="margin: 0 auto; width: 100%; max-width: 300px">
                    <h3 class="mb-3 text-center">Masuk</h3>
                    <form>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Masukkan password">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>

                    <p class="mt-3">Belum punya akun? Daftar sekarang</p>

                    <div class="w-100 border-bottom mb-4"></div>
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
    </div>
    <footer class="border-top py-3">
        <div class="container">
            <div class="d-flex" style="gap: 1rem">
                <div>Tentang</div>
                <div class="flex-grow-1"></div>
                <div>&copy; File Fast 2022</div>
            </div>
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>