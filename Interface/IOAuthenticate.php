<?php
/**
 * Autheniticates a user using Oauth.
 */
interface IOAuthenticate {
  /*
   * Allow debugging of request.
   *
   * @param object $oauth
   *   Instance of PHP OAuth class.
   * @param integer $enable
   *   Defaults to zero, can be set to 1 to allow debugging.
   */

  public function enableDebug($oauth, $enable = 0);

  /**
   * Gets OAuth request token
   */
  public function getToken($oauth);

  /**
   * Wrapper function for setToken() method of PHP Oauth class.
   */
  public function setToken();

  /**
   * Wrapper function for fetch() method of PHP Oauth class.
   * @param type $apiUrl
   */
  public function fetch($apiUrl);
}