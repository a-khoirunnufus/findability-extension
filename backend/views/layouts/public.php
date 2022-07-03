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

<body class="d-flex flex-column" style="min-height: 100vh">
  <?php $this->beginBody() ?>
  <header class="border-bottom py-3" style="position: fixed; width: 100%; z-index: 1; background-color: white;">
    <div class="container">
      <div class="d-flex align-items-center" style="gap: 1rem">
        <h5><i class="bi bi-file-earmark-text-fill"></i> File Fast</h5>
        <div class="flex-grow-1"></div>
        <a role="button" href="<?= Url::toRoute('signin') ?>" class="btn btn-outline-primary">Masuk</a>
        <a role="button" href="<?= Url::toRoute('signup') ?>" class="btn btn-outline-primary">Daftar</a>
      </div>
    </div>
  </header>

  <main class="flex-grow-1">
    <div class="container d-flex flex-column justify-content-center" style="margin-top: 71px; padding: 100px 0">
      <?= $content ?>
    </div>
  </main>

  <footer class="border-top py-3">
    <div class="container">
      <div class="d-flex" style="gap: 1rem">
        <a href="<?= Url::toRoute('about') ?>">Tentang</a>
        <div class="flex-grow-1"></div>
        <div>&copy; File Fast <?= date('Y') ?> · <?= Yii::powered() ?></div>
      </div>
    </div>
  </footer>
  <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>