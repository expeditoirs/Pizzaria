<?php
session_start();

// Verificar se a sessão está ativa
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para o login
    header('Location: ../index.html');
    exit;
}
?>
<div class="section-title">Adicionar Despesa</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <?php
                if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1') {
                    echo "<div id='alerta-sucesso' class='alert alert-success' role='alert'>
                            Receita cadastrada com sucesso!
                          </div>";
                }
                ?>
                <?php
                if (isset($_GET['sucesso']) && $_GET['sucesso'] == '2') {
                    echo "<div id='alerta-sucesso' class='alert alert-success' role='alert'>
                            Despesa cadastrada com sucesso!
                          </div>";
                }
                ?>
                <form id="form-despesa" method="POST" action="includes/funcoes/adicionar_despesa.php">
                    <div class="mb-3">
                        <label for="valor-despesa" class="form-label">Valor da Despesa (R$)</label>
                        <input type="number" class="form-control" id="valor-despesa" name="valor" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoria-despesa" class="form-label">Categoria de Despesa</label>
                        <select class="form-select" id="categoria-despesa" name="categoria" required onchange="mostrarCampoAdicional()">
                            <option value="ingredientes">Ingredientes</option>
                            <option value="salarios">Salários</option>
                            <option value="energia">Energia</option>
                            <option value="aluguel">Aluguel</option>
                        </select>
                    </div>
                    <!-- Campo adicional que vai mudar dependendo da categoria -->
                    <div class="mb-3" id="campo-adicional" style="display: none;">
                        <label for="nome-adicional" class="form-label" id="label-adicional"></label>
                        <input type="text" class="form-control" id="nome-adicional" name="nome_adicional">
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Adicionar Despesa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Função que mostra ou esconde o campo adicional baseado na categoria
    function mostrarCampoAdicional() {
        var categoria = document.getElementById("categoria-despesa").value;
        var campoAdicional = document.getElementById("campo-adicional");
        var labelAdicional = document.getElementById("label-adicional");

        // Verifica a categoria e ajusta o campo adicional
        if (categoria === "ingredientes") {
            labelAdicional.textContent = "Nome do Produto";
            campoAdicional.style.display = "block";
        } else if (categoria === "salarios") {
            labelAdicional.textContent = "Nome do Funcionário";
            campoAdicional.style.display = "block";
        } else {
            campoAdicional.style.display = "none";
        }
    }
</script>
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
