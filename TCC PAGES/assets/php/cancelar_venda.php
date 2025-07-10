<?php
session_start();  // Inicia a sessão

// Limpar a sessão de produtos, ou seja, cancelar toda a venda
$_SESSION['produtos'] = [];

// Redirecionar de volta para a página de caixa (caixa.php, por exemplo)
header("Location: caixa.php");
exit;  // Sempre usar exit após header para garantir que o script pare de rodar aqui
?>