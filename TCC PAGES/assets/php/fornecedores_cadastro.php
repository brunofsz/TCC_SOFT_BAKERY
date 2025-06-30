<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFT BAKERY - CADASTRO DE FORNECEDORES</title>
    <link rel="stylesheet" href="../css/fornecedores_cadastro.css">
</head>

<body>
    <h1>Cadastro de Fornecedor (Físico/Jurídico)</h1>

    <!-- Formulário de cadastro -->
    <form action="fornecedores_cadastrar.php" method="post">
        <h3>Razão Social:</h3>
        <input type="text" class="input" name="razao_social" required>

        <h3>Nome Fantasia:</h3>
        <input type="text" class="input" name="nome_fantasia" required>

        <h3>E-mail:</h3>
        <input type="email" class="input" name="email" required>

        <h3>Telefone:</h3>
        <input class="peq input" type="text" name="telefone" required>

        <h3>CNPJ:</h3>
        <input class="peq input" type="text" name="cnpj" required>

        <!-- Botões -->
        <div class="botoes">
            <div class="dentro">
                <button type="submit" class="btn">Finalizar</button>
                <button type="reset" onclick="window.location.href='./fornecedores.php'; return false;" class="btn">Cancelar</button>
            </div>
        </div>
    </form>

</body>

</html>
