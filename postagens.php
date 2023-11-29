<?php
session_start();
// Verificar se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtém o nome completo do usuário da sessão
$nomeCompleto = $_SESSION['usuario']['nome'];

// Extrair o primeiro nome e sobrenome do nome completo
$nomeArray = explode(" ", $nomeCompleto);
$primeiroNome = $nomeArray[0];
$sobrenome = count($nomeArray) >= 2 ? $nomeArray[1] : "";

// Conectar ao banco de dados
$conexao = new mysqli('localhost', 'root', '', 'user');
if ($conexao->connect_error) {
    die('Erro de conexão: ' . $conexao->connect_error);
}

// Buscar informações do pedido de refúgio e do advogado associado (simulando os dados)
$pedidoRefugio = array(

);

// Simulação das informações do advogado associado
$advogadoAssociado = array(

);

// Buscar todas as postagens no banco de dados
$query = "SELECT * FROM postagens ORDER BY data_publicacao DESC";
$result = $conexao->query($query);
$postagens = $result->fetch_all(MYSQLI_ASSOC);


// Buscar casos associados ao nome do usuário
$queryCasos = "SELECT * FROM pedidos_refugio WHERE usuario = '$nomeCompleto' ORDER BY id DESC";
$resultCasos = $conexao->query($queryCasos);
$casos = $resultCasos->fetch_all(MYSQLI_ASSOC);


