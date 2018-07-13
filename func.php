<?php

require 'vendor/autoload.php';

function sendMailArka($sujet="",$message="",$from="",$to=""){

    $host = "xxx.fr";
    $sourceIp = "xx.xx.xx.xx";
    $localDomain = "xxx.fr";
    $privatekey = file_get_contents("/home/mail/pk.pem");

    try{
        $h2t = new Html2Text\Html2Text($message);
        $textmessage = $h2t->getText();

        $transfert = Swift_SmtpTransport::newInstance();
        $transfert->setHost($host);
        $transfert->setEncryption("tls");
        $transfert->setSourceIp($sourceIp);
        $transfert->setLocalDomain($localDomain);

        $signer = new Swift_Signers_DKIMSigner($privatekey, $localDomain, "default");

        $messaget = Swift_Message::newInstance();
        $messaget->attachSigner($signer);
        $messaget->setTo($to);
        $messaget->setSubject($sujet);
        $messaget->setBody($textmessage, "text/plain");
        $messaget->addPart($message, "text/html");
        $messaget->setFrom($from);

        $mailer = Swift_Mailer::newInstance($transfert);
        $mailer->send($messaget);
        return true;
    }catch(Exception $e){
        return false;
    }
}
