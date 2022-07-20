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
  <!-- Bootstrap 5.2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="<?= Url::to('css/quicknav.css', true) ?>">
</head>
<body>
  <div id="loading-image">
    <img src="<?= Url::to('gif/loading-default/64x64.gif', true) ?>">
  </div>

  <!-- INPUT KEYWORD -->
  <div class="input-group input-group-sm mb-3" style="width: 50%">
    <input id="input-keyword" value="<?= $keyword ?>" type="text" class="form-control" placeholder="Masukkan kata kunci" aria-describedby="button-addon">
    <button 
      class="btn btn-outline-primary fw-bolder" 
      type="button" 
      id="button-addon"
      onclick="updateKeyword()"
    >
      Terapkan
    </button>
  </div>

  <!-- ADAPTIVE VIEW -->
  <ul id="adaptive-view">
    <?php foreach($shortcuts as $shortcut): ?>
      <li class="shortcuts__item-wrapper">
        <?php foreach($shortcut as $key => $file): ?>
          <div 
            class="shortcuts__item-child"
            onclick="navigateToUrl('<?= Url::toRoute([
              'navigation/index',
              'folder_id' => $file['parent'],
              'keyword' => $keyword,
              'sort_key' => $sort_key,
              'sort_dir' => $sort_dir,
            ], true) ?>')"
          >
            <?php if($file['mimeType'] == 'application/vnd.google-apps.folder'): ?>
              <img src="<?= Url::to('icons/folder-fill.svg', true) ?>">
            <?php else: ?>
              <img src="<?= Url::to('icons/file-earmark-fill.svg', true) ?>">
            <?php endif; ?>
            &nbsp;&nbsp;<?= $file['name'] ?>
          </div> 
          <?php if($key != count($shortcut)-1): ?>
            <div class="arrow-right">></div>
          <?php endif; ?>
        <?php endforeach; ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <hr>

  <!-- BREADCRUMB -->
  <nav class="d-flex justify-content-between align-items-center border-bottom pb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <?php foreach($pathToFolder as $key => $folder): ?>
        <?php if($key == count($pathToFolder)-1): ?>
          <li class="breadcrumb-item active" aria-current="page">
            <?= $folder['name'] ?>
          </li>
        <?php else: ?>
          <li class="breadcrumb-item">
            <span 
              class="breadcrumb__text" 
              onclick="navigateToUrl('<?= Url::toRoute([
                'navigation/index',
                'folder_id' => $folder['id'],
                'keyword' => $keyword,
                'sort_key' => $sort_key,
                'sort_dir' => $sort_dir,
              ], true) ?>')"
            >
              <?= $folder['name'] ?>
            </span>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ol>
    <div style="min-width: 80px">
      <button class="btn btn-sm btn-light button-icon me-2" style="z-index: 2">
        <img src="<?= Url::to('icons/info-circle-fill.svg', true) ?>">
      </button>
      <button onclick="downloadFile()" class="btn btn-sm btn-light button-icon">
        <img src="<?= Url::to('icons/download.svg', true) ?>">
      </button>
    </div>
  </nav>

  <!-- STATIC VIEW -->
  <table id="static-view">
    <thead>
      <tr>
        <!-- COLUMN HEADER NAME -->
        <th style="width: 70%">
          Nama&nbsp;&nbsp;
          <?php if($sort_key == 'name' and $sort_dir === SORT_DESC): ?>
            <span onclick="navigateToUrl('<?= Url::toRoute([
              'navigation/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'name',
              'sort_dir' => SORT_ASC,
            ], true) ?>')">
              <img src="<?= Url::to('icons/caret-down-fill.svg', true) ?>">
            </span>
          <?php else: ?>
            <span onclick="navigateToUrl('<?= Url::toRoute([
              'navigation/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'name',
              'sort_dir' => SORT_DESC,
            ], true) ?>')">
              <img src="<?= Url::to('icons/caret-up-fill.svg', true) ?>">
            </span>
          <?php endif; ?>
        </th>
        
        <!-- COLUMN HEADER MODIFIED DATE -->
        <th style="width: 15%">
          Terakhir diubah&nbsp;&nbsp;
          <?php if($sort_key == 'modifiedByMeTime' and $sort_dir === SORT_DESC): ?>
            <span onclick="navigateToUrl('<?= Url::toRoute([
              'navigation/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'modifiedByMeTime',
              'sort_dir' => SORT_ASC,
            ], true) ?>')">
              <img src="<?= Url::to('icons/caret-down-fill.svg', true) ?>">
            </a>
          <?php else: ?>
            <span onclick="navigateToUrl('<?= Url::toRoute([
              'navigation/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'modifiedByMeTime',
              'sort_dir' => SORT_DESC,
            ], true) ?>')">
              <img src="<?= Url::to('icons/caret-up-fill.svg', true) ?>">
            </a>
          <?php endif; ?>
        </th>
        
        <!-- COLUMN HEADER SIZE -->
        <th style="width: 15%">
          Ukuran file&nbsp;&nbsp;
          <?php if($sort_key == 'size' and $sort_dir === SORT_DESC): ?>
            <span onclick="navigateToUrl('<?= Url::toRoute([
              'navigation/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'size',
              'sort_dir' => SORT_ASC,
            ], true) ?>')">
              <img src="<?= Url::to('icons/caret-down-fill.svg', true) ?>">
            </a>
          <?php else: ?>
            <span onclick="navigateToUrl('<?= Url::toRoute([
              'navigation/index',
              'folder_id' => $folder_id,
              'keyword' => $keyword,
              'sort_key' => 'size',
              'sort_dir' => SORT_DESC,
            ], true) ?>')">
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
          data-type="<?= $file['mimeType'] ?>"
          data-url="<?= Url::toRoute([
            'navigation/index', 
            'folder_id'=>$file['id'], 
            'keyword'=>$keyword, 
            'sort_key'=>$sort_key, 
            'sort_dir'=>$sort_dir
          ],true) ?>"
          <?php if($file['mimeType'] != 'application/vnd.google-apps.folder'): ?>
            data-url-download="<?= Url::toRoute([
              'file/download', 
              'file_id'=>$file['id'], 
              'file_name'=>$file['name'], 
              'file_mime_type'=>$file['mimeType'],
            ],true) ?>"
          <?php endif; ?>
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
    function updateKeyword() {
      const keyword = document.querySelector('#input-keyword').value;
      const baseUrl = '<?= Url::base(true) ?>';
      const params = '<?= "?folder_id=$folder_id&sort_key=$sort_key&sort_dir=$sort_dir" ?>';
      const paramKeyword = '&keyword='+keyword;
      const url = baseUrl + '/quicknav/navigation/index' + params + paramKeyword;
      window.location.href = url;
      document.querySelector('#loading-image').classList.add('show');
    }

    function navigateToUrl(url) {
      window.location.href = url;
      document.querySelector('#loading-image').classList.add('show');
    }

    function downloadFile() {
      const items = document.querySelectorAll('.sv__item-wrapper');
      items.forEach((elm) => {
        const isDownloadable = elm.classList.contains('active');
        const url = elm.getAttribute('data-url-download');
        if (isDownloadable && url) {
          window.location.href = url;
          alert('File sedang didownload, mohon tunggu sebentar.');
        }
      });
    }

    const items = document.querySelectorAll('.sv__item-wrapper');
    items.forEach((elm) => {
      elm.addEventListener('click', function(e) {
        if(this.classList.contains('active')) {
          const url = this.getAttribute('data-url');
          const type = this.getAttribute('data-type');
          if(type == 'application/vnd.google-apps.folder') {
            navigateToUrl(url);
          } else {
            // url to open file info
          }
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