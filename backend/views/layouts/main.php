<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $content string */
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
  </head>
  <body>
    <?php $this->beginBody() ?>
    <header style="margin-bottom: 3rem">Findability Extension API</header>
    <?= $content ?>
    <footer style="margin-top: 3rem">&copy; 2022 by Ahmad Khoirunnufus</footer>
    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>