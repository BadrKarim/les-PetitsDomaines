<?php

namespace App\Classes;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailJet 
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function sendRegister($to_email, $lastname, $firstname)
    {
        $api_key = $this->params->get('MAILJET_KEY_API_PUBLIC');
        $api_key_secret = $this->params->get('MAILJET_KEY_API_SECRET');
        $adminEmail = $this->params->get('ADMIN_MAIL');

        $mj = new Client($api_key, $api_key_secret, true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $adminEmail,
                        'Name' => "Les Petits Domaines"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $lastname.' '.$firstname
                        ]
                    ],
                    'TemplateID' => 5746751,
                    'TemplateLanguage' => false,
                    'Variables' => [
                        'lastName' => $lastname,
                        'firstname'=> $firstname,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}