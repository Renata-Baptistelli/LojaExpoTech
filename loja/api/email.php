<?php

// Importação das classes da biblioteca PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Função para envio de e-mails usando SMTP (PHPMailer)
 *
 * @param string $to      Email do destinatário
 * @param string $subject Assunto do e-mail
 * @param string $message Corpo da mensagem em HTML
 * @return bool           Verdadeiro se o email for enviado com sucesso
 */
function send_email($to, $subject, $message) {
    // Inclusão das classes necessárias do PHPMailer
    require_once __DIR__ . '/PHPMailer/src/Exception.php';
    require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/src/SMTP.php';

    // Inclusão das credenciais (NUNCA subir este arquivo ao Git)
    require_once __DIR__ . '/secrets.php';

    // Cria uma nova instância de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->CharSet = "utf-8";                         // Define o charset como UTF-8
        $mail->isSMTP();                                  // Define o uso de SMTP
        $mail->Host       = 'smtp.sapo.pt';               // Servidor de e-mail SMTP
        $mail->SMTPAuth   = true;                         // Habilita autenticação SMTP
        $mail->Username   = $EMAIL_SAPO;                  // Nome de utilizador (vem do secrets.php)
        $mail->Password   = $EMAIL_PASS;                  // Palavra-passe (vem do secrets.php)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Tipo de encriptação
        $mail->Port       = 465;                          // Porta do servidor

        // Define remetente e destinatário
        $mail->setFrom($EMAIL_SAPO, "App Loja 24198");    // Remetente
        $mail->addAddress($to);                           // Destinatário

        // Conteúdo do e-mail
        $mail->isHTML(true);                              // Formato HTML
        $mail->Subject = $subject;                        // Assunto
        $mail->Body    = $message;                        // Mensagem

        // Tenta enviar o e-mail
        $mail->send();
        return true;

    } catch (Exception $e) {
        // Se houver erro, retorna false (pode-se ativar logs se necessário)
        // echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
        return false;
    }
}
?>
