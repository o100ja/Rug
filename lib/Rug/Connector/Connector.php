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

  /********************************************************************************************************************/

  public function __construct(array $options = array(), ClientInterface $client = null) {
    $this->_options = (object)array_merge(array(
      'host'    => 'localhost',
      'port'    => '5984',
      'path'    => '/',

      'ssl'     => false,
      'proxy'   => false,
      'timeout' => 5,

      'db'      => null,
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
  }

  /********************************************************************************************************************/

  public function getHost() {
    return $this->_options->host;
  }

  public function getPort() {
    return $this->_options->port;
  }

  public function getPath() {
    return $this->_options->path;
  }

  public function getTimeout() {
    return $this->_options->timeout;
  }

  public function isSSL() {
    return !empty($this->_options->ssl);
  }

  public function getProxy() {
    return $this->_options->proxy;
  }

  public function hasProxy() {
    return !empty($this->_options->proxy);
  }

  /********************************************************************************************************************/

  public function send(Request $request, Response $response, array $options = array()) {
    $this->_client->send($request, $response, $options);
    return $response;
  }

}
