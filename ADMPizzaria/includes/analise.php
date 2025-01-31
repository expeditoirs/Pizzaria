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
include 'conexao.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Outros scripts podem ir aqui -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<div class="container my-5">
    <h2>Calendário Financeiro</h2>
    <p>Clique em um dia e consulte as vendas e os gastos.</p>

    <!-- Calendário -->
    <input type="date" id="dataSelecionada" class="form-control my-3">

    <!-- Botão de consulta -->
    <button id="btnConsultar" class="btn btn-primary">Consultar</button>

    <!-- Área onde os dados do dia serão exibidos -->
    <div id="dadosDia" class="mt-3"></div>
</div>

<script>
$(document).ready(function() {
    $("#btnConsultar").click(function() {  // Ao clicar no botão "Consultar"
        var data = $("#dataSelecionada").val();  // Obtém o valor da data selecionada

        console.log("Data selecionada: ", data);  // Verifica a data no console

        if (data) {
            $.ajax({
                url: "includes/funcoes/get_dados.php",  // Arquivo que vai processar a data
                type: "POST",
                data: { data: data },  // Envia a data via POST
                success: function(response) {
                    console.log("Resposta recebida: ", response);  // Verifica a resposta no console
                    $("#dadosDia").html(response);  // Exibe a resposta na área "dadosDia"
                },
                error: function(xhr, status, error) {
                    console.error("Erro na requisição:", status, error);  // Mensagem de erro no console
                }
            });
        } else {
            alert("Por favor, selecione uma data.");
        }
    });
});
</script>