// Verificar se o formulário de criação de postagem foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $autor = $primeiroNome . ' ' . $sobrenome;
    $conteudo = $_POST['conteudo'];

    // Verificar se foram enviadas novas imagens
    if (isset($_FILES['imagens']['tmp_name'][0]) && !empty(array_filter($_FILES['imagens']['tmp_name']))) {
        $diretorioDestino = 'uploads/'; // Diretório onde as imagens serão armazenadas
        $imagens = array();

        foreach ($_FILES['imagens']['tmp_name'] as $key => $tmp_name) {
            $nomeArquivo = $_FILES['imagens']['name'][$key];
            $caminhoCompleto = $diretorioDestino . $nomeArquivo;

            if (move_uploaded_file($tmp_name, $caminhoCompleto)) {
                $imagens[] = $caminhoCompleto;
            }
        }

        // Transformar o array de caminhos em JSON para salvar no banco de dados
        $imagensJSON = json_encode($imagens);
    } else {
        $imagensJSON = null;
    }

    // Inserir a nova postagem no banco de dados
    $insertQuery = "INSERT INTO postagens (autor, conteudo, imagens) VALUES ('$autor', '$conteudo', '$imagensJSON')";

    if ($conexao->query($insertQuery) === TRUE) {

        // Redirecionar para a página de postagens após a criação da postagem
        header("Location: postagens.php");
        exit; // Certifique-se de usar exit() após o redirecionamento para interromper a execução do código
    } else {
        echo "Erro ao criar postagem: " . $conexao->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/stylepost.css" />
    <link rel="stylesheet" type="text/css" href="css/reesponsivepost.css" />
    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <title>Postagens</title>

    <!-- FavIcon -->
    <link rel="apple-touch-icon" sizes="60x60" href="./Imagens/FavIcon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./Imagens/FavIcon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Imagens/FavIcon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="./Imagens/FavIcon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#00a300">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <nav id="menu">
        <img src="./Imagens/Logo.png" class="logo-nav" alt="LogoNovas Raízes">
    </nav>

    <h2 class="info">Principais Informações</h2>
    <button onclick="window.location.href='tradutor.html';"
        style="border-radius: 10px; width: 14%; top: 25%; height: 5%; position:absolute; border: none; font-family: Inter; color: #FFF; font-weight: 700; text-shadow: -2px 6px 40px rgba(0, 0, 0, 0.50); font-size: 16px; background: linear-gradient(147deg, #F6D365 -16.81%, #F3CA67 26.28%, #B77B5D 96.72%); left: 04%; cursor: pointer;">
        Ir para o Tradutor
    </button>

    <button onclick="window.location.href='login.php';"
        style="border-radius: 10px; width: 14%; top: 32%; height: 5%; position:absolute; border: none; font-family: Inter; color: #FFF; font-weight: 700; text-shadow: -2px 6px 40px rgba(0, 0, 0, 0.50); font-size: 16px; background: linear-gradient(147deg, #F6D365 -16.81%, #F3CA67 26.28%, #B77B5D 96.72%); left: 04%; cursor: pointer;">
        Logout
    </button>

    <h2 class="info-g">Informações Gerais</h2>
    <div class="linha"></div>
    <div class="linha-2"></div>

    <div class="postagem">
        <h1 class="user">Olá, <span style="font-weight: 700; font-size:28px; color: #fff;">
                <?php echo $primeiroNome . " " . $sobrenome; ?>!
            </span></h1>

        <!-- Formulário de criação de postagem -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
            enctype="multipart/form-data">
            <textarea name="conteudo" id="conteudo" rows="4" cols="50" required placeholder="texto"
                style="resize: none;  position: relative; width: 88%; color: #9D9D9D; font-family: Inter; font-size: 20px; font-style: normal; font-weight: 600; line-height: normal; background: none; top: 14px; border: none; height: 53px; left: 59px"></textarea><br><br>
            <label for="imagens" class="file-input-label">
                <img src="./Imagens/Icon - Imag.png" alt="Escolher Imagem"
                    style="position: absolute; left: 1rem; top: 4.5rem; cursor: pointer;">
            </label>
            <input type="file" name="imagens[]" id="imagens" accept="image/jpeg, image/png, image/gif" multiple
                style="display: none;">
            <input class="btn-post" type="submit" value="Publicar">

            <script>
                document.getElementById("imagens").addEventListener("change", function (event) {
                    const fileInput = event.target;
                    const fileCount = fileInput.files.length;

                    const fileCountElement = document.getElementById("selected-file-count");
                    if (fileCount === 1) {
                        fileCountElement.textContent = "1 arquivo selecionado";
                    } else {
                        fileCountElement.textContent = fileCount + " arquivos selecionados";
                    }
                });
            </script>
            <p id="selected-file-count" class="file-count"
                style="max-width: 600px; position: absolute; left: 3rem; top: 4.8rem;"></p>

        </form>
    </div>

    <!-- Exibir postagens existentes -->
    <div class="postagens">
        <?php
        if (!empty($postagens)) {
            foreach ($postagens as $postagem) {
                echo "<div>";
                echo "<span style='font-family: Inter, sans-serif; font-size: 18px; font-weight: 500;'>" . $postagem['autor'] . "</span>";
                echo "<p style='max-width: 100%; word-wrap: break-word; top:18%, position: relative; font-family: Inter, sans-serif; font-size: 16px; font-weight: 200;'>" . $postagem['conteudo'] . "</p>";
                echo "<p>Data da Publicação: " . date("d/m/Y H:i", strtotime($postagem['data_publicacao'])) . "</p>";

                // Exibir imagens, se houver
                if (!empty($postagem['imagens'])) {
                    $imagens = json_decode($postagem['imagens'], true);
                    foreach ($imagens as $imagem) {
                        echo "<img src='$imagem' alt='Imagem da postagem' width='300' style='margin-top: 10px; border-radius: 8px;'><br>";
                    }
                }

                // Adicionar o link de edição
                echo "<a href='editar_postagem.php?postagem_id=" . $postagem['id'] . "'>Editar</a>";
                // Adicionar o link de exclusão
                echo "<a href='excluir_postagem.php?id=" . $postagem['id'] . "' style='margin-left: 10px; color: red;'>Excluir</a>";
                echo "</div>";
                echo "<hr style='border-color: #323232;;'>";
            }
        } else {
            echo "<p style='color: #2D2D2D; font-family: Inter, sans-serif; text-align: center; font-size: 18px; font-weight: bold; margin-top: 40%;'>não há nada por aqui</p>";
        }

        ?>
    </div>

    <!-- Verificar se o usuário possui casos não concluídos -->
    <?php
    $casosNaoConcluidos = false;
    foreach ($casos as $caso) {
        if (!$caso['concluido']) {
            $casosNaoConcluidos = true;
            break;
        }
    }

    if (!$casosNaoConcluidos) {
        echo '<button class="botao-apresentacao" onclick="location.href=\'apresentacao_solicitacao.php\';">Solicitar Ajuda Jurídica</button>';
    }
    ?>

    <div class="status">
        <?php if (!empty($advogadoAssociado['nome'])): ?>
            <!-- Informações do pedido de refúgio -->
            <h1 class="user"><span style="font-weight: 700; font-size:16px; color: #FFFFFF;">
                    <?php echo $primeiroNome . " " . $sobrenome; ?>
                </span></h1>
            <h2>Pedido de Refúgio</h2>
            <p>Descrição:
                <?php echo $pedidoRefugio['descricao']; ?>
            </p>
            <p>Status:
                <?php echo $pedidoRefugio['status']; ?>
            </p>

            <!-- Exibir informações do advogado e atualizações -->
            <div class="informacoes-advogado">
                <h2>Informações do Advogado</h2>
                <?php if ($advogadoAssociado['podeAtualizar']): ?>
                    <!-- Aqui você pode adicionar conteúdo específico para o advogado -->
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Botão para redirecionar para a tela de apresentação e solicitação de ajuda -->
        <?php if (!empty($_SESSION['usuario']['advogado'])): ?>
            <!-- Adicione aqui o conteúdo específico para o advogado -->
        <?php else: ?>

        <?php endif; ?>
    <?php endif; ?>

    <script>
        // Verificar se o parâmetro success está presente na URL
        const urlParams = new URLSearchParams(window.location.search);
        const successParam = urlParams.get('success');

        // Exibir o pop-up apenas se o parâmetro success estiver definido e for igual a "true"
        if (successParam !== null && successParam === 'true') {
            const popup = document.createElement("div");
            popup.id = "popup";
            popup.className = "popup";
            const popupContent = document.createElement("div");
            popupContent.className = "popup-content";

            // Criar o botão de fechar com o estilo do cursor
            const closeBtn = document.createElement("button");
            closeBtn.id = "close-popup";
            closeBtn.className = "close-popup-btn";
            closeBtn.textContent = '×';
            closeBtn.style.cursor = 'pointer'; // Aplicar o estilo do cursor
            closeBtn.style.border = 'none'; // Remover a borda
            closeBtn.style.background = 'none'; // Remover a cor de fundo
            closeBtn.style.fontWeight = 'bold'; // Definir a fonte como bold
            closeBtn.style.fontSize = '20px'; // Aumentar o tamanho da fonte

            const popupText = document.createElement("p");
            popupText.textContent = "Solicitação enviada com sucesso!";
            popupContent.appendChild(closeBtn);
            popupContent.appendChild(popupText);
            popup.appendChild(popupContent);
            document.body.appendChild(popup);

            // Mostrar o pop-up
            popup.style.display = "block";

            // Fechar o pop-up quando o botão de fechar for clicado
            closeBtn.addEventListener("click", function () {
                popup.style.display = "none";
                document.body.removeChild(popup);
            });
        }
    </script>

    <!-- Exibir casos -->
    <?php
    foreach ($casos as $caso) {
        if (!$caso['concluido']) { // Verifica se o caso não está concluído
            echo "<div class='caso'>";
            echo "<h3>Caso #" . $caso['id'] . "</h3>";
            echo "<p>Descrição: " . $caso['descricao'] . "</p>";
            echo "<p>Advogado: " . $caso['advogado_nome'] . "</p>";

            if (!empty($caso['documento']) || !empty($caso['foto'])) {
                echo "<button class='expandir-btn' onclick='expandirCaso(" . $caso['id'] . ")'>Ver mais</button>";
            }

            echo "<div class='caso-detalhes' id='caso-" . $caso['id'] . "'>";

            if (!empty($caso['documento'])) {
                echo "<a href='" . $caso['documento'] . "' target='_blank'>Baixar Documento</a>";
            }

            if (!empty($caso['foto'])) {
                echo "<img src='" . $caso['foto'] . "' alt='Foto do Caso' style='width: 12%; height: auto;'>";

            }

            echo "</div>"; // fim da div caso-detalhes
            echo "</div>"; // fim da div caso
            echo "<hr>";
        }
    }
    ?>

    <script>
        function expandirCaso(id) {
            var casoDetalhes = document.getElementById('caso-' + id);
            if (casoDetalhes.style.display === 'none') {
                casoDetalhes.style.display = 'block';
            } else {
                casoDetalhes.style.display = 'none';
            }
        }
    </script>


</body>

</html>