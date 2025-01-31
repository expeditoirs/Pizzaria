
<?php
ob_start(); // Inicia o buffer de saída
include '../conexao.php'; // Conectar ao banco de dados
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $preco_diaria = $_POST['preco_diaria']; // Adicionando o preço da diária
    $dias = isset($_POST['dias']) ? $_POST['dias'] : [];

    try {
        // Iniciar transação
        $conn->beginTransaction();

        // Inserir o funcionário na tabela funcionarios
        $stmt_funcionario = $conn->prepare("INSERT INTO funcionarios (nome, cargo, preco_diaria) VALUES (:nome, :cargo, :preco_diaria)");
        $stmt_funcionario->bindParam(':nome', $nome);
        $stmt_funcionario->bindParam(':cargo', $cargo);
        $stmt_funcionario->bindParam(':preco_diaria', $preco_diaria); // Vinculando o preço da diária
        $stmt_funcionario->execute();

        // Pegar o id do funcionário inserido
        $id_funcionario = $conn->lastInsertId();

        // Inserir os dias de trabalho na tabela dias_trabalho
        if (!empty($dias)) {
            $stmt_dias = $conn->prepare("INSERT INTO dias_trabalho (id_funcionario, dia) VALUES (:id_funcionario, :dia)");

            foreach ($dias as $dia) {
                $stmt_dias->bindParam(':id_funcionario', $id_funcionario);
                $stmt_dias->bindParam(':dia', $dia);
                $stmt_dias->execute();
            }
        }

        // Confirmar a transação
        $conn->commit();

       
        echo "<script>window.location.href='../../admin.php?sucesso=3';</script>";
        die("Se você está vendo isso, o header não funcionou.");
        exit;

    } catch (PDOException $e) {
        // Caso ocorra um erro, desfazer a transação
        $conn->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}
ob_end_flush();
?>
