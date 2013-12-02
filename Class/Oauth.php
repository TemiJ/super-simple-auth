<?php
require_once '../Interfaces/IOAuthenticate.php';

/**
 * {@inheritdoc}
 */
class Oauthenticate implements IOAuthenticate {

  private $reqUrl;
  private $authUrl;
  private $accUrl;
  private $apiUrl;
  private $conskey;
  private $consSec;

  /**
   * Getter function for $reqUrl property.
   *
   * @return string
   */
  private function getReqUrl() {
    return $reqUrl;
  }

  /**
   * Setter function for $reqUrl property.
   *
   * @return string
   */
  private function setReqUrl($reqUrl) {
    $this->reqUrl = $reqUrl;
  }

  /**
   * Getter function for $authUrl property.
   *
   * @return string
   */
  private function getAuthUrl() {
    return $this->authUrl;
  }

  /**
   * Setter function for $authUrl property.
   *
   * @return string
   */
  private function setAuthUrl($authUrl) {
    $this->authUrl = $authUrl;
  }

  /**
   * Getter function for $accUrl property.
   *
   * @return string
   */
  private function getAccUrl() {
    return $this->accUrl;
  }

  /**
   * Setter function for $accUrl property.
   *
   * @return string
   */
  private function setAccUrl($accUrl) {
    $this->accUrl = $accUrl;
  }

  /**
   * Setter function for $apiUrl property.
   *
   * @return string
   */
  private function getApiUrl() {
    return $this->apiUrl;
  }

  /**
   * Setter function for $apiUrl property.
   *
   * @return string
   */
  private function setApiUrl($apiUrl) {
    $this->apiUrl = $apiUrl;
  }

  /**
   * Getter function for $consKey property.
   *
   * @return string
   */
  private function getConsKey() {
    return $this->consKey;
  }

  /**
   * Setter function for $consKey property.
   *
   * @return string
   */
  private function setConsKey($consKey) {
    $this->consKey = $consKey;
  }

  /**
   * Getter function for $consKey property.
   *
   * @return string
   */
  private function getConsSec($consSec) {
    return $this->consSec;
  }

  /**
   * Setter function for $consSec property.
   *
   * @return string
   */
  private function setConsSec($consSec) {
    $this->consSec = $consSec;
  }

  /**
   * Creates an instance of PHP OAuth class and authenticates.
   *
   * @param string $consKey
   *   Consumner key.
   * @param string $consSec
   *   Consumer secrets.
   * @param string $apiUrl
   *   The API URL.
   * @param string $accUrl
   *   Access URL.
   * @param string $authUrl
   *   Authorization URL.
   * @param string $reqUrl
   *   Request URL.
   * @param integer $debug
   *   Either 1 or 0 to enable debugging.
   */
  function __construct($consKey, $consSec, $apiUrl, $accUrl, $authUrl, $reqUrl, $debug) {

    $this->setConsKey($consKey);
    $this->setConsSec($consSec);
    $this->setApiUrl($apiUrl);
    $this->setAccUrl($accUrl);
    $this->setAuthUrl($authUrl);
    $this->setReqUrl($reqUrl);
    session_start();
    if (!isset($_GET['oauth_token']) && $_SESSION['state'] == 1)
      $_SESSION['state'] = 0;
    try {

      $oauth = new OAuth($this->consKey, $this->consSec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
      $this->enableDebug($oauth, $debug);
      $this->getToken($oauth);
      $this->setToken();
      $this->fetch($this->apiUrl);
    } catch (OAuthException $E) {
      print_r($E);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function enableDebug($oauth, $enable = 0) {
    if ($enable == 1) {
      $oauth->enableDebug();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getToken($oauth) {
    if (!isset($_GET['oauth_token']) && !$_SESSION['state']) {
      $requestTokenInfo = $oauth->getRequestToken($this->reqUrl);
      $_SESSION['secret'] = $requestTokenInfo['oauth_token_secret'];
      $_SESSION['state'] = 1;
      header('Location: ' . $this->authUrl . '?oauth_token=' . $requestTokenInfo['oauth_token']);
      exit;
    }
    else if ($_SESSION['state'] == 1) {
      $oauth->setToken($_GET['oauth_token'], $_SESSION['secret']);
      $accessTokenInfo = $oauth->getAccessToken($this->accUrl);
      $_SESSION['state'] = 2;
      $_SESSION['token'] = $accessTokenInfo['oauth_token'];
      $_SESSION['secret'] = $accessTokenInfo['oauth_token_secret'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setToken() {
    $this->oauth->setToken($_SESSION['token'], $_SESSION['secret']);
  }

  /**
   * {@inheritdoc}
   */
  public function fetch($apiUrl) {
    $this->oauth->fetch($apiUrl);
  }

// this should be a reuse of what is in the BasicAuth class
// maybe log the response in some log or does it show on return
// URL. Maybe sent up a real return URL first.
  public function httpStatus() {
    $json = json_decode($oauth->getLastResponse());
//print_r($json);
  }

}

$reqUrl = 'https://api.twitter.com/oauth/request_token';
$authUrl = 'https://api.twitter.com/oauth/authorize';
$accUrl = 'https://api.twitter.com/oauth/access_token';
$apiUrl = 'https://api.twitter.com/1';
$consKey = 'EcruQ4uNIyEm6nV3GEDCdw';
$consSec = '37PbtM6WBkSmw35d3lyX2Nuu00jd7fF2EkfHu4MS6gg';
$debug = 1;

new Oauthenticate($consKey, $consSec, $apiUrl, $accUrl, $authUrl, $reqUrl, $debug);

// set up some interfaces (use autoload??)
// format
// does factory pattern work with this