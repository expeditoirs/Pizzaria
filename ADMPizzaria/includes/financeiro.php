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
include 'conexao.php'; // Incluir a conexão com o banco de dados

// Função para calcular o lucro por semana ou mês
function calcularLucro($conn, $periodo) {
    // Calcular as receitas para o período
    $query_receitas = "
        SELECT SUM(valor) as total_receitas
        FROM receitas
        WHERE DATE_FORMAT(data_registro, '$periodo') = DATE_FORMAT(CURDATE(), '$periodo')
    ";
    $stmt_receitas = $conn->prepare($query_receitas);
    $stmt_receitas->execute();
    $total_receitas = $stmt_receitas->fetch(PDO::FETCH_ASSOC)['total_receitas'];

    // Calcular as despesas para o período
    $query_despesas = "
        SELECT SUM(valor) as total_despesas
        FROM despesas
        WHERE DATE_FORMAT(data_registro, '$periodo') = DATE_FORMAT(CURDATE(), '$periodo')
    ";
    $stmt_despesas = $conn->prepare($query_despesas);
    $stmt_despesas->execute();
    $total_despesas = $stmt_despesas->fetch(PDO::FETCH_ASSOC)['total_despesas'];

    // Calcular o lucro
    return $total_receitas - $total_despesas;
}

// Consultar as receitas e despesas para o gráfico semanal
$query_semanal = "
    SELECT categoria, SUM(valor) as total
    FROM (
        SELECT categoria, valor, data_registro FROM despesas WHERE WEEK(data_registro) = WEEK(CURDATE())
        UNION ALL
        SELECT categoria, valor, data_registro FROM receitas WHERE WEEK(data_registro) = WEEK(CURDATE())
    ) AS despesas_combinadas
    GROUP BY categoria
";
$stmt_semanal = $conn->prepare($query_semanal);
$stmt_semanal->execute();
$dados_semanal = $stmt_semanal->fetchAll(PDO::FETCH_ASSOC);

// Consultar as receitas e despesas para o gráfico mensal
$query_mensal = "
    SELECT categoria, SUM(valor) as total
    FROM (
        SELECT categoria, valor, data_registro FROM despesas WHERE MONTH(data_registro) = MONTH(CURDATE())
        UNION ALL
        SELECT categoria, valor, data_registro FROM receitas WHERE MONTH(data_registro) = MONTH(CURDATE())
    ) AS despesas_combinadas
    GROUP BY categoria
";
$stmt_mensal = $conn->prepare($query_mensal);
$stmt_mensal->execute();
$dados_mensal = $stmt_mensal->fetchAll(PDO::FETCH_ASSOC);

// Calcular lucro semanal e mensal
$lucro_semanal = calcularLucro($conn, '%Y-%u'); // Para semana, usando o formato '%Y-%u'
$lucro_mensal = calcularLucro($conn, '%Y-%m');   // Para mês, usando o formato '%Y-%m'
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Despesas e Receitas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Importando a biblioteca Chart.js -->
</head>
<body>
    <h2>Gráficos de Despesas e Receitas</h2>

    <h3>Lucro Semanal: R$ <?php echo number_format($lucro_semanal, 2, ',', '.'); ?></h3> <!-- Exibindo o lucro semanal -->
    <canvas id="graficoSemanal" width="400" height="200"></canvas> <!-- Gráfico Semanal -->

    <h3>Lucro Mensal: R$ <?php echo number_format($lucro_mensal, 2, ',', '.'); ?></h3> <!-- Exibindo o lucro mensal -->
    <canvas id="graficoMensal" width="400" height="200"></canvas> <!-- Gráfico Mensal -->

    <script>
        // Convertendo os dados do PHP para JavaScript para o gráfico semanal
        var categorias_semanal = <?php echo json_encode(array_column($dados_semanal, 'categoria')); ?>;
        var valores_semanal = <?php echo json_encode(array_column($dados_semanal, 'total')); ?>;

        // Gerando o gráfico semanal
        var ctx_semanal = document.getElementById('graficoSemanal').getContext('2d');
        var graficoSemanal = new Chart(ctx_semanal, {
            type: 'bar', // Tipo do gráfico (barras)
            data: {
                labels: categorias_semanal, // Categorias das despesas e receitas
                datasets: [{
                    label: 'Total por Categoria (R$) - Semana', // Título do gráfico
                    data: valores_semanal, // Valores somados por categoria
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', // Cor do fundo das barras
                    borderColor: 'rgba(54, 162, 235, 1)', // Cor da borda das barras
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true, // Começar o eixo Y do zero
                        title: {
                            display: true,
                            text: 'Valor (R$)'
                        }
                    }
                }
            }
        });

        // Convertendo os dados do PHP para JavaScript para o gráfico mensal
        var categorias_mensal = <?php echo json_encode(array_column($dados_mensal, 'categoria')); ?>;
        var valores_mensal = <?php echo json_encode(array_column($dados_mensal, 'total')); ?>;

        // Gerando o gráfico mensal
        var ctx_mensal = document.getElementById('graficoMensal').getContext('2d');
        var graficoMensal = new Chart(ctx_mensal, {
            type: 'bar', // Tipo do gráfico (barras)
            data: {
                labels: categorias_mensal, // Categorias das despesas e receitas
                datasets: [{
                    label: 'Total por Categoria (R$) - Mês', // Título do gráfico
                    data: valores_mensal, // Valores somados por categoria
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Cor do fundo das barras
                    borderColor: 'rgba(255, 99, 132, 1)', // Cor da borda das barras
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true, // Começar o eixo Y do zero
                        title: {
                            display: true,
                            text: 'Valor (R$)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
