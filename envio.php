<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "user";
$usertable = "cadastros";
$yourfield = "email";
$email=$_POST["email"];

$mysqli = new mysqli($hostname,$username,$password,$dbname);

if(!$mysqli) {
    echo "Error: Falha ao conectar-se com o banco de dados MYSQL." . PHP_EOL;
    echo "Debugging errno". mysqli_connect_errno() . PHP_EOL;
    echo "Debugging errno". mysqli_connect_errno() . PHP_EOL;
      exit;
} else {
    echo "Conexão realizada com sucesso<br>";
}
$query = "SELECT * FROM cadastros WHERE email='$email'";

$result = mysqli_query($mysqli, $query);

if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $email = $row["email"];
        $senha = $row["senha"];
        $nomeCompleto = $row["nome"]; // Obter o nome completo do usuário do banco de dados

        // Extrair o primeiro nome e sobrenome do nome completo
        $nomeArray = explode(" ", $nomeCompleto);
        $primeiroNome = $nomeArray[0];
        $sobrenome = count($nomeArray) >= 2 ? $nomeArray[1] : "";

        echo "Name: " . $email . "<br/>";
        $_SESSION["usuario"] = $email;
        $_SESSION["senha"] = $senha;
        $_SESSION["error"] = NULL;
            
        }
    }
//Chamando metodo
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

//'enviar' = nome do butão de envio
//These must be at the top of your script, not inside a function
if (isset($_POST['enviar'])) {

    //Chamando a classe Mailer
    $mail = new PHPMailer(true);

    try {
        // TRUE/Verdadeiro = Etá Funcionado
        //Configuarar o Servidor
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Mostra as informações do Servidor enquato executa o Envio
        $mail->isSMTP(); //Função SMTP
        $mail->Host = 'smtp.gmail.com'; //Definindo servidor de envio do email 
        $mail->SMTPAuth = true; //Servidor requer autentificação

        /////////////////////////////////////////////////////////////////////////////////////
        $mail->Username = 'novasraizes.br@gmail.com'; //Seu email
        $mail->Password = 'unxcfrqcstbwuelv'; //Senha email(senha de app - Gmail)

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        ///////////////////////////////////////////////////////////////////////////////////
        //Recipients
        $mail->setFrom('novasraizes.br@gmail.com', 'Novas Raizes'); //Qual email estou enviando(coloca o mesmo de Username)

        $mail->addAddress($_POST['email'], 'Usuário'); //Destinatário (pode colocar outros. basta copiar a linha 31 de novo) no ultimo campo defina o nome do usuario

        ///////////////////////////////////////////////////////////////////////////////////
        $mail->addReplyTo('novasraizes.br@gmail.com', 'Novas Raizes'); //Email de resposta

        $mail->isHTML(true); //Define se o email vai ser no formato de HTML
        $mail->Subject = 'Pedido de redefinição de senha'; // Informa o Assunto/Titulo do email

        $mail->CharSet = 'UTF-8'; // Definir a codificação para UTF-8


        
        $body = $conteudo = '
        <h1>Recuperação de Senha</h1>
        <p>Olá, ' . $primeiroNome . ' ' . $sobrenome . '</p>
        <p>Você solicitou a recuperação de sua senha.</p>
        <p>Sua senha é: <strong>' . $_SESSION['senha'] . '</strong></p>

        <hr>
        <p>Se você não solicitou essa recuperação de senha, ignore este e-mail.</p>
        <p>Atenciosamente,</p>
        <p>A equipe de suporte</p>
    ';

        $mail->Body = $body; // Mensagem que vai no email
        $result = $mail->Send();
        header("Location: email.php");



        // FALSE = erro    
    } catch (Exception $e) {
        echo "Erro ao enviar o email: {$mail->ErrorInfo}"; //monstra a mensaem e depois o tipo de erro armazenado no servidor
    }

    //Tudo que está acima só vai ser enviado se as informações forem executadas via POST
} else {
    echo "Erro ao enviar email, acesso não foi via Formulário";
    //Caso alguém tente enviar email sem acessar o formulário
}