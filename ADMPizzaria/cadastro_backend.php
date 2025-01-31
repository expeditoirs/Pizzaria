<?php
// Iniciar a sessão
session_start();

require_once 'includes/conexao.php';    



// Função para criptografar a senha
function criptografar_senha($senha) {
    return password_hash($senha, PASSWORD_DEFAULT);
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // Verificar se as senhas coincidem
    if ($senha != $confirma_senha) {
        $_SESSION['erro'] = "As senhas não coincidem!";
        header("Location: cadastro.php");
        exit();
    }

    // Criptografar a senha
    $senha_criptografada = criptografar_senha($senha);

    try {
        // Verificar se o email já está cadastrado
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['erro'] = "Este email já está cadastrado!";
            header("Location: cadastro.php");
            exit();
        }

        // Inserir o novo usuário no banco de dados
        $query_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bindParam(':nome', $nome);
        $stmt_insert->bindParam(':email', $email);
        $stmt_insert->bindParam(':senha', $senha_criptografada);
        $stmt_insert->execute();

        // Sucesso no cadastro
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Agora, faça login.";
        header("Location: index.html");
        exit();

    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao cadastrar: " . $e->getMessage();
        header("Location: cadastro.php");
        exit();
    }
}
?>
