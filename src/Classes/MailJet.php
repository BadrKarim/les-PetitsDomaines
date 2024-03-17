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

    public function sendResetPassword($to_email, $lastname, $firstname, $content)
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
                    'TemplateID' => 5774322,
                    'TemplateLanguage' => false,
                    'Variables' => [
                        "firstname" => $firstname,
                        "lastname" => $lastname,
                        "content" => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    public function sendContact($to_email, $lastname, $firstname)
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
                    'TemplateID' => 5746886,
                    'TemplateLanguage' => false,
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    public function sendSuccessStripe($to_email, $lastname, $firstname)
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
                    'TemplateID' => 5746962,
                    'TemplateLanguage' => false,
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    public function sendCancelStripe($to_email, $lastname, $firstname)
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
                    'TemplateID' => 5775202,
                    'TemplateLanguage' => false,
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}