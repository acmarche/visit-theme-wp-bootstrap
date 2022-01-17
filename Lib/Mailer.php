<?php

namespace VisitMarche\Theme\Lib;

use AcMarche\Pivot\Utils\Env;

class Mailer
{
    public static function sendError(string $subject, string $message): void
    {
        Env::loadEnv();
        $to = $_ENV['WEBMASTER_EMAIL'];
        wp_mail($to, $subject, $message);
    }

    public static function sendInscription(string $nom, string $prenom, string $email, bool $rgpd): void
    {
        Env::loadEnv();
        $to = $_ENV['ADL_EMAIL'];
        $subject = 'Inscription newsletter';
        $message = $nom.' '.$prenom.' souhaite s\'inscrire avec le mail: '.$email;

        wp_mail($to, $subject, $message);
    }
}
