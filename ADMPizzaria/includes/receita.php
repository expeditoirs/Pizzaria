<?php
session_start();

// Verificar se a sessão está ativa
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para o login
    header('Location: ../index.html');
    exit;
}

?>
<div class="section-title">Adicionar Receita</div>
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
                <form id="form-receita" method="POST" action="includes/funcoes/adicionar_receita.php">
                    <div class="mb-3">
                        <label for="valor-receita" class="form-label">Valor da Receita (R$)</label>
                        <input type="number" class="form-control" id="valor-receita" name="valor" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoria-receita" class="form-label">Categoria de Receita</label>
                        <select class="form-select" id="categoria-receita" name="categoria" required>
                            <option value="venda-pizza">Venda de Pizza</option>
                            <option value="venda-bebida">Venda de Bebida</option>
                            <option value="promocao">Promoção</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Adicionar Receita</button>
                </form>
            </div>
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
