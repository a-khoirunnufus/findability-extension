<?php

/** @var yii\web\View $this */

use app\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;

AppAsset::register($this);
$session = Yii::$app->session;
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
  
  <?php if($session->hasFlash('notification.type') and $session->hasFlash('notification.message')): ?>
    <?= Alert::widget([
      'options' => [
          'class' => 'alert-'.$session->getFlash('notification.type').' custom-alert shadow fade show',
      ],
      'body' => $session->getFlash('notification.message'),
    ]); ?>
  <?php endif; ?>

  <main class="flex-grow-1">
    <div class="container d-flex flex-column justify-content-center" style="padding-top: 5rem; padding-bottom: 5rem;">
      <?= $content ?>
    </div>
  </main>

  <footer class="bg-dark border-top py-2 text-white" style="font-size: .8rem;">
    <div class="container">
      <div class="d-flex" style="gap: 1rem">
        <a href="#" class="text-white">Tentang Aplikasi</a>
        <div class="flex-grow-1"></div>
        <div class="text-white">&copy; File Fast <?= date('Y') ?> · <?= Yii::powered() ?></div>
      </div>
    </div>
  </footer>
  <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>