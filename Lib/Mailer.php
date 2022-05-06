<?php

namespace VisitMarche\Theme\Lib;

class Mailer
{
    public static function sendError(string $subject, string $message): void
    {
        $to = $_ENV['WEBMASTER_EMAIL'];
        wp_mail("jf@marche.be", $subject, $message);
    }
}
