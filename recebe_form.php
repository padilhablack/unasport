
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body> 

        <?php
//dados do banco
        $hostname = '127.0.0.1';
        $user = 'root';
        $pass = '';
        $dbase = 'formularios';
        $con = null;

//varias que recebem os dados do formulÃ¡rio
        $nome = $_POST['nome_full'] ? $_POST['nome_full'] : "";
        $RG = $_POST['rg'] ? $_POST['rg'] : "";
        $nascimento = $_POST['d_nasc'] ? $_POST['d_nasc'] : "";
        $email = $_POST['email'] ? $_POST['email'] : "";
        $endereco = $_POST['endereco'] ? $_POST['endereco'] : "";
        $complemento = $_POST['complemento'] ? $_POST['complemento'] : "";
        $cidade = $_POST['cidade'] ? $_POST['cidade'] : "";
        $cep = $_POST['cep'] ? $_POST['cep'] : "";
        $participante = $_POST['participante'] ? $_POST['participante'] : "";
        $modalidade = $_POST['modalidade'] ? $_POST['modalidade'] : "";
        $reserva = $_POST['reserva_onibus'] ? $_POST['reserva_onibus'] : "";


        $reservaMensagem = "";
        if (isset($reserva)) {
            $reservaMensagem = "SIM";
        } else {
            $reservaMensagem = "NÃO.";
        }

//Abre a conexÃ£o com o banco 
        $con = mysql_connect($hostname, $user, $pass); // conexÃ£o
        mysql_select_db($dbase); // seleciona o banco
//seta todos em utf8
        mysql_query("SET character_set_connection=utf8");
        mysql_query("SET character_set_client=utf8");
        mysql_query("SET character_set_results=utf8");


 // faz verficação se email e rg já estão cadastrados
        
        $pegaRG = mysql_query("SELECT * FROM unasport WHERE rg = '$RG'");
        $resultRG = mysql_num_rows($pegaRG);

        $pegaEmail = mysql_query("SELECT * FROM unasport WHERE email = '$email'");
        $resultEmail = mysql_num_rows($pegaEmail);


        if ($resultEmail > 0) {
            ?>
            <script>
                alert("Email já cadastrado nessa oficina");
                location.href = "http://l o calhost/unasport/";
            </script>
        <?php } else if ($resultRG > 0) {
            ?>
            <script>
                alert("RG já cadastrado no evento");
                location.href = "http://l o calhost/unasport/";
            </script>
            <?php
        } else {

//executa a query no banco
            
            $sql = "INSERT INTO `formularios`.`unasport` (`id`, `nome`, `rg`, `nascimento`, `email`, `endereco`, `complemento`, `cidade`, `cep`, `participante`, `modalidade`, `reserva`) VALUES ("
                    . "NULL, '" . $nome . "', '" . $RG . "', '" . $nascimento . "', '" . $email . "', '" . $endereco . "', '" . $complemento . "', '" . $cidade . "', '" . $cep . "', '" . $participante . "', '" . $modalidade . "', '" . $reserva . "');";

            $db = mysql_query($sql);
            
            // Se tudo ocorrer bem no banco prepara os dados para enviar por mensagem
            
            if ($db) {
                $from = 'noreply@unasp-ec.com';
                $to = "padilhamidia@hotmail.com";
                $headers = "From: UNASPORT <noreply@unasp-ec.com>\r\n";
                $headers .= "MIME-Version: 1.0\n";
                $headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
                $headers .= sprintf('Return-Path: %s%s', $from, PHP_EOL);
                $headers .= sprintf('Reply-To: %s%s', $from, PHP_EOL);
                $headers .= sprintf('X-Priority: %d%s', 3, PHP_EOL);
                $headers .= sprintf('X-Mailer: PHP/%s%s', phpversion(), PHP_EOL);
                $headers .= sprintf('Disposition-Notification-To: %s%s', $from, PHP_EOL);
                $subject = utf8_decode('Confirmção de Inscrição UNASPORT ' . $assunto);
                $mensagem = utf8_decode('Olá¡ ' . $nome . '! Seu Cadastro foi efetuado com sucesso');
                $assunto = 'II SECONE';
                $mensagem = "O cadastro de <b>" . $nome . "</b> foi realizado com sucesso!</br>";
                $mensagem .= "SEUS DADOS:</br>";
                $mensagem .= "NOME: " . $nome . "</br>";
                $mensagem .= "DATA DE NASCIMENTO:" . $nascimento . "</br>";
                $mensagem .= "RG:" . $RG . "</br>";
                $mensagem .= "EMAIL: " . $email . "</br>";
                $mensagem .= "ENDEREÇO: " . $endereco . "</br>";
                $mensagem .= "COMPLEMENTO: " . $complemento . "</br>";
                $mensagem .= "CIDDADE: " . $cidade . "</br>";
                $mensagem .= "CEP: " . $cep . "</br>";
                $mensagem .= "MODALIDADE: " . $modalidade . "</br>";
                $mensagem .= "RESERVA PARA ÔNIBUS: " . $reservaMensagem . "</br>";

                if (mail($to, $subject, $mensagem, $headers)) {
                    ?>
                    <script>
                        alert("Cadastro realizado com sucesso");
                        location.href = "http://w w w.unasp-ec.com/secone/";
                    </script>
            <?php
        } else {
            echo 'EMAIL FALHOU';
        }
        //Encerra a conexÃ£o
        mysql_close();
    } else {
        echo"ERRO NO CADASTRO" . mysql_errno();
    }
}
?>
    </body>
</html>