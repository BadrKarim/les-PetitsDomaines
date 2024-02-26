<?php

namespace App\Classes;

use Mailjet\Client;
use Mailjet\Resources;

class MailJet 
{
    private $api_key = 'edda01f3ecc72a866d1f8141846941c9';
    private $api_key_secret = '55c1145130f460a311693ba702d42aae';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "karim-badr@hotmail.fr",
                        'Name' => "Les Petits Domaines"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 5732292,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() && dd($response->getData());
    }
}