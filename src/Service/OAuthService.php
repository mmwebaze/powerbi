<?php

namespace Drupal\powerbi\Service;

use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use kamermans\OAuth2\GrantType\ClientCredentials;
use kamermans\OAuth2\OAuth2Middleware;

class OAuthService implements OAuthServiceInterface {
    protected $baseUrl;
    protected $grantType;
    protected $clientId;
    protected $clientSecret;
    protected $tokenEndpoint;

    public function __construct(ConfigFactory $config_factory) {
        $config = $config_factory->getEditable('powerbi.settings');

        $this->baseUrl = $config->get('powerbi.baseurl');
        $this->grantType = $config->get('powerbi.grant_type');
        $this->clientId = $config->get('powerbi.client_id');
        $this->clientSecret = $config->get('powerbi.client_secret');
        $this->tokenEndpoint = $config->get('powerbi.token_endpoint');
    }
    public function getResouce($resourceEndpoint) {
        try {
            $http = new Client();
            $request = $http->request('POST', $resourceEndpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getAccessToken()
                    ]
                ]);
            $response = $request->getBody();
            return $response;
        } catch (ClientException $e) {
            echo $e->getMessage() . ' *********';
        }
    }
    public function fetchAuthorizationCode($state){

        $url = 'see secret' . http_build_query([
                'response_type' => 'code',
                'client_id' => $this->clientId,
                'redirect_uri' => 'http://learning.dd:8083/powerbi/powerbi',
                'response_mode' => 'query',
                'state' => $state
            ]);
print($url);
        return $url;
    }
    private function getAccessToken() {
        try {
            $reauth_config = [
                "client_id" => $this->clientId,
                "client_secret" => $this->clientSecret,
                'grant_type' => $this->grantType,
                'scope' => 'datasets'
            ];
            $reauth_client = new Client([
                // URL for access_token request
                'base_uri' => $this->tokenEndpoint,
            ]);
            $grant_type = new ClientCredentials($reauth_client, $reauth_config);
            $oauth = new OAuth2Middleware($grant_type);
            return $oauth->getAccessToken();

        } catch (ClientException $e) {
            echo $e->getMessage() . ' *********';
        }
    }
}