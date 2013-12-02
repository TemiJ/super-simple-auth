<?php
require_once '../Interfaces/IBasic.php';

/**
 * {@inheritdoc}
 */
class BasicAuth implements IBasic {

  /**
   * Username to authenticate to service.
   *
   * @var string
   */
  private $username;

  /**
   * Password to authenticate to service.
   *
   * @var string
   */
  private $password;

  /**
   * Endpoint URL of API.
   *
   * @var type
   */
  private $url;

  /**
   * Getter function for $username property.
   *
   * @return string
   */
  private function getUsername() {
    return $this->username;
  }

  /**
   * Setter function for $username property.
   */
  private function setUsername($username) {
    $this->username = $username;
  }

  /**
   * Getter function for $password property.
   *
   * @return string
   */
  private function getPassword() {
    return $this->password;
  }

  /**
   * Setter function for $password property.
   */
  private function setPassword($password) {
    $this->username = $password;
  }

  /**
   * Getter function for $url property.
   *
   * @return string
   */
  private function getUrl() {
    return $this->url;
  }

  /**
   * Setter function for $url property.
   */
  private function setUrl($url) {
    $this->url = $url;
  }

  
  function __construct($url, $userName, $password) {

    $this->setUrl($url);
    $this->setUsername($userName);
    $this->setPassword($password);
    $httpStatus = $this->initCurl($this->url, $this->username, $this->password);

    print $this->httpStatus($httpStatus);
  }

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
  public function initCurl($url, $username = NULL, $password = NULL) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_PROXY, 'http://10.23.12.110:8080');
    curl_exec($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return $httpStatus;
  }

  /**
   * Returns the outcome of the reuqest based on the CURL request.
   *
   * @param string $httpStatus
   *   Status of HTTP request.
   */
  public function httpStatus($httpStatus) {
    //$httpStatus = $this->initCurl($url);
    $statusNumber = substr($httpStatus, 0, 1);

    switch ($statusNumber) {
      case 0:
        $response = $httpStatus . ' Host not found';
        break;
      case 2:
        $response = $httpStatus .' Successfully Authenticated';
        break;
      case 3:
        $response = $httpStatus . ' Moved';
        break;
      case 4:
        $response = $httpStatus . ' Authentication Failed';
        break;
      case 5:
        $response = $httpStatus .' Server Error';
        break;
      default:
    }
    return $response;
  }

}


$url = 'http://content.guardianapis.com/?q=football';
$username = 'temij';
$password = 'taekwondo';
new BasicAuth($url, $username, $password);
