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
    <form class="alinha-formulario" action="cadastrar_cliente.php" method="POST"> <!-- Ação que aponta para o script PHP -->
        <div class="dados">
            <h3>Nome Pessoal/Razão Social:</h3>
            <input type="text" class="input" name="nome" required> <!-- Nome do campo no banco -->
        </div>
        <div class="dados">
            <h3>Nome Fantasia:</h3>
            <input type="text" class="input" name="nome_fantasia" required>
        </div>
        <div class="dados">
            <h3>E-mail:</h3>
            <input type="email" class="input" name="email" required>
        </div>

        <div class="dados menor">

            <div class="dados cp">
                <h3>Telefone:</h3>
                <input class="peq input" type="text" name="telefone" required>
            </div>

            <div class="dados cp">
                <h3>CPF:</h3>
                <input class="peq input" type="text" name="cpf">
            </div>

            <div class="dados cp">
                <h3>CNPJ:</h3>
                <input class="peq input" type="text" name="cnpj">
            </div>
        </div>

        <div class="btns">
            <div class="dentro">
                <button type="reset" onclick="window.location.href='./clientes.php'; return false;" class="btn cancelar">Cancelar</button>
                <button type="submit" class="btn">Finalizar</button>

            </div>
        </div>
    </form>
</body>

</html>