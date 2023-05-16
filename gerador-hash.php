<?php
$senha = "123456";
$senhaCripto = hash('sha256', $senha);

var_dump($senha);
echo"<br>";
var_dump($senhaCripto);
?>