<?php

/**
 * Allows authentication to service via Basic authentication.
 */
interface IBasic {

  /**
   * Runs Curl request based on user provided parameters.
   *
   * @param string $url
   *   Endpoint URL of API.
   * @param type $username
   *   Username to authenticate request with.
   * @param type $password
   *   Password that goes with specified username.
   */
  public function initCurl($url, $username = NULL, $password = NULL);

  /**
   * Returns the outcome of the reuqest based on the CURL request.
   *
   * @param string $httpStatus
   *   Status of HTTP request.
   */
  public function httpStatus($httpStatus);
}
