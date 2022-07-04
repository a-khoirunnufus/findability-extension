<?php

/** @var yii\web\View $this */

use app\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php $this->registerCsrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
  <script src="https://accounts.google.com/gsi/client" async defer></script>
</head>

<body class="bg-light d-flex flex-column" style="min-height: 100vh">
  <?php $this->beginBody() ?>
  <header class="border-bottom shadow py-3" style="position: fixed; width: 100%; z-index: 1; background-color: white;">
    <div class="container">
      <div class="d-flex align-items-center">
        <h5 class="m-0"><i class="bi bi-file-earmark-text-fill"></i> File Fast</h5>
        <div class="flex-grow-1"></div>
        <div>
          <img src="<?= Url::to('@web/img/profile-default.png') ?>" style="height: 32px; width: 32px; margin-right: .5rem; object-fit: cover; border-radius: 50%; border: 1px solid gainsboro;">
          Ahmad Khoirunnufus
        </div>
      </div>
    </div>
  </header>

  <div class="flex-grow-1">
    <div class="container d-flex flex-row align-item-start" style="margin-top: 65px; padding-top: 2rem; padding-bottom: 2rem;">
      <aside style="width: 250px">
        <div class="card shadow-sm">
          <div class="card-body">
            <a role="button" href="<?= Url::toRoute('home/index') ?>" class="btn btn-light w-100 text-left mb-3"><i class="bi bi-house-fill mr-2"></i> Home</a>
            <a role="button" href="<?= Url::toRoute('profile/index') ?>" class="btn btn-primary w-100 text-left mb-3"><i class="bi bi-person-fill mr-2"></i> Profil</a>
            <a role="button" href="#" class="btn btn-light w-100 text-left mb-3"><i class="bi bi-info-circle-fill mr-2"></i> Panduan</a>
            <a role="button" href="#" class="btn btn-light w-100 text-left mb-5"><i class="bi bi-list-task mr-2"></i> Pengujian</a>
            <a role="button" href="<?= Url::toRoute('auth/signout') ?>" class="btn btn-light w-100 text-danger text-left"><i class="bi bi-box-arrow-left mr-2"></i> Keluar</a>
          </div>
        </div>
      </aside>
      <div class="w-100 pl-3">
        <main>
          <?= $content ?>
        </main>
      </div>
    </div>
  </div>

  <footer class="bg-dark border-top py-2 text-white" style="font-size: .8rem;">
    <div class="container">
      <div class="d-flex" style="gap: 1rem">
        <a href="<?= Url::toRoute('about') ?>" class="text-white">Tentang</a>
        <div class="flex-grow-1"></div>
        <div class="text-white">&copy; File Fast <?= date('Y') ?> · <?= Yii::powered() ?></div>
      </div>
    </div>
  </footer>
  <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>