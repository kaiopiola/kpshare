<?php
    $uploaddir = dirname(__FILE__) . '/uploads/';
    $formato = array_reverse(explode('.', basename($_FILES['arquivo']['name'])))[0];
    $nomearquivo = basename($_FILES['arquivo']['name']);
    $uploadfile = $uploaddir . $idunica . '.' . $formato;
    $uploadfile = $uploaddir . $idunica . '.kp';

    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) {
        $subiu = true;
    } else {
        $subiu = false;
        $func = 'uploadERRO';
    }
