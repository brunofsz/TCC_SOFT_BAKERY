<?php
session_start(); // Inicia a sessão para armazenar os produtos

// Variáveis de mensagem
$mensagem = '';
$tipoMensagem = ''; // tipoMensagem pode ser 'erro'

// Verifica se a sessão de produtos está iniciada
if (!isset($_SESSION['produtos'])) {
  $_SESSION['produtos'] = []; // Inicializa o array de produtos na sessão
}

// Conexão com o banco de dados
$host = "localhost";
$username = "root";
$password = "";
$dbname = "padaria";

$descricao = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['codigo_interno']) && isset($_POST['quantidade'])) {
    $codigo_interno = $_POST['codigo_interno'];
    $quantidade = (float) $_POST['quantidade'];

    try {
      // Conexão com o banco de dados
      $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Inicializa as variáveis
      $descricao = 'Produto não encontrado.';
      $valor = 0;
      $subtotal = 0;
      $codigo_barras = null;
      $unidade_medida = null;

      // Verifica se o produto está ativo ou busca o código unitário pelo código de barras
      $stmtProduto = $conn->prepare(
        "SELECT p.codigo_interno, p.descricao, p.valor, p.unidade_medida, p.status_prod, cb.cod_barras 
               FROM produtos p 
               LEFT JOIN codigo_barras cb ON cb.cod_interno = p.codigo_interno
               WHERE p.codigo_interno = :codigo_interno OR cb.cod_barras = :codigo_interno"
      );
      $stmtProduto->bindParam(':codigo_interno', $codigo_interno);
      $stmtProduto->execute();

      if ($stmtProduto->rowCount() > 0) {
        $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

        // Verificar o status do produto
        if ($produto['status_prod'] === 'inativo') {
          $descricao = 'Produto está inativo e não pode ser adicionado.';
          $tipoMensagem = 'erro';
        } else {
          // Preenche as informações do produto
          $descricao = $produto['descricao'];
          $valor = floatval($produto['valor']);
          $unidade_medida = $produto['unidade_medida'];
          $codigo_barras = $produto['cod_barras'];
          $codigo_interno_prod = $produto['codigo_interno']; // Obtém o código interno

          // Verifica se a quantidade é maior que 0
          if (isset($_POST['codigo_interno']) && isset($_POST['quantidade'])) {
            // Alterando para float para aceitar decimais
            $quantidade = (float) $_POST['quantidade'];

            // Verificar se a quantidade é um número válido e maior que zero
            if ($quantidade > 0) {
              $subtotalValor = $valor * $quantidade;
              $subtotal = number_format($subtotalValor, 2, ',', '.');
            } else {
              $descricao = 'Quantidade inválida.';
              $tipoMensagem = 'erro';
            }
          }
        }
      } else {
        $descricao = 'Produto não encontrado.';
        $tipoMensagem = 'erro'; // Produto não encontrado
      }

      // Adicionar à sessão quando "Adicionar Produto" for pressionado
      if (isset($_POST['adicionar_produto']) && $descricao !== 'Produto não encontrado.' && $descricao !== 'Produto está inativo e não pode ser adicionado.') {
        $_SESSION['produtos'][] = [
          'codigo' => $codigo_interno_prod, // Adiciona o código unitário aqui
          'codigo_barras' => $codigo_barras,
          'descricao' => $descricao,
          'quantidade' => $quantidade,
          'unidade_medida' => $unidade_medida,
          'subtotal' => $subtotal
        ];
      }

      // Excluir item específico
      if (isset($_POST['cancelar_item'])) {
        $codigoExcluir = $_POST['codigo_excluir'];
        foreach ($_SESSION['produtos'] as $index => $produto) {
          if ($produto['codigo'] == $codigoExcluir) {
            unset($_SESSION['produtos'][$index]);
            break;
          }
        }
        $_SESSION['produtos'] = array_values($_SESSION['produtos']); // Reindexar array
      }

      // Cancelar toda a venda
      if (isset($_POST['cancelar_venda'])) {
        $_SESSION['produtos'] = [];
      }
    } catch (PDOException $e) {
      $descricao = 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
      $valor = 0;
      $subtotal = 0;
      $tipoMensagem = 'erro';
    }
  }
}

// Consultar todos os produtos cadastrados
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmtProdutos = $conn->prepare("SELECT codigo_interno, descricao, valor FROM produtos WHERE status_prod = 'ativo'");
  $stmtProdutos->execute();
  $produtosCadastrados = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo 'Erro ao consultar produtos cadastrados: ' . $e->getMessage();
}

