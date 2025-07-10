<?php
session_start();

// Verificar se há produtos na sessão
if (!isset($_SESSION['produtos']) || count($_SESSION['produtos']) == 0) {
    echo "Nenhum produto foi adicionado ao carrinho.";
    exit;
}

// Verificar se o cliente foi selecionado (por CPF ou CNPJ)
if ((!isset($_POST['cpf']) || empty($_POST['cpf'])) && (!isset($_POST['cnpj']) || empty($_POST['cnpj']))) {
    echo "Por favor, forneça o CPF ou CNPJ do cliente.";
    exit;
}

$st_devedor = "sim";
$crediario = "crediário";
$cod_cliente = $_POST['cod_cliente'];
$cpf = $_POST['cpf'];
$cnpj = $_POST['cnpj'];
$produtos = $_SESSION['produtos'];

// Conexão com o banco de dados
$host = "localhost";
$username = "root";
$password = "";
$dbname = "padaria";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Iniciar a transação
    $conn->beginTransaction();

    // Calcular o total
    $total = 0;
    foreach ($produtos as $produto) {
        $total += floatval(str_replace(',', '.', $produto['subtotal']));
    }

    // Definir o fuso horário para o Brasil (Brasília)
    date_default_timezone_set('America/Sao_Paulo');


    // Inserir a venda na tabela 'vendas'
    $stmt = $conn->prepare("INSERT INTO vendas (valor, datah, forma_pagamento) VALUES (:total, :datah, :forma_pagamento)");
    $datah = date('Y-m-d H:i:s');  // Obtém a data e hora no formato YYYY-MM-DD HH:MM:SS
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':datah', $datah);
    $stmt->bindParam(':forma_pagamento', $crediario);

    //Executar o INSERT na tabela de vendas
    $stmt->execute();

    // Obter o ID da venda inserida
    $cod_ved = $conn->lastInsertId();

    // Verificar se o cod_cliente existe na tabela cad_clientes
    $stmt = $conn->prepare("SELECT cod_cliente FROM cad_clientes WHERE cod_cliente = :cod_cliente");
    $stmt->bindParam(':cod_cliente', $cod_cliente);
    $stmt->execute();

    // Se o cod_cliente não existe, lançar um erro
    if ($stmt->rowCount() == 0) {
        echo "Erro: o cliente com o cod_cliente $cod_cliente não foi encontrado.";
        exit;
    }

    // Atualizar as informações na tabela cad_clientes
    $stmt = $conn->prepare("
        UPDATE cad_clientes
        SET valor = :valor,
            datah = :datah,
            st_devedor = :st_devedor,
            cod_vend = :cod_vend
        WHERE cod_cliente = :cod_cliente
    ");

    // Associar os valores com os parâmetros da consulta
    $stmt->bindParam(':valor', $total);
    $stmt->bindParam(':datah', $datah);
    $stmt->bindParam(':st_devedor', $st_devedor);
    $stmt->bindParam(':cod_vend', $cod_ved);
    $stmt->bindParam(':cod_cliente', $cod_cliente);

    // Executar a atualização
    $stmt->execute();

    // Inserir os produtos da venda na tabela 'itens_venda'
    $stmt = $conn->prepare("INSERT INTO item_vendas (cod_ved, cod_int, quantidade, subtotal) VALUES (:cod_ved, :cod_int, :quantidade, :subtotal)");

    foreach ($produtos as $produto) {
        $stmt->bindParam(':cod_ved', $cod_ved);
        $stmt->bindParam(':cod_int', $produto['codigo']);
        $stmt->bindParam(':quantidade', $produto['quantidade']);
        $stmt->bindParam(':subtotal', $produto['subtotal']);
        $stmt->execute();
    }

    // Confirmar a transação
    $conn->commit();

    // Limpar os produtos da sessão após o processamento
    session_unset();
    // session_destroy();

    // Redirecionar para uma página de sucesso ou mostrar uma mensagem de sucesso
    echo "Venda realizada com sucesso!";
    echo "<br><a href='./caixa.php'>Voltar ao Caixa</a>";

} catch (PDOException $e) {
    // Desfaz a transação em caso de erro
    $conn->rollBack();
    // Exibe o erro real
    echo "Erro ao processar a venda: " . $e->getMessage();
    // Você também pode logar o erro para depuração
    error_log("Erro ao processar a venda: " . $e->getMessage());
}
?>