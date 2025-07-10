<?php

if (isset($_POST['filtro']) && isset($_POST['data'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "padaria";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Erro ao conectar: ' . $e->getMessage());
    }

    $filtro = htmlspecialchars($_POST['filtro']); // Evita XSS
    $data = htmlspecialchars($_POST['data']); // Evita XSS

    if (!in_array($filtro, ['ano', 'mes', 'dia'])) {
        die("Filtro inválido.");
    }

    $query_vendas = "SELECT cod_venda FROM vendas";
    if ($filtro == 'ano') {
        $query_vendas .= " WHERE YEAR(datah) = :data";
    } elseif ($filtro == 'mes') {
        $query_vendas .= " WHERE YEAR(datah) = :ano AND MONTH(datah) = :mes";
    } elseif ($filtro == 'dia') {
        $query_vendas .= " WHERE datah = :data";
    }

    $stmt_vendas = $pdo->prepare($query_vendas);

    if ($filtro == 'ano') {
        $stmt_vendas->bindParam(':data', $data, PDO::PARAM_INT);
    } elseif ($filtro == 'mes') {
        $ano = substr($data, 0, 4);
        $mes = substr($data, 5, 2);
        $stmt_vendas->bindParam(':ano', $ano, PDO::PARAM_INT);
        $stmt_vendas->bindParam(':mes', $mes, PDO::PARAM_INT);
    } elseif ($filtro == 'dia') {
        $stmt_vendas->bindParam(':data', $data, PDO::PARAM_STR);
    }

    try {
        $stmt_vendas->execute();
    } catch (PDOException $e) {
        die("Erro ao buscar vendas: " . $e->getMessage());
    }

    $total_lucro = 0;
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SOFT BAKERY - Resultados Financeiros</title>
        <link rel="stylesheet" href="../css/financeiro_resultado.css">
    </head>

    <body>
        <div class="container">
            <h2>Resultados Financeiros</h2>
            <table class="result-table">
                <thead>
                    <tr>
                        <th>Código da Venda</th>
                        <th>Código do Produto</th>
                        <th>Descrição do Produto</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $vendas_encontradas = false;
                    while ($venda = $stmt_vendas->fetch(PDO::FETCH_ASSOC)) {
                        $vendas_encontradas = true;
                        $cod_venda = $venda['cod_venda'];

                        $query_itens = "SELECT cod_item, quantidade, subtotal FROM item_vendas WHERE cod_ved = :cod_venda";
                        $stmt_itens = $pdo->prepare($query_itens);
                        $stmt_itens->bindParam(':cod_venda', $cod_venda, PDO::PARAM_INT);
                        $stmt_itens->execute();

                        while ($item = $stmt_itens->fetch(PDO::FETCH_ASSOC)) {
                            $cod_item = $item['cod_item'];
                            $quantidade = $item['quantidade'];
                            $subtotal = $item['subtotal'];

                            $query_produto = "SELECT descricao FROM produtos WHERE codigo_interno = :cod_item";
                            $stmt_produto = $pdo->prepare($query_produto);
                            $stmt_produto->bindParam(':cod_item', $cod_item, PDO::PARAM_INT);
                            $stmt_produto->execute();
                            $produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);
                            $descricao_produto = $produto['descricao'];

                            echo "<tr>
                                    <td>{$cod_venda}</td>
                                    <td>{$cod_item}</td>
                                    <td>{$descricao_produto}</td>
                                    <td>{$quantidade}</td>
                                    <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
                                  </tr>";

                            $total_lucro += $subtotal;
                        }
                    }

                    if (!$vendas_encontradas) {
                        echo "<tr><td colspan='5'>Nenhuma venda encontrada para o filtro selecionado.</td></tr>";
                    } else {
                        echo "<tr style='font-weight: bold; background-color: #9fa8f7; color: white;'>
                                <td colspan='4'>Lucro Total</td>
                                <td>R$ " . number_format($total_lucro, 2, ',', '.') . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <a href="home.php" class="btn-home">Voltar para a Home</a>
        </div>
    </body>

    </html>

<?php } else {
    echo "Parâmetros não encontrados. Verifique a URL ou o formulário.";
} ?>
