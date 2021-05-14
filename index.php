<?php

$url = explode('/', $_GET['url']);

include_once('database.php');

$baseurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}";

$hoje = date("Y-m-d");

$func = 'novo';

$qty = FetchRow("SELECT count(*) as contagem FROM file WHERE data_upload LIKE '$hoje%'");
$qty = $qty->contagem;

if ($url[0] == 'file') {
    $func = 'download';
    $chave = $url[1];
    $arquivo = FetchRow("SELECT * FROM file WHERE idunica='$chave'");
}

if ($url[0] == 'download') {
    include_once('download.php');
    exit;
}

if ($url[0] == 'share') {
    $chave = $url[1];
    $func = 'uploadOK';
}

if (isset($_REQUEST['function'])) {
    if ($_REQUEST['function'] == 'upload') {
        $func = 'upload';
    }
}

if ($func == 'upload') {
    $idunica = uniqid();
    include_once('uploader.php');

    if ($subiu) {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (Query("INSERT INTO file (idunica, arquivo, host) VALUES ('$idunica', '$nomearquivo', '$ip')")) {
            $func = 'uploadOK';
            header("Location: $baseurl/share/$idunica");
        } else {
            $func = 'uploadERRO';
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <meta property="og:image" content="/img/logo.png" />
    <link rel="icon" href="/img/icon.png" type="image/x-icon" />
    <link rel="shortcut icon" href="/img/icon.png" type="image/x-icon" />
</head>


<body class="text-light bg-dark">

    <div class="container" style="height: 90vh;">
        <div class="row h-100">
            <div class="col-xs-12 col-md-6 offset-md-3 my-auto text-center">

                <div class="text-center">
                    <a href="<?= $baseurl ?>"><img src="/img/logo.png" class="img-fluid" width="200px" alt="Logo Kpshare"></a>
                </div>

                <?php if ($func == 'novo') : ?>
                    <title>Kpshare</title>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="function" value="upload">
                        <div class="form-group">
                            <label>Selecione um arquivo</label>
                            <input type="file" class="form-control-file" name="arquivo">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-default">Enviar</button>
                        </div>
                    </form>

                    <p><b><?= $qty ?></b> arquivos compartilhados hoje!</p>
                <?php endif; ?>

                <?php if ($func == 'uploadOK') : ?>
                    <title>Kpshare</title>
                    <p>Aqui está seu link compartilhável para download:</p>
                    <input id="link" type="text" class="form-control" value="<?= $baseurl ?>/file/<?= $chave ?>"><br>
                    <button class="btn btn-default" onclick="Copiar()">Copiar</button>

                    <script>
                        function Copiar() {
                            var copyText = document.getElementById("link");

                            copyText.select();
                            copyText.setSelectionRange(0, 99999);
                            document.execCommand("copy");

                            alert('Link copiado para a área de transferência!');
                        }
                    </script>
                <?php endif; ?>

                <?php if ($func == 'uploadERRO') : ?>
                    <p>Ocorreu um erro ao fazer o upload do seu arquivo.</p>
                <?php endif; ?>

                <?php if ($func == 'download') : ?>
                    <title><?= $arquivo->arquivo ?> - Kpshare</title>
                    <p>Para efetuar o download de <b><?= $arquivo->arquivo ?></b>, clique abaixo:</p>
                    <a href="/download/<?= $chave ?>"><button class="btn btn-default">Download</button></a>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <footer>
        <div class="col-md-12 text-center"><i>© Kpsoft 2021</i></div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>