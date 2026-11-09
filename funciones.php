<?php

function consumirApiGet($url) {

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ]
    ]);

    $respuesta = curl_exec($ch);
    $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);

    curl_close($ch);

    if ($respuesta === false) {
        return [
            "ok" => false,
            "codigo" => $codigoHttp,
            "error" => $errorCurl,
            "datos" => null
        ];
    }

    if ($codigoHttp < 200 || $codigoHttp >= 300) {
        return [
            "ok" => false,
            "codigo" => $codigoHttp,
            "error" => "La API respondió con HTTP " . $codigoHttp,
            "datos" => null
        ];
    }

    $datos = json_decode($respuesta, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            "ok" => false,
            "codigo" => $codigoHttp,
            "error" => "La respuesta no es JSON válido",
            "datos" => null
        ];
    }

    return [
        "ok" => true,
        "codigo" => $codigoHttp,
        "error" => null,
        "datos" => $datos
    ];
}

function consumirApiPost($url, $datosEnviar) {

    $ch = curl_init();

    $json = json_encode($datosEnviar);

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Content-Type: application/json"
        ]
    ]);

    $respuesta = curl_exec($ch);
    $codigoHttp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errorCurl = curl_error($ch);

    curl_close($ch);

    if ($respuesta === false) {
        return [
            "ok" => false,
            "codigo" => $codigoHttp,
            "error" => $errorCurl,
            "datos" => null
        ];
    }

    $datos = json_decode($respuesta, true);

    return [
        "ok" => $codigoHttp >= 200 && $codigoHttp < 300,
        "codigo" => $codigoHttp,
        "error" => null,
        "datos" => $datos
    ];
}