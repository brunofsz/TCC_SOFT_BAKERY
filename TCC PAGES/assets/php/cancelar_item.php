<?php
session_start();  // Inicia a sessão

// Verifica se o código do produto a ser excluído foi enviado
if (isset($_POST['codigo_excluir'])) {
    $codigoExcluir = $_POST['codigo_excluir'];

    // Verifica se existe algum produto na sessão
    if (isset($_SESSION['produtos'])) {
        // Percorre a lista de produtos e remove o item com o código fornecido
        foreach ($_SESSION['produtos'] as $index => $produto) {
            if ($produto['codigo'] == $codigoExcluir) {
                unset($_SESSION['produtos'][$index]);
                break;  // Encerra o loop após excluir o item
            }
        }
    }
}

// Redireciona de volta para a página de caixa
header("Location: caixa.php");
exit;  // Garante que o script pare de rodar aqui após o redirecionamento
?>