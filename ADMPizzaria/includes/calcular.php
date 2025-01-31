<?php
session_start();

// Verificar se a sessão está ativa
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para o login
    header('Location: ../index.html');
    exit;
}
?>
<?php
include 'conexao.php'; // Conectar ao banco de dados

// Consultar funcionários cadastrados
$query_funcionarios = "SELECT * FROM funcionarios";
$stmt_funcionarios = $conn->prepare($query_funcionarios);
$stmt_funcionarios->execute();
$funcionarios = $stmt_funcionarios->fetchAll(PDO::FETCH_ASSOC);

// Verificar se foi solicitado editar o preço da diária
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
    $id_funcionario = $_GET['editar'];

    // Obter os dados do funcionário
    $query_funcionario = "SELECT * FROM funcionarios WHERE id = :id_funcionario";
    $stmt_funcionario = $conn->prepare($query_funcionario);
    $stmt_funcionario->bindParam(':id_funcionario', $id_funcionario);
    $stmt_funcionario->execute();
    $funcionario = $stmt_funcionario->fetch(PDO::FETCH_ASSOC);
}

// Atualizar preço da diária
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_funcionario'])) {
    $id_funcionario = $_POST['id_funcionario'];
    $preco_diaria = $_POST['preco_diaria'];

    try {
        // Atualizar preço da diária do funcionário
        $query_update = "UPDATE funcionarios SET preco_diaria = :preco_diaria WHERE id = :id_funcionario";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bindParam(':preco_diaria', $preco_diaria);
        $stmt_update->bindParam(':id_funcionario', $id_funcionario);
        $stmt_update->execute();

        echo "<div class='alert alert-success' role='alert'>Preço da diária atualizado com sucesso!</div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro: " . $e->getMessage() . "</div>";
    }
}

// Verificar se foi solicitado demitir um funcionário
if (isset($_GET['demitir']) && is_numeric($_GET['demitir'])) {
    $id_funcionario = $_GET['demitir'];

    try {
        // Apagar os registros de dias_trabalho associados ao funcionário
        $query_dias = "DELETE FROM dias_trabalho WHERE id_funcionario = :id_funcionario";
        $stmt_dias = $conn->prepare($query_dias);
        $stmt_dias->bindParam(':id_funcionario', $id_funcionario);
        $stmt_dias->execute();

        // Apagar o funcionário da tabela funcionarios
        $query_demitir = "DELETE FROM funcionarios WHERE id = :id_funcionario";
        $stmt_demitir = $conn->prepare($query_demitir);
        $stmt_demitir->bindParam(':id_funcionario', $id_funcionario);
        $stmt_demitir->execute();

        // Exibir alerta e redirecionar
        echo "<script>
            alert('Funcionário demitido com sucesso!');
            window.location.href='admin.php';
        </script>";
        exit;
       
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>Erro: " . $e->getMessage() . "</div>";
    }
}






