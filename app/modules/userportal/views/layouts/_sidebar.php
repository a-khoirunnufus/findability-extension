<?php
use yii\helpers\Url;
?>

<div class="sidebar sidebar-fixed" id="sidebar">
  <div class="sidebar-brand d-md-flex">
    <p class="h5 m-0">QuickNav</p>
  </div>
  <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
    <li class="nav-item"><a class="nav-link" href="<?= Url::toRoute('home/index', true) ?>">
        Home</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= Url::to('/', true) ?>">
        Pengujian</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= Url::to('/', true) ?>">
        Panduan</a></li>
  </ul>
  <!-- <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button> -->
</div>