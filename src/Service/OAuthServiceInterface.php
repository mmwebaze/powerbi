<?php
namespace Drupal\powerbi\Service;

/**
 * Provides an interface defining Power BI authentication service.
 */
interface OAuthServiceInterface {
    public function getResouce($resourceEndpoint);
    public function fetchAuthorizationCode($state);
}