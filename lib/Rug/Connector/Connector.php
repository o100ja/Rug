<?php
namespace Rug\Connector;

use Buzz\Client\ClientInterface;
use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Buzz\Util\CookieJar;

class Connector {

  const METHOD_GET    = 'GET';
  const METHOD_HEAD   = 'HEAD';
  const METHOD_POST   = 'POST';
  const METHOD_PUT    = 'PUT';
  const METHOD_DELETE = 'DELETE';
  const METHOD_COPY   = 'COPY';

  /********************************************************************************************************************/

  /**
   * @var object
   */
  private $_options;

  /**
   * @var ClientInterface
   */
  private $_client;

  /**
   * @var CookieJar
   */
  private $_cookies;

  /**
   * @var object
   */
  private $_auth;

  /********************************************************************************************************************/

  /**
   * @param array $options
   * @param ClientInterface $client
   */
  public function __construct(array $options = array(), ClientInterface $client = null) {
    $this->_options = (object)array_merge(array(
      'host'    => 'localhost',
      'port'    => '5984',
      'path'    => '/',

      'ssl'     => false,
      'proxy'   => false,
      'timeout' => 5,

      'name' => null,

      'user' => null,
      'pass' => null,
    ), $options);

    $this->_cookies = new CookieJar();

    if (empty($client)) {
      $client = new Curl();
    }
    $this->_client = $client;
    $this->_client->setTimeout($this->getTimeout());
    if ($this->isSSL()) {
//      $opts[CURLOPT_SSL_VERIFYPEER] = true;
//      $opts[CURLOPT_SSL_VERIFYHOST] = true;
//      $opts[CURLOPT_CAINFO] = $this->sslCertPath;
    }
    if ($this->hasProxy()) {
      $this->_client->setProxy($this->getProxy());
    }

    if ($this->hasAuth()) {
      $this->_auth = (object)array(
        'user' => $this->_options->user,
        'pass' => $this->_options->pass,
      );
    }
  }

  /********************************************************************************************************************/

  /**
   * @return string
   */
  public function getHost() {
    return $this->_options->host;
  }

  /**
   * @return integer
   */
  public function getPort() {
    return $this->_options->port;
  }

  /**
   * @return string
   */
  public function getPath() {
    return $this->_options->path;
  }

  /**
   * @return integer
   */
  public function getTimeout() {
    return $this->_options->timeout;
  }

  /**
   * @return bool
   */
  public function isSSL() {
    return !empty($this->_options->ssl);
  }

  /**
   * @return string
   */
  public function getProxy() {
    return $this->_options->proxy;
  }

  /**
   * @return bool
   */
  public function hasProxy() {
    return !empty($this->_options->proxy);
  }

  /**
   * @return bool
   */
  public function hasAuth() {
    return !empty($this->_options->user);
  }

  /**
   * @return object
   */
  public function getAuth() {
    return $this->_auth;
  }

  /**
   * @param Request $request
   */
  public function setupAuth(Request $request) {
    if ($auth = $this->getAuth()) {
      $request->addHeader('Authorization: Basic ' . base64_encode($auth->user . ':' . $auth->pass));
    }
  }

  /********************************************************************************************************************/

  /**
   * @param Request  $request
   * @param Response $response
   * @param array    $options
   *
*@return Response
   */
  public function send(Request $request, Response $response, array $options = array()) {
    $this->setupAuth($request);
    $this->_client->send($request, $response, $options);
    return $response;
  }

  /********************************************************************************************************************/

}
