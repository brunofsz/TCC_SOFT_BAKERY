<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFT BAKERY - Balanço Financeiro</title> <!-- Título da página -->
    <link rel="stylesheet" href="../css/financeiro.css"> <!-- Vincula o arquivo CSS -->
</head>

<body>
    <div class="container">
        <h2>Balanço Financeiro</h2> <!-- Título principal da página -->

        <!-- Formulário para filtrar os resultados financeiros -->
        <form action="financeiro_resultado.php" method="POST" class="filter-form">
            <label for="filtro">Filtrar por:</label>
            <select name="filtro" id="filtro" required> <!-- Campo de seleção para o tipo de filtro -->
                <option value="dia">Dia</option> <!-- Opção de filtro por Dia -->
                <option value="mes">Mês</option> <!-- Opção de filtro por Mês -->
                <option value="ano">Ano</option> <!-- Opção de filtro por Ano -->
            </select>

            <label for="data">Data:</label>
            <input type="date" name="data" id="data" required> <!-- Campo de entrada de data (obrigatório) -->

            <button type="submit">Filtrar</button> <!-- Botão para enviar o formulário -->
        </form>
    </div>
</body>

</html>
