<?php
session_start();
$host = 'localhost';
$dbname = 'padaria';
$username = 'root';
$password = '';

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Erro na conexão: " . $e->getMessage();
  exit;
}

// Verifica se há produtos na sessão
if (isset($_SESSION['produtos']) && count($_SESSION['produtos']) > 0) {
  $produtos = $_SESSION['produtos'];
} else {
  // Caso não haja produtos, redireciona para a página 'caixa.php'
  header("Location: caixa.php");
  exit;  // Garante que o código abaixo não será executado
}

// Variáveis do pagamento
$total = 0;
foreach ($produtos as $produto) {
  $total += floatval(str_replace(',', '.', $produto['subtotal']));
}

// Definir o fuso horário para o Brasil (Brasília)
date_default_timezone_set('America/Sao_Paulo');

// Dados do pagamento (aqui você precisa de algo como um form para pegar esses dados)
// Verifica se o valor foi enviado via POST, caso contrário, define como 0
$forma_pagamento = isset($_POST['select']) ? $_POST['select'] : 0;  // valor padrão é 0
$datah = date('Y-m-d H:i:s');  // Data e hora atual para a venda

// Início da transação para garantir que ambas as inserções aconteçam ou nada aconteça
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $forma_pagamento != 0) {
  try {
    $conn->beginTransaction();

    // Inserir dados na tabela 'vendas'
    $stmt = $conn->prepare("INSERT INTO vendas (valor, datah, forma_pagamento) VALUES (:total, :datah, :forma_pagamento)");
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':datah', $datah);
    $stmt->bindParam(':forma_pagamento', $forma_pagamento);
    $stmt->execute();

    // Pegar o ID da venda recém-inserida
    $cod_ved = $conn->lastInsertId();

    // Inserir os itens na tabela 'item_vendas'
    foreach ($produtos as $produto) {
      $cod_int = $produto['codigo'];  // Código do produto
      $quantidade = $produto['quantidade'];
      $subtotal = $produto['subtotal'];

      // Inserir cada item na tabela de itens de venda
      $stmt = $conn->prepare("INSERT INTO item_vendas (cod_ved, cod_int, quantidade, subtotal) VALUES (:cod_ved, :cod_int, :quantidade, :subtotal)");
      $stmt->bindParam(':cod_ved', $cod_ved);
      $stmt->bindParam(':cod_int', $cod_int);
      $stmt->bindParam(':quantidade', $quantidade);
      $stmt->bindParam(':subtotal', $subtotal);
      $stmt->execute();
    }

    // Commit da transação
    $conn->commit();

    // Limpa os dados da sessão após a venda ser realizada
    session_unset();

    // Redireciona para a página 'caixa.php'
    header("Location: caixa.php");
    exit;  // Interrompe o script após o redirecionamento

  } catch (Exception $e) {
    // Se algo der errado, fazemos o rollback
    $conn->rollBack();
    echo "Erro ao processar a venda: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SOFT BAKERY - Venda</title>
  <link rel="stylesheet" href="../css/venda.css">
</head>

<body>
  <div class="td">
    <div class="inputs" id="inputs">
      <form method="POST">
        <div class="alinha-slct">
          <label for="select">
            <h4 class="frm">Forma de Pagamento:</h4>
          </label>
          <select name="select" id="select" required>
            <!-- Verifica o valor de forma_pagamento para manter a opção selecionada -->
            <option value="0" <?php echo ($forma_pagamento == 0) ? 'selected' : ''; ?>>Selecione</option>
            <option value="Cartão" <?php echo ($forma_pagamento == "Cartão") ? 'selected' : ''; ?>>Cartão</option>
            <option value="Dinheiro" <?php echo ($forma_pagamento == "Dinheiro") ? 'selected' : ''; ?>>Dinheiro</option>
          </select>
        </div>
        <div class="input">
          <h4>Forma de Pagamento:
            <span
              id="formaPagamentoNome"><?php echo ($forma_pagamento == "Cartão") ? "Cartão" : (($forma_pagamento == "Dinheiro") ? "Dinheiro" : "Selecione"); ?></span>
          </h4>
        </div>

        <!-- Exibe o total da venda -->
        <div class="input">
          <h4>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h4>
        </div>

        <!-- Exibe o campo de valor pago somente para Dinheiro -->
        <div id="dinheiroInputs" class="dinheiro-inputs" style="display: <?php echo ($forma_pagamento == "Dinheiro") ? 'flex' : 'none'; ?>;">
          <input type="text" class="input valor-pago" id="valorPago" placeholder="Valor Pago" oninput="calcularTroco()">
          <br>
          <div id="troco" class="input">
            <h4>Troco:</h4>
            <span id="trocoValor">R$ 0,00</span>
          </div>
        </div>

        <div class="alinha-btns">
          <button class="btn finalizar" type="submit" id="finalizarBtn" disabled>Finalizar</button>
          <!-- Botão de cancelar -->
          <button class="btn cancelar" type="button" onclick="window.location.href='caixa.php'">Cancelar</button>

        </div>

      </form>

    </div>

    <!-- Tabela de produtos -->
    <div class="tbl">
      <table>
        <tr class="head">
          <th>Num prod</th>
          <th>Cod de Barras</th>
          <th>Descrição</th>
          <th>Qtde</th>
          <th>UM</th>
          <th class="fim">Valor</th>
        </tr>
        <?php
        // Exibe os produtos da sessão
        if (isset($_SESSION['produtos']) && count($_SESSION['produtos']) > 0) {
          $total = 0;
          foreach ($_SESSION['produtos'] as $produto) {
            echo "<tr>
                <td>{$produto['codigo']}</td>
                <td>{$produto['codigo_barras']}</td>
                <td>{$produto['descricao']}</td>
                <td>{$produto['quantidade']}</td>
                <td>{$produto['unidade_medida']}</td>
                <td>R$ {$produto['subtotal']}</td>
              </tr>";
            $total += floatval(str_replace(',', '.', $produto['subtotal']));
          }
        } else {
          echo "<tr><td colspan='5'>Nenhum produto foi adicionado.</td></tr>";
        }
        ?>
      </table>
    </div>
  </div>

  <script>
    // Atualiza a forma de pagamento ao selecionar
    document.getElementById("select").addEventListener("change", function() {
      const select = this.value;
      const nomeFormaPagamento = {
        "Cartão": "Cartão",
        "Dinheiro": "Dinheiro"
      };

      // Exibindo o nome da forma de pagamento
      document.getElementById("formaPagamentoNome").innerText = nomeFormaPagamento[select] || "Selecione";

      // Exibe o campo de valor pago se for Dinheiro
      if (select == "Dinheiro") {
        document.getElementById("dinheiroInputs").style.display = "block";
      } else {
        document.getElementById("dinheiroInputs").style.display = "none";
      }

      // Desabilita o botão "Finalizar" até que a forma de pagamento seja válida
      document.getElementById("finalizarBtn").disabled = select === "0";
    });

    // Função para calcular o troco
    function calcularTroco() {
      let valorPago = parseFloat(document.getElementById("valorPago").value.replace(',', '.'));
      let totalCompra = <?php echo $total; ?>;

      if (!isNaN(valorPago) && valorPago >= totalCompra) {
        let troco = valorPago - totalCompra;
        document.getElementById("trocoValor").innerText = "R$ " + troco.toFixed(2).replace('.', ',');
        document.getElementById("finalizarBtn").disabled = false;
      } else {
        document.getElementById("trocoValor").innerText = "Valor insuficiente.";
        document.getElementById("finalizarBtn").disabled = true;
      }
    }
  </script>

</body>

</html>