?>
<?php
if (isset($_GET['sucesso']) && $_GET['sucesso'] == '3') {
    echo "<div id='alerta-sucesso' class='alert alert-success' role='alert'>
            Funcionário cadastrado com sucesso!
          </div>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastrar Funcionário</h2>
        <div class="section-title">Cadastrar Funcionário</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="includes/funcoes/cadastrar_funcionario.php">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo" name="cargo" required>
                    </div>
                    <div class="mb-3">
                        <label for="preco_diaria" class="form-label">Preço da Diária (R$)</label>
                        <input type="number" class="form-control" id="preco_diaria" name="preco_diaria" required>
                    </div>
                    <div class="mb-3">
                        <label for="dias" class="form-label">Dias da Semana que Trabalha</label><br>
                        <input type="checkbox" name="dias[]" value="segunda-feira"> Segunda-feira<br>
                        <input type="checkbox" name="dias[]" value="terça-feira"> Terça-feira<br>
                        <input type="checkbox" name="dias[]" value="quarta-feira"> Quarta-feira<br>
                        <input type="checkbox" name="dias[]" value="quinta-feira"> Quinta-feira<br>
                        <input type="checkbox" name="dias[]" value="sexta-feira"> Sexta-feira<br>
                        <input type="checkbox" name="dias[]" value="sabado"> Sábado<br>
                        <input type="checkbox" name="dias[]" value="domingo"> Domingo<br>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cadastrar Funcionário</button>
                </form>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>Preço da Diária</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($funcionarios as $funcionario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($funcionario['nome']); ?></td>
                                <td><?php echo htmlspecialchars($funcionario['cargo']); ?></td>
                                <td>R$ <?php echo number_format($funcionario['preco_diaria'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php if ($funcionario['demitido'] == 0): ?>
                                        <a href="?demitir=<?php echo $funcionario['id']; ?>" class="btn btn-danger btn-sm">Demitir</a>
                                    <?php else: ?>
                                        <span class="text-muted">Demitido</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            var alerta = document.getElementById('alerta-sucesso');
            if (alerta) {
                setTimeout(function() {
                    alerta.style.display = 'none'; // Esconde o alerta após 5 segundos
                }, 5000); // 5000 ms = 5 segundos
            }
        };
    </script>
    
</div>
</body>
</html>
<?php
include 'conexao.php'; // Conectar ao banco de dados

// Consultar funcionários cadastrados
$query_funcionarios = "SELECT * FROM funcionarios";
$stmt_funcionarios = $conn->prepare($query_funcionarios);
$stmt_funcionarios->execute();
$funcionarios = $stmt_funcionarios->fetchAll(PDO::FETCH_ASSOC);

// Função para contar quantos dias de um tipo (ex: segunda-feira) há no mês atual
function contar_dias_no_mes($dia_semana, $mes, $ano) {
    $dias_no_mes = 0;
    $num_dias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano); // Número de dias no mês
    $first_day = strtotime("$ano-$mes-01"); // Primeiro dia do mês

    // Definir a correspondência do dia da semana em português para o formato em inglês
    $dias_semana = [
        "segunda-feira" => "Monday",
        "terça-feira" => "Tuesday",
        "quarta-feira" => "Wednesday",
        "quinta-feira" => "Thursday",
        "sexta-feira" => "Friday",
        "sabado" => "Saturday",
        "domingo" => "Sunday"
    ];

    // Verificar se o dia está mapeado corretamente
    if (!isset($dias_semana[$dia_semana])) {
        return 0;
    }

    $dia_semana_ingles = $dias_semana[$dia_semana];

    for ($i = 0; $i < $num_dias; $i++) {
        $data = strtotime("+$i days", $first_day);
        $dia_atual = date('l', $data); // Dia da semana completo (ex: Monday, Tuesday, etc.)

        if ($dia_atual == $dia_semana_ingles) {
            $dias_no_mes++;
        }
    }

    return $dias_no_mes;
}

// Função para calcular o pagamento do funcionário
function calcular_pagamento($id_funcionario, $preco_diaria) {
    global $conn;
    $total_pagamento = 0;
    $dias_info = []; // Array para armazenar os dias e quantos aparecem no mês

    // Obter os dias de trabalho do funcionário
    $query_dias_trabalho = "SELECT dia FROM dias_trabalho WHERE id_funcionario = :id_funcionario";
    $stmt_dias_trabalho = $conn->prepare($query_dias_trabalho);
    $stmt_dias_trabalho->bindParam(':id_funcionario', $id_funcionario);
    $stmt_dias_trabalho->execute();
    $dias_trabalho = $stmt_dias_trabalho->fetchAll(PDO::FETCH_ASSOC);

    $ano_atual = date('Y');
    $mes_atual = date('m');
    
    foreach ($dias_trabalho as $dia) {
        // Contar quantos dias desse tipo (ex: segunda-feira, terça-feira) há no mês atual
        $dias_no_mes = contar_dias_no_mes($dia['dia'], $mes_atual, $ano_atual);
        $dias_info[] = ["dia" => $dia['dia'], "dias_no_mes" => $dias_no_mes];
        $total_pagamento += $dias_no_mes * $preco_diaria; // Pagamento do funcionário
    }

    return ["total_pagamento" => $total_pagamento, "dias_info" => $dias_info];
}

$total_pagamento_geral = 0; // Variável para armazenar o pagamento total de todos os funcionários

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Relatório de Pagamento dos Funcionários</h2>

        <?php foreach ($funcionarios as $funcionario): ?>
            <?php 
                $result = calcular_pagamento($funcionario['id'], $funcionario['preco_diaria']);
                $total_pagamento = $result['total_pagamento'];
                $dias_info = $result['dias_info'];
                $total_pagamento_geral += $total_pagamento; // Somando o pagamento de cada funcionário
            ?>
            
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Funcionário: <?php echo htmlspecialchars($funcionario['nome']); ?></strong>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Pagamento para o mês atual:</h5>
                    <p class="card-text">R$ <?php echo number_format($total_pagamento, 2, ',', '.'); ?></p>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dia</th>
                                <th>Quantidade de Dias no Mês</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dias_info as $info): ?>
                                <tr>
                                    <td><?php echo ucfirst($info['dia']); ?></td>
                                    <td><?php echo $info['dias_no_mes']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Exibir o total geral -->
        <div class="card">
            <div class="card-header">
                <strong>Total de Pagamento para Todos os Funcionários</strong>
            </div>
            <div class="card-body">
                <h5 class="card-title">Pagamento Total:</h5>
                <p class="card-text">R$ <?php echo number_format($total_pagamento_geral, 2, ',', '.'); ?></p>
            </div>
        </div>

    </div>
</body>
</html>
