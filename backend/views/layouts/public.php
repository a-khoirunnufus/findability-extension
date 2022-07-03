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
  <header class="border-bottom shadow-sm py-3" style="position: fixed; width: 100%; z-index: 1; background-color: white;">
    <div class="container">
      <div class="d-flex align-items-center">
        <h5 class="m-0"><i class="bi bi-file-earmark-text-fill"></i> File Fast</h5>
        <div class="flex-grow-1"></div>
        <a role="button" href="<?= Url::toRoute('signin') ?>" class="btn btn-link mr-1">Masuk</a>
        <a role="button" href="<?= Url::toRoute('signup') ?>" class="btn btn-link">Daftar</a>
      </div>
    </div>
  </header>

  <main class="flex-grow-1">
    <div class="container d-flex flex-column justify-content-center" style="margin-top: 71px; padding-top: 2rem; padding-bottom: 2rem;">
      <?= $content ?>
    </div>
  </main>

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