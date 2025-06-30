<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFT BAKERY - Cadastro de Clientes</title>
    <link rel="stylesheet" href="../css/cadastro_cliente.css">
</head>

<body>
    <h1>Cadastro de Cliente Físico/Jurídico</h1>
    <form action="cadastrar_cliente.php" method="POST"> <!-- Ação que aponta para o script PHP -->
        <h3>Nome Pessoal/Razão Social:</h3>
        <input type="text" class="input" name="nome" required> <!-- Nome do campo no banco -->

        <h3>Nome Fantasia:</h3>
        <input type="text" class="input" name="nome_fantasia" required>

        <h3>E-mail:</h3>
        <input type="email" class="input" name="email" required>

        <h3>Telefone:</h3>
        <input class="peq input" type="text" name="telefone" required>

        <div class="inputs">
            <div class="cpf">
                <h3>CPF</h3>
                <input class="peq input" type="text" name="cpf">
            </div>

            <div class="cnpj">
                <h3>CNPJ</h3>
                <input class="peq input" type="text" name="cnpj">
            </div>
        </div>

        <div class="botoes">
            <div class="dentro">
                <button type="submit" class="btn">Finalizar</button>
                <button type="reset" onclick="window.location.href='./clientes.php'; return false;" class="btn">Cancelar</button>
            </div>
        </div>
    </form>
</body>

</html>