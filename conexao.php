<?php
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user";
    $usertable = "cadastros";
    $yourfield = "email";
    $email=$_POST["email"];
    $senha=$_POST["senha"];
    
    $mysqli = new mysqli($hostname,$username,$password,$dbname);

    if(!$mysqli) {
        echo "Error: Falha ao conectar-se com o banco de dados MYSQL." . PHP_EOL;
        echo "Debugging errno". mysqli_connect_errno() . PHP_EOL;
        echo "Debugging errno". mysqli_connect_errno() . PHP_EOL;
          exit;
    } else {
        echo "Conexão realizada com sucesso<br>";
    }
    $query = "SELECT * FROM login WHERE  email='$email' AND senha='$senha'";

    $result = mysqli_query($mysqli,$query);
    if ($result){
        while($row = mysqli_fetch_array ($result)){
            $email = $row["email"];
            echo "Name: ".$email."<br/>";
            $_SESSION["usuario"]=$email;
            $_SESSION["error"]=NULL;
            echo "<script>window.location.replace('./postagens.php')</script>";
        }

        $total = mysqli_fetch_array($result);
        if ($total == 0) {
            echo "<BR><br><B>".$query;
            $_SESSION["error"]=true;
            echo "<script>window.location.replace('./Erro.html')</script>";
        }
    }
    
?>
