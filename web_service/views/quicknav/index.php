<?php
/** var $shortcuts array */
/** var $files array */
/** var $folder_id string */
/** var $keyword string|null */
/** var $sort_key string */
/** var $sort_dir int */

use yii\helpers\Url;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QuickNav</title>
  <title>QuickNav</title>
  <!-- Bootstrap 5.2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= Url::to('css/index.css', true) ?>">
</head>
<body>
  <div id="loading-image">
    <img src="<?= Url::to('gif/loading-default/64x64.gif', true) ?>">
  </div>
  <!-- ADAPTIVE VIEW -->
  <ul id="adaptive-view">
    <?php foreach($shortcuts as $shortcut): ?>
      <li>
        <img src="<?= Url::to('icons/file-earmark-fill.svg', true) ?>">
        &nbsp;&nbsp;<?= $shortcut['name'] ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <hr>

  <!-- BREADCRUMB -->
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <?php foreach($pathToFolder as $key => $folder): ?>
        <?php if($key == count($pathToFolder)-1): ?>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $folder['name'] ?>
          </li>
        <?php else: ?>
          <li class="breadcrumb-item">
            <a 
              class="breadcrumb__text" 
              href="<?= Url::toRoute([
                'quicknav/index',
                'folder_id' => $folder['id'],
                'keyword' => $keyword,
                'sort_key' => $sort_key,
                'sort_dir' => $sort_dir,
              ], true) ?>"
            >
              <?= $folder['name'] ?>
            </a>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ol>
  </nav>

  <!-- STATIC VIEW -->
  <table id="static-view">
    <thead>
      <tr>
        <!-- COLUMN HEADER NAME -->
        <th style="width: 65%">
          Nama&nbsp;&nbsp;
          <?php if($sort_key == 'name' and $sort_dir === SORT_DESC): ?>
            <a href="<?= Url::toRoute([
              'quicknav/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'name',
              'sort_dir' => SORT_ASC,
            ], true) ?>">
              <img src="<?= Url::to('icons/caret-down-fill.svg', true) ?>">
            </a>
          <?php else: ?>
            <a href="<?= Url::toRoute([
              'quicknav/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'name',
              'sort_dir' => SORT_DESC,
            ], true) ?>">
              <img src="<?= Url::to('icons/caret-up-fill.svg', true) ?>">
            </a>
          <?php endif; ?>
        </th>
        
        <!-- COLUMN HEADER MODIFIED DATE -->
        <th style="width: 20%">
          Terakhir diubah&nbsp;&nbsp;
          <?php if($sort_key == 'modifiedByMeTime' and $sort_dir === SORT_DESC): ?>
            <a href="<?= Url::toRoute([
              'quicknav/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'modifiedByMeTime',
              'sort_dir' => SORT_ASC,
            ], true) ?>">
              <img src="<?= Url::to('icons/caret-down-fill.svg', true) ?>">
            </a>
          <?php else: ?>
            <a href="<?= Url::toRoute([
              'quicknav/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'modifiedByMeTime',
              'sort_dir' => SORT_DESC,
            ], true) ?>">
              <img src="<?= Url::to('icons/caret-up-fill.svg', true) ?>">
            </a>
          <?php endif; ?>
        </th>
        
        <!-- COLUMN HEADER SIZE -->
        <th style="width: 15%">
          Ukuran file&nbsp;&nbsp;
          <?php if($sort_key == 'size' and $sort_dir === SORT_DESC): ?>
            <a href="<?= Url::toRoute([
              'quicknav/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'size',
              'sort_dir' => SORT_ASC,
            ], true) ?>">
              <img src="<?= Url::to('icons/caret-down-fill.svg', true) ?>">
            </a>
          <?php else: ?>
            <a href="<?= Url::toRoute([
              'quicknav/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'size',
              'sort_dir' => SORT_DESC,
            ], true) ?>">
              <img src="<?= Url::to('icons/caret-up-fill.svg', true) ?>">
            </a>
          <?php endif; ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($files as $file): ?>
        <tr 
          class="sv__item-wrapper" 
          data-url="<?= Url::toRoute([
            'quicknav/index', 'folder_id'=>$file['id'], 'keyword'=>$keyword, 'sort_key'=>$sort_key, 'sort_dir'=>$sort_dir
          ],true) ?>"
        >
          <!-- FILE NAME -->
          <td>
            <?php if($file['mimeType'] == 'application/vnd.google-apps.folder'): ?>
              <img src="<?= Url::to('icons/folder-fill.svg', true) ?>">
            <?php else: ?>
              <img src="<?= Url::to('icons/file-earmark-fill.svg', true) ?>">
            <?php endif; ?>
            &nbsp;&nbsp;<?= $file['name'] ?>
          </td>

          <!-- FILE MODIFIED DATE -->
          <td><?= date('j M Y', strtotime($file['modifiedByMeTime'])) ?></td>
          
          <!-- FILE SIZE -->
          <td>
            <?php
              $fileSize = intval($file['size']);
              // print_r($fileSize);
              if($fileSize > 0 and $fileSize < 1000000) {
                echo floor($fileSize/1000)." KB";
              } elseif($fileSize >= 1000000 and $fileSize < 1000000000) {
                echo floor($fileSize/1000000)." MB";
              } else {
                floor($fileSize/1000000000)." GB";
              }
            ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <script>
    const items = document.querySelectorAll('.sv__item-wrapper');
    items.forEach((elm) => {
      elm.addEventListener('click', function(e) {
        if(this.classList.contains('active')) {
          const url = this.getAttribute('data-url');
          window.location.href = url;
          // display loading image
          document.querySelector('#loading-image').classList.add('show');
        }
        items.forEach((item) => {
          item.classList.remove('active');
        });
        this.classList.add('active');
      });
    });
  </script>
</body>
</html>