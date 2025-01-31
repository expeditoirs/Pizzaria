<?php
include '../conexao.php'; // Inclui a conexão com o banco de dados

// Verifica se os dados do formulário foram recebidos
if (isset($_POST['valor']) && isset($_POST['categoria'])) {
    $valor = $_POST['valor'];
    $categoria = $_POST['categoria'];
    
    try {
        // Preparar a query de inserção
        $stmt = $conn->prepare("INSERT INTO receitas (valor, categoria) VALUES (:valor, :categoria)");
        
        // Vincular os parâmetros
        $stmt->bindValue(':valor', $valor);
        $stmt->bindValue(':categoria', $categoria);
        
        // Executar a query
        $stmt->execute();
        
        // Redirecionar de volta ou mostrar uma mensagem de sucesso
        header('Location: ../../admin.php?sucesso=1'); // Substitua pela URL da sua página inicial
        exit;
    } catch (PDOException $e) {
        echo "Erro ao cadastrar a receita: " . $e->getMessage();
    }
} else {
    echo "Dados inválidos!";
}
?>
