<?php
session_start();

// Verifique se o usuário está autenticado e obtenha o nome completo
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$nomeCompleto = $_SESSION['usuario']['nome'];
$nomeArray = explode(" ", $nomeCompleto);
$primeiroNome = $nomeArray[0]; // Defina o primeiro nome aqui

// Conectar ao banco de dados
$conexao = new mysqli('localhost', 'root', '', 'user');
if ($conexao->connect_error) {
    die('Erro de conexão: ' . $conexao->connect_error);
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION['usuario']['nome'];
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];

    // Upload de foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['tmp_name'] !== "") {
        $diretorioDestino = 'uploads/';
        $nomeFoto = $_FILES['foto']['name'];
        $caminhoFoto = $diretorioDestino . $nomeFoto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoFoto);
        $foto = $caminhoFoto;
    }

    // Upload de documento
    $documento = null;
    if (isset($_FILES['documento']) && $_FILES['documento']['tmp_name'] !== "") {
        $diretorioDestino = 'uploads/';
        $nomeDocumento = $_FILES['documento']['name'];
        $caminhoDocumento = $diretorioDestino . $nomeDocumento;
        move_uploaded_file($_FILES['documento']['tmp_name'], $caminhoDocumento);
        $documento = $caminhoDocumento;
    }

    // Inserir a nova solicitação no banco de dados
    $insertQuery = "INSERT INTO solicitacoes (usuario, titulo, conteudo, foto, documento) VALUES ('$usuario', '$titulo', '$conteudo', '$foto', '$documento')";

    if ($conexao->query($insertQuery) === TRUE) {
        // Redirecionar para a página de postagens com o parâmetro success=true
        header("Location: postagens.php?success=true");
        exit();
    } else {
        echo "Erro ao enviar solicitação: " . $conexao->error;
    }


}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/solicitar.css" />
    <link rel="stylesheet" type="text/css" href="css/reesponsivepost.css" />
    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <title>Solicitação de Ajuda Jurídica</title>

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

    <h1 style="font-family: 'Inter'; font-style: normal; font-weight: 600; font-size: 28px; line-height: 29px;">
        Apresentação e Solicitação de Ajuda</h1>
    <p class="apre"
        style="font-family: 'Inter'; font-style: normal; font-weight: 600; font-size: 20px; line-height: 29px;">Olá,
        <?php echo $primeiroNome; ?>!<br>Aqui você pode solicitar ajuda de um advogado. Conte-nos sobre sua situação,
        adicione imagens que possam ajudar a entender o contexto e, se necessário, compartilhe documentos em formato PDF
        para fornecer detalhes adicionais.<br><br>Lembre-se de que um advogado analisará cuidadosamente a sua
        solicitação para oferecer a assistência necessária.
    </p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input class="form-titulo" type="text" id="titulo" name="titulo" required placeholder="Título da Solicitação:"
            style="font-family: 'Inter'; font-style: normal; font-weight: 600; font-size: 20px; line-height: 29px;"
            position: relative; top: 1rem;><br><br>

        <label class="txt-conteudo" for="conteudo">Conteúdo da Solicitação:</label><br>
        <textarea id="conteudo" name="conteudo" rows="4" cols="50" required></textarea><br><br>

        <label for="imagens" class="file-input-label">
            <img src="./Imagens/Icon - Imag.png" alt="Escolher Imagem"
                style="position: absolute; left: 4rem; top: 43rem; cursor: pointer;">
        </label>
        <input type="file" name="foto" id="imagens" accept="image/jpeg, image/png, image/gif" multiple
            style="display: none;">

        <label for="documento" class="file-input-label">
            <img src="./Imagens/icons8-documento-30.png" alt="Enviar Documento"
                style="position: absolute; left: 7rem; top: 43rem; cursor: pointer;">
        </label>
        <input type="file" name="documento" id="documento" accept=".pdf" style="display: none;">

        <input class="btn-sub" type="submit" name="enviar" value="Enviar Solicitação">

        <p id="selected-file-count" class="file-count"
            style="max-width: 600px; position: absolute; left: 4rem; top: 41rem; color:#FFFFFF;"></p>

        <script>
            const imagensInput = document.getElementById("imagens");
            const documentoInput = document.getElementById("documento");
            const fileCountElement = document.getElementById("selected-file-count");

            imagensInput.addEventListener("change", updateFileCount);
            documentoInput.addEventListener("change", updateFileCount);

            function updateFileCount() {
                const imagensCount = imagensInput.files.length;
                const documentoCount = documentoInput.files.length;

                if (imagensCount === 0 && documentoCount === 0) {
                    fileCountElement.textContent = "";
                } else if (imagensCount === 1 && documentoCount === 0) {
                    fileCountElement.textContent = "1 imagem selecionada";
                } else if (imagensCount > 0 && documentoCount === 0) {
                    fileCountElement.textContent = imagensCount + " imagens selecionadas";
                } else if (imagensCount === 0 && documentoCount === 1) {
                    fileCountElement.textContent = "1 documento selecionado";
                } else if (imagensCount === 0 && documentoCount > 0) {
                    fileCountElement.textContent = documentoCount + " documentos selecionados";
                } else {
                    fileCountElement.textContent = imagensCount + " imagens + " + documentoCount + " documentos selecionados";
                }
            }
        </script>


    </form>

    <?php
    // Verificar se o usuário é um advogado
    $usuario = $_SESSION['usuario']['nome'];
    $queryAdvogado = "SELECT * FROM advogados WHERE nome = '$usuario'";
    $resultAdvogado = $conexao->query($queryAdvogado);
    $isAdvogado = $resultAdvogado && $resultAdvogado->num_rows > 0;

    if ($isAdvogado) {

        if ($isAdvogado) {
            // Exibir as solicitações apenas para os advogados
            $querySolicitacoes = "SELECT * FROM solicitacoes ORDER BY data_solicitacao DESC";
            $resultSolicitacoes = $conexao->query($querySolicitacoes);

            if ($resultSolicitacoes->num_rows > 0) {
                echo "<h2>Solicitações Recebidas:</h2>";

                while ($row = $resultSolicitacoes->fetch_assoc()) {
                    echo "<div>";
                    echo "<strong>Título:</strong> " . $row['titulo'] . "<br>";
                    echo "<strong>Usuário:</strong> " . $row['usuario'] . "<br>";
                    echo "<strong>Data:</strong> " . $row['data_solicitacao'] . "<br>";
                    echo "<strong>Conteúdo:</strong><br>" . $row['conteudo'] . "<br><br>";
                    echo "</div>";
                }
            } else {
                echo "<h2>Nenhuma solicitação recebida ainda.</h2>";
            }
        }
    }
    ?>

</body>

</html>