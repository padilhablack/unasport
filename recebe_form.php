<?php
?> 
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
</head>
<body> 
    
<?php
//dados do banco
$hostname = '186.202.152.51';
$user = 'unasp43';
$pass = 'un@sp43';
$dbase = 'unasp43';
$con = null;


//varias que recebem os dados do formulÃ¡rio
$nome = $_POST['nome'] ? $_POST['nome'] : "";
$sobrenome = $_POST['sobrenome'] ? $_POST['sobrenome'] : "";
$telefone = (isset($_POST['telefone'])) ? $_POST['telefone'] : "";
$email = (isset($_POST['email'])) ? $_POST['email'] : "";
$oficina = (isset($_POST['oficina'])) ? $_POST['oficina'] : "";
$ra = (isset($_POST['ra'])) ? $_POST['ra'] : "";
$semestre = (isset($_POST['semestre'])) ? $_POST['semestre'] : "";
$curso = (isset($_POST['curso'])) ? $_POST['curso'] : "";

//Abre a conexÃ£o com o banco 
$con = mysql_connect($hostname, $user, $pass); // conexÃ£o
mysql_select_db($dbase); // seleciona o banco
//seta todos em utf8
mysql_query("SET character_set_connection=utf8");
mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_results=utf8");

//Obtem os dados da tabela oficina
$pegaOficina = mysql_query("SELECT * FROM secone WHERE oficina = '$oficina'");
$resultOficina = mysql_num_rows($pegaOficina);
//Obtem os dados da tabela email
$pegaEmail = mysql_query("SELECT * FROM secone WHERE email = '$email'");
// pega um resultado dos dados do email
$resultEmail = mysql_num_rows($pegaEmail);
//variável que recebe o post_ra caso ele exista
$resultRA = null;

//caso exista o post_ra $resultRa recebe
if (!empty($ra)) {
    $pegaRa = mysql_query("SELECT * FROM secone WHERE ra = '$ra'");
    $resultRA = mysql_num_rows($pegaRa);
}


if($resultEmail > 0){?>
     <script>
        alert("Email já cadastrado nessa oficina");
        location.href="http://www.unasp-ec.com/secone/";
    </script>
<?php
}else if ($resultOficina == 55) { ?>
    <script>
        alert("Já foram preenchidas o numero de vagas para essa oficina");
        location.href="http://www.unasp-ec.com/secone/";
    </script>

<?php
}

//verifica se o RA existe no sistema
else if ($resultRA > 0) {
    ?>
       <script>
        alert("RA já cadastrado nessa oficina");
        location.href="http://www.unasp-ec.com/secone/";
    </script>
    <?php
} else {
    
//executa a query no bancoI 
   $sql = "INSERT INTO secone (id,nome,sobrenome,telefone,email,oficina,ra,curso,semestre) VALUES (NULL,'" . $nome . "','" . $sobrenome . "','" . $telefone . "','" . $email . "','" . $oficina . "','" . $ra . "','" . $curso . "','" . $semestre . "')";
    $db = mysql_query($sql);
    //executa a query no banco
    if ($db) {
        $from = 'noreply@unasp-ec.com';
        $to = "wanderley.gazeta@unasp.edu.br";
        $headers = "From: SECONE <noreply@unasp-ec.com>\r\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
        $headers .= sprintf('Return-Path: %s%s', $from, PHP_EOL);
        $headers .= sprintf('Reply-To: %s%s', $from, PHP_EOL);
        $headers .= sprintf('X-Priority: %d%s', 3, PHP_EOL);
        $headers .= sprintf('X-Mailer: PHP/%s%s', phpversion(), PHP_EOL);
        $headers .= sprintf('Disposition-Notification-To: %s%s', $from, PHP_EOL);
        $subject = utf8_decode('Cadastro Secone ' . $assunto);
        $mensagem = utf8_decode('Olá¡ ' . $nome . '! Seu Cadastro foi efetuado com sucesso');
        $assunto = 'II SECONE';
        $mensagem = "O cadastro de <b>".$nome."</b> foi realizado com sucesso!</br>";
		$mensagem .= "SEUS DADOS:</br>";
		$mensagem .= "NOME: ".$nome."</br>";
		$mensagem .= "TELEFONE:".$telefone."</br>";
		$mensagem .= "EMAIL: ".$email."</br>";
		$mensagem .= "OFICINA: ".$oficina."</br>";
		$mensagem .= "RA: ".$ra."</br>";
		$mensagem .= "CURSO: ".$curso."</br>";
		$mensagem .= "SEMESTRE: ".$semestre."</br>";
		$mensagem .= "Para visualizar todas as inscrições acesse <a href='http://www.unasp-ec.com/secone/inscritos.php'>AQUI</a></br>";

        if (mail($to, $subject, $mensagem, $headers)) {
		?>
            <script>
				alert("Cadastro realizado com sucesso");
				location.href="http://www.unasp-ec.com/secone/";
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