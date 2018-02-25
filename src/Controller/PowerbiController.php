<?php

namespace Drupal\powerbi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\powerbi\Service\OAuthServiceInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class PowerbiController extends ControllerBase {

    protected $oauthService;

    public function __construct(OAuthServiceInterface $oauthService) {
        $this->oauthService = $oauthService;
    }

    public function powerbi(Request $request){
        $code = $request->get('code');
        $state = $request->get('state');

        if ($code) {
            if (!$state || $_SESSION['state'] != $state) {
                header('Location: ' . $_SERVER['PHP_SELF']);
                die();
            }
        }

        $http = new Client();
        $request = $http->request('GET', 'https://api.powerbi.com/v1.0/myorg/datasets/7d17e64f-d071-4ab6-a007-46d03c93da38/tables/',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $_SESSION['access_token']
                ]
            ]);

        $status = ' michael';

        return array(
            '#theme' => 'powerbi',
            '#powerbi_variables' => $request->getBody(),
        );
    }
    public static function create(ContainerInterface $container){
        return new static(
            $container->get('powerbi.oauth')
        );
    }
    public function receiveAuthorizationCode(Request $request){

        $_SESSION['state'] = hash('sha256', microtime(TRUE) . rand() . $_SERVER['REMOTE_ADDR']);
        unset($_SESSION['access_token']);

        $url = $this->oauthService->fetchAuthorizationCode($_SESSION['state']);

        return $this->redirect($url);
    }
}