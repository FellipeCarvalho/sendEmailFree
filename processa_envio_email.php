<?php

    //print_r($_POST); //para fins de debugg

    //importa os arquivos da biblioteca de envio de email
    require "./libs/PHPMailer/Exception.php";
    require "./libs/PHPMailer/PHPMailer.php";
    require "./libs/PHPMailer/SMTP.php";
    require "./libs/PHPMailer/POP3.php";
    require "./libs/PHPMailer/OAuth.php";

    //importa namespaces requeridos para usar na biblioteca importada acima
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;


    //criação da classe mensagem
    class  Mensagem {

        //variaveis privadas a essa classe
        private $para     = null;
        private $assunto  = null;
        private $mensagem = null;
        public $status = array( 'cod_status'=> null, 'descricao_status'=>'');

        //função get
        public function __get($atributo) {
            return $this->$atributo;
        }


        //função set
        public function __set($atributo,$valor){
            $this->$atributo = $valor;
        }

        //verifica  se os campos foram preenchidos
        public function mensagemValida() {
            if ( empty($this->para) || empty($this->assunto) ||  empty($this->mensagem)  )
                return true;
        }
        
    }
    //fim classe

    //estanciamos classe em uma varivel, que chamamos agora de objeto por conter elementos da classe mensagem
    $mensagem = new  Mensagem();

    //a partir do objeto, fazemos a atribuição com valores do front-end
    $mensagem->__set('para',  $_POST['para']);
    $mensagem->__set('assunto',  $_POST['assunto']);
    $mensagem->__set('mensagem',  $_POST['mensagem']);

    //teste para debugg
    if ($mensagem->mensagemValida()){
        //echo 'Mensagem não é válida!';
    }

    //***********************************************************************************************************//
    //Código para envio de mensagem usando a lib PHPmailer - https://github.com/PHPMailer/PHPMailer/tree/master  //
    //***********************************************************************************************************//

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->CharSet = 'utf-8';
        
    try {
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'br456.hostgator.com.br';
        $mail->SMTPAuth = true;
        $mail->Username = 'email-teste-nao-responder@fellipecarvalho.com';
        $mail->Password = '123456789';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        
        
        $mail->setFrom('email-teste-nao-responder@fellipecarvalho.com');
        $mail->addAddress($mensagem->__get('para')); #($email);


        $mail->isHTML(true);
        $mail->FromName = 'Email teste Não responder - Desconsiderar';
        $mail->Subject = $mensagem->__get('assunto') .  rand(0, 1500);
        $mail->Body = $mensagem->__get('mensagem')  ;
        $mail->AltBody = 'Email teste Não responder - Desconsiderar';

        $mail->send();
        $mensagem->status['cod_status'] = 1;
        $mensagem->status['descricao_status'] ="Email enviado com sucesso";
        
        

    } catch (Exception $e) {
        $mensagem->status['cod_status'] =2;
        $mensagem->status['descricao_status'] ="Não foi possível enviar o email. Por favor, tente novamente. <br> Detalhes do erro: " . $mail->ErrorInfo;

    } 

    //fim do codigo copiado de envio de email smtp da lib PHPmailer
    //abaixo finalizo o código em php e abro uma entrada de código em html

?>

<html>

        <head>
            <meta charset="utf-8" />
            <title>Email Free</title>

            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        </head>
        
        <body>

            <div class="container">

                <div class="py-3 text-center">
                    <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
                    <h2>Envio deEmail Free</h2>
                    <p class="lead">Seu app de envio de e-mails particular!</p>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <?php if ( $mensagem->status['cod_status'] == 1 ) { ?>
                                 <div class="container">
                                     <h1 class="display-4 text-sucess">Sucesso</h1>
                                     <p class=""><?php echo $mensagem->status['descricao_status'] ?> </p> 
                                     <a class="btn btn-success btn-lg mt-5 text-white" href="index.php"> Voltar</a> 
                                 </div> 
                        <?php } else{ ?>
                            <div class="container">
                                     <h1 class="display-4 text-danger">Ops! Email não enviado</h1>
                                     <p class=""><?php echo $mensagem->status['descricao_status'] ?> </p> 
                                     <a class="btn btn-danger btn-lg mt-5 text-white" href="index.php"> Voltar</a>   
                                 </div> 
                            <?php } ?>
                    </div>
                    <!--fim coluna-->

                </div>
                <!--fim row-->

            </div>
            <!--fim conteiner-->

        </body>
    
</html>



