<?php
// Conectar ao banco de dados
$servername = "localhost"; // ou o seu servidor de banco de dados
$username = "root"; // seu usuário do banco de dados
$password = ""; // sua senha do banco de dados
$dbname = "padaria"; // nome do seu banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $nome = $_POST['nome'];
    $nome_fantasia = $_POST['nome_fantasia'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : null;
    $cnpj = isset($_POST['cnpj']) ? $_POST['cnpj'] : null;

    // Validar os dados (opcional, mas recomendado)
    if (empty($nome) || empty($nome_fantasia) || empty($email) || empty($telefone)) {
        die("Por favor, preencha todos os campos obrigatórios.");
    }

    // Inserir dados no banco de dados
    $sql = "INSERT INTO cad_clientes (nome, nome_fantasia, cpf, cnpj, email, telefone) 
            VALUES ('$nome', '$nome_fantasia', '$cpf', '$cnpj', '$email', '$telefone')";

    if ($conn->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso!";
        // Redirecionar para a página de clientes ou onde necessário
        header("Location: clientes.php");
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    // Fechar a conexão
    $conn->close();
}
?>