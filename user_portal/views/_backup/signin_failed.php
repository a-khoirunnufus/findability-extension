<?php

use yii\helpers\Url;

$session = Yii::$app->session;
?>

<h3>Gagal masuk ke sistem!</h3>
<?php if($session->hasFlash('signinFailed')): ?>
  <p><?= $session->getFlash('signinFailed') ?></p>
<?php endif; ?>
<p>Kembali ke halaman <a href="<?= Url::toRoute('auth/signup'); ?>">login</a>.</p>