// Verifica se há produtos na sessão
$temProdutos = !empty($_SESSION['produtos']);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SOFT BAKERY - Caixa</title>
  <link rel="stylesheet" href="../css/caixa.css" />
  <style>
    /* Estilo do modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgb(0, 0, 0);
      background-color: rgba(0, 0, 0, 0.4);
    }

    /* Modal Conteúdo */
    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 600px;
    }

    /* Botão Fechar */
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <!-- Botões e Formulários -->

  <!-- Exibir mensagem de erro, se houver -->
  <?php if ($tipoMensagem === 'erro'): ?>
    <div class="alert erro">
      <?php echo $mensagem; ?>
    </div>
  <?php endif; ?>





  <div class="top">

    <div class="voltar">
      <a href="./home.php">
        <img src="../img/voltar.png" alt="Voltar">
      </a>
    </div>


    <div class="inputs-alinha">
      <div class="loja">
        <h4 class="">Loja:</h4>
        <div class="nomelj">
          <h4 class="txtinput">001</h4>
        </div>
      </div>

      <div class="loja">
        <h4>Operador:</h4>
        <div class="nomelj">
          <h4 class="txtinput">Balconista</h4>
        </div>
      </div>

    </div>

    <!-- BTNS -->

    <div class="servicos">
      <!-- Botão para cancelar toda a venda -->
      <form method="POST" action="cancelar_venda.php">
        <input type="submit" name="cancelar_venda" value="Cancelar Venda" />
      </form>

      <form method="POST" action="venda.php">
        <!-- Desabilita o botão se não houver produtos -->
        <input type="submit" name="venda" value="Finalizar Venda" <?php echo $temProdutos ? '' : 'disabled'; ?> />
      </form>

      <form method="POST" action="crediario.php">
        <!-- Desabilita o botão se não houver produtos -->
        <input type="submit" name="crediario" value="Venda no Crediário" <?php echo $temProdutos ? '' : 'disabled'; ?> />
      </form>

      <!-- Botão para abrir o modal -->
      <form action="">
        <button id="openModalBtn" class="inpt" type="button">Exibir Produtos </button>
      </form>
    </div>

  </div>


  <!-- TOP î FINALIZADO! -->



  <!-- <div class="camada"> -->

  <form method="POST" class="form-all">

    <div class="alinha-codigo">

      <div class="alinha-form">
        <h2 class="txtcdg">Código</h2>
        <input type="text" placeholder="Digite o Código do produto aqui" autofocus class="cdg div-inpt" name="codigo_interno"
          value="<?php echo isset($_POST['codigo_interno']) ? htmlspecialchars($_POST['codigo_interno']) : ''; ?>"
          required />
      </div>

      <div class="alinha-form">
        <h2 class="txtprd">Produto</h2>
        <div class="prd div-inpt">
          <?php echo htmlspecialchars($descricao); ?>
          <?php echo isset($unidade_medida) ? " ({$unidade_medida})" : ''; ?>
        </div>

      </div>
    </div>
    <!-- Tabela V -->

    <!-- Tabela de produtos -->
    <div class="alinha-tbl">
      <div class="tbl">
        <table class="table">
          <tr class="head">
            <th>Num prod</th>
            <th>Cod de Barras</th>
            <th>Descrição</th>
            <th>Qtde</th>
            <th>UM</th>
            <th class="fim">Valor</th>
            <th>Ações</th>
          </tr>
          <?php
          // Exibe os produtos da sessão
          foreach ($_SESSION['produtos'] as $index => $produto) {
            echo "<tr>
              <td>{$produto['codigo']}</td>
              <td>{$produto['codigo_barras']}</td>
              <td>{$produto['descricao']}</td>
              <td>{$produto['quantidade']}</td>
              <td>{$produto['unidade_medida']}</td>
              <td>R$ {$produto['subtotal']}</td>
              <td>
                <form method='POST' action='cancelar_item.php' style='display:inline;'>
                  <input type='hidden' name='codigo_excluir' value='{$produto['codigo']}' />
                  <input type='submit' value='X' />
                </form>
              </td>
            </tr>";
          }
          ?>
        </table>
      </div>









      <!-- Btn juntos | -->

      <div class="alinha-inputs-tbl">
        <div class="preco">
          <h2 class="txtprc">Preço</h2>
          <div class="prc input div-inpt">
            <?php echo !empty($valor) ? 'R$ ' . number_format($valor, 2, ',', '.') : ''; ?>
          </div>
        </div>

        <div class="quantidade">
          <h2 class="txtqtd">Quantidade</h2>

          <input type="number" placeholder="Digite a quantidade aqui" class="qtd input div-inpt" name="quantidade" value="<?php echo $quantidade; ?>" required
            step="0.01" />

        </div>

        <div class="btn_prod">
          <input type="submit" value="Buscar Produto" />
        </div>

        <div class="subtotal">
          <h2 class="txtttl">Subtotal</h2>
          <div class="sbt input div-inpt">
            <?php echo !empty($subtotal) ? 'R$ ' . htmlspecialchars($subtotal) : ''; ?>
          </div>
        </div>

        <input type="submit" name="adicionar_produto" value="Adicionar Produto" />
      </div>
    </div>
  </form>
  <!-- </div> -->

  <!-- Modal -->
  <div id="productModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Produtos Cadastrados</h2>
      <table>
        <tr class="head">
          <th>Código</th>
          <th>Descrição</th>
          <th>Preço</th>
        </tr>
        <?php foreach ($produtosCadastrados as $produto): ?>
          <tr>
            <td><?php echo htmlspecialchars($produto['codigo_interno']); ?></td>
            <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
            <td>R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>




  <script>
    // Obter o modal
    var modal = document.getElementById("productModal");

    // Obter o botão que abre o modal
    var btn = document.getElementById("openModalBtn");

    // Obter o elemento <span> que fecha o modal
    var span = document.getElementsByClassName("close")[0];

    // Quando o usuário clicar no botão, abre o modal
    btn.onclick = function() {
      modal.style.display = "block";
    }

    // Quando o usuário clicar no <span> (x), fecha o modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // Quando o usuário clicar fora do modal, ele também fecha
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>

</body>

</html>