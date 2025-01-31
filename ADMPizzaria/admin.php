<?php
session_start();

// Verificar se a sessão está ativa
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para o login
    header('Location: index.html');
    exit;
}
?>

<?php
header('Content-Type: text/html; charset=UTF-8');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>Gestão Financeira - Pizzaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Flexbox para o layout */
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1; /* Faz o main ocupar o espaço restante */
        }

        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 1rem;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    

    <!-- Include do Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main content -->
    <main>
        <div class="container my-5">
            
            <!-- Menu de navegação -->
            

            <!-- Conteúdo das seções -->
            <div id="section-receita">
                <?php include('includes/receita.php'); ?>
            </div>

            <div id="section-despesa" style="display: none;">
                <?php include('includes/despesa.php'); ?>
            </div>

            <div id="section-financeiro" style="display: none;">
                <?php include('includes/financeiro.php'); ?>
            </div>
              
            <div id="section-analise" style="display: none;">
                <?php include('includes/analise.php'); ?>
            </div>
            

            <div id="section-calcular" style="display: none;">
                <?php include('includes/calcular.php'); ?>
            </div>
            
          
        </div>
    </main>

    <!-- Include do Footer -->
    <?php include('includes/footer.php'); ?>

   <!-- Scripts do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Seu script personalizado -->
    <script>
        // O código JavaScript do showTab
        document.addEventListener("click", function (event) {
            const navbar = document.getElementById("navbarNav");
            const button = document.querySelector(".navbar-toggler");
    
            // Verifica se o menu está aberto pelo Bootstrap
            const isOpen = navbar.classList.contains("show");
    
            // Se clicar no botão, deixa o Bootstrap fazer o trabalho (NÃO interfere!)
            if (button.contains(event.target)) {
                return; // Permite que o Bootstrap controle o comportamento do botão
            }
    
            // Se clicar fora do menu, fecha o menu corretamente
            if (!navbar.contains(event.target)) {
                const bsCollapse = new bootstrap.Collapse(navbar, { toggle: false }); 
                bsCollapse.hide(); // Usa a função nativa do Bootstrap para fechar
            }
        });
    
        function showTab(tab) {
            const sections = document.querySelectorAll('div[id^="section-"]');
            sections.forEach(section => {
                section.style.display = 'none';
            });
    
            const activeSection = document.getElementById('section-' + tab);
            if (activeSection) {
                activeSection.style.display = 'block';
            }
    
            const tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
    
            const activeTab = document.getElementById('tab-' + tab);
            if (activeTab) {
                activeTab.classList.add('active');
            }
        }
    
        // Exibe a primeira seção ao carregar
        showTab('receita');
    </script>


    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
