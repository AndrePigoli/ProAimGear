<?php
$senha = 'admin123';
$hash = password_hash($senha, PASSWORD_DEFAULT);

echo "Senha original: $senha<br>";
echo "Hash gerado: $hash";
?>