<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QuickNav</title>
  <!-- Bootstrap 5.2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 2rem;
      position: relative;
      font-family: 'Roboto', sans-serif;
      font-size: 13px;
      color: #3c4043;
    }
    #quicknav-badge {
      padding: .25rem .5rem;
      position: absolute;
      top: 0;
      font-weight: 500;
      right: 0;
      background-color: gainsboro;
      border-radius: 0 10px 0 0;
    }
    #setup-navigation {
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }
  </style>
</head>
<body>
  <span id="quicknav-badge">QuickNav</span>

  <div id="setup-navigation">
    <div>
      <div class="input-group input-group-sm" style="width: 400px">
        <input name="keyword" type="text" class="form-control" placeholder="Masukkan kata kunci" aria-label="Recipient's username" aria-describedby="button-addon2">
        <button id="btn-start" class="btn btn-outline-primary" type="button" id="button-addon2">Mulai Penelusuran</button>
      </div>
    </div>
  </div>
</body>
</html>