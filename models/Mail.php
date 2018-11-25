<?php
namespace Models;
use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerExcpetion;
use Exception as Exception;
use Models\Client as Client;

class Mail
{   
    private $mail;
    
    public function __construct(){
        $this->mail = new PHPMailer(true);   
    }
        
    public function send($email, Client $client, $fecha, $ticketCodeList){
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email is not a valid email format");
            } 
            $clientName = $client->getName()." ".$client->getLastname();
            $htmlMessage = 'Estimado '.$clientName.'<br> Gracias por su compra el día '.$fecha.', a continuación encotrará enlaces a cada ticket de su compra relaizada: <br><br>';
            $i = 0;

            foreach ($ticketCodeList as $ticket) {
                $htmlMessage .= '<a href="'.$ticket.'">Ticket Nº'.$i.'<a><br>';
                $i++;
            }

            //Server settings
            //$mail->SMTPDebug = 2;                               // Verbose errors
            $this->mail->isSMTP();                                      // Set mailer to use SMTP
            $this->mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
            $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
            $this->mail->Username = 'gotoevent.utn';                    // SMTP username
            $this->mail->Password = 'PhoeniX85';                        // SMTP password
            $this->mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $this->mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $this->mail->setFrom('gotoevent.utn@example.com', 'GoToEvent Mailer'); // Add a recipient
            $this->mail->addAddress($email, $clientName);                            // Name is optional

            //Content
            $this->mail->isHTML(true);                                  // Set email format to HTML
            $this->mail->Subject = 'Gracias por su compra en GoToEvent';
            $this->mail->Body    = $htmlMessage;

            $this->mail->send();

            //echo 'Message has been sent';
        } catch (PHPMailerExcpetion $ex) {
            throw $ex;
        } catch (Exception $ex){

        }
    }
}