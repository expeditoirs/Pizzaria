<?php
include '../conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se a variável de data foi recebida
if (isset($_POST['data'])) {
    $data = $_POST['data'];  // A data recebida no formato 'YYYY-MM-DD'
    
    // Formatando a data para garantir que ela seja no formato adequado (se necessário)
    $data_formatada = date('Y-m-d', strtotime($data));
    
    try {
         $stmt_receitas = $conn->prepare("SELECT id, valor, categoria, data_registro FROM receitas WHERE data_registro LIKE :data");
        $stmt_receitas->bindValue(':data', $data_formatada . '%');
        $stmt_receitas->execute();
        $receitas = $stmt_receitas->fetchAll(PDO::FETCH_ASSOC);


        // Consultando gastos
        $stmt_gastos = $conn->prepare("SELECT id, descricao, valor, data_gasto FROM gastos WHERE data_gasto = :data");
        $stmt_gastos->bindValue(':data', $data_formatada);  // Usando bindValue
        $stmt_gastos->execute();
        $gastos = $stmt_gastos->fetchAll(PDO::FETCH_ASSOC);

        // Consultando despesas
        $stmt_despesas = $conn->prepare("SELECT id, valor, categoria, nome_adicional, data_registro FROM despesas WHERE data_registro LIKE :data");
        $stmt_despesas->bindValue(':data', $data_formatada . '%');  // Usando bindValue
        $stmt_despesas->execute();
        $despesas = $stmt_despesas->fetchAll(PDO::FETCH_ASSOC);

        // Exibindo os dados formatados com Bootstrap
        echo "<h5>Resumo do Dia: " . $data_formatada . "</h5>";

        echo "<h6>Receitas:</h6>";
        if (count($receitas) > 0) {
            echo "<table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Valor (R$)</th>
                            <th>Categoria</th>
                            <th>Data do Registro</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($receitas as $receita) {
                echo "<tr>
                        <td>" . $receita['id'] . "</td>
                        <td>" . number_format($receita['valor'], 2, ',', '.') . "</td>
                        <td>" . $receita['categoria'] . "</td>
                        <td>" . date('d/m/Y H:i', strtotime($receita['data_registro'])) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Não há receitas registradas para este dia.</p>";
        }


        // Exibindo Despesas
        echo "<h6>Despesas:</h6>";
        if (count($despesas) > 0) {
            echo "<table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Valor (R$)</th>
                            <th>Categoria</th>
                            <th>Nome Adicional</th> <!-- Adiciona a coluna 'Nome Adicional' -->
                            <th>Data do Registro</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($despesas as $despesa) {
                echo "<tr>
                        <td>" . $despesa['id'] . "</td>
                        <td>" . number_format($despesa['valor'], 2, ',', '.') . "</td>
                        <td>" . $despesa['categoria'] . "</td>
                        <td>" . (isset($despesa['nome_adicional']) ? $despesa['nome_adicional'] : 'N/A') . "</td> <!-- Exibe o campo nome_adicional -->
                        <td>" . date('d/m/Y H:i', strtotime($despesa['data_registro'])) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>Não há despesas registradas para este dia.</p>";
        }

    } catch (PDOException $e) {
        echo "Erro ao consultar dados: " . $e->getMessage();
    }
} else {
    echo "<p>Data não recebida.</p>";
}
?>
