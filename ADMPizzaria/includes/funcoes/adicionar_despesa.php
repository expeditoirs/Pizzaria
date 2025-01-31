<?php
// Incluindo o arquivo de conexão com o banco de dados
include '../conexao.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $valor = $_POST['valor']; // Valor da despesa
    $categoria = $_POST['categoria']; // Categoria da despesa
    $nome_adicional = isset($_POST['nome_adicional']) ? $_POST['nome_adicional'] : ''; // Nome do produto ou funcionário (dependendo da categoria)

    // Define a data atual
    $data_registro = date('Y-m-d H:i:s');

    try {
        // Prepare a SQL para inserir os dados na tabela 'despesas'
        $sql = "INSERT INTO despesas (valor, categoria, nome_adicional, data_registro) VALUES (:valor, :categoria, :nome_adicional, :data_registro)";
        
        $stmt = $conn->prepare($sql);
        
        // Vincula os parâmetros
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':nome_adicional', $nome_adicional);
        $stmt->bindParam(':data_registro', $data_registro);

        // Executa a consulta
        $stmt->execute();

        header('Location: ../../admin.php?sucesso=2'); // Substitua pela URL da sua página inicial
        exit;
        
    } catch (PDOException $e) {
        echo "Erro ao adicionar despesa: " . $e->getMessage();
    }
} else {
    echo "Método POST não utilizado.";
}
?>
