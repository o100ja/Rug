<?php

namespace Rug\Message\Factory;

use Buzz\Message\Factory\Factory;
use Buzz\Message\Factory\FactoryInterface;
use Buzz\Message\RequestInterface;
use Buzz\Util\Url;
use Rug\Connector\Connector;
use Rug\Message\RugRequest;
use Rug\Message\RugResponse;

abstract class AbstractFactory extends Factory implements FactoryInterface {

  protected $_host = 'localhost';
  protected $_port = '5984';
  protected $_path = '/';

  protected $_ssl = false;

  protected $_userAgent = 'Rug/0.1';

  /********************************************************************************************************************/

  private $_fInfo;

  /**
   * @return string
   */
  protected function _mime(\SplFileInfo $file) {
    if (empty($this->_fInfo)) {
      $this->_fInfo = new \finfo(FILEINFO_MIME_TYPE);
    }
    return $this->_fInfo->file($file->getRealPath());
  }

  /********************************************************************************************************************/

  public function __construct(Connector $connector) {
    $this->_host = $connector->getHost();
    $this->_port = $connector->getPort();
    $this->_path = $connector->getPath();
    $this->_ssl  = $connector->isSSL();
  }

  public function isSSL() {
    return $this->_ssl;
  }

  /********************************************************************************************************************/

  /**
   * @deprecated
   * @param $method
   * @param string $resource
   * @param null $host
   * @throws \RuntimeException
   */
  public function createFormRequest($method = RequestInterface::METHOD_POST, $resource = '/', $host = null) {
    throw new \RuntimeException('method not supported');
  }

  public function applyContent(RequestInterface $request, $content) {
    if (empty($content)) {
      return $this;
    }
    if (is_array($content)) {
      $content = json_encode((object)$content);
    } else if (is_object($content)) {
      $content = json_encode($content);
    }
    $request->setContent($content);
    return $this;
  }

  abstract public function path($path = '');

  private function _root($path = '') {
    return ($this->isSSL() ? 'https' : 'http') . '://' . $this->_host . ':' . $this->_port . $this->_path . $path;
  }

  private function _url($url, array $parameters = array()) {
    if (empty($parameters)) {
      return new Url($url);
    }
    return new Url($url . '?' . http_build_query($parameters));
  }

  /**
   * @param null $path
   * @param array $parameters
   * @return Url
   */
  public function createURL($path = '', array $parameters = array()) {
    return $this->_url($this->_root($this->path($path)), $parameters);
  }

  public function createCDBRequest(
    $method, $path = '', array $params = array(), $content = null, $headers = array(),
    $mime = 'application/json', array &$options = array()
  ) {
    $request = new RugRequest($method);

    $headers['User-Agent'] = $this->_userAgent;

    $location = $this->createURL($path, $params);

    if ($content instanceof \SplFileInfo) {
      $options[CURLOPT_PUT]        = true;
      $options[CURLOPT_INFILE]     = fopen($content->getRealPath(), 'r');
      $options[CURLOPT_INFILESIZE] = $content->getSize();
      if (empty($mime)) {
        $mime = $this->_mime($content);
      }
    } else {
      $this->applyContent($request, $content);
    }
    $headers['Content-Type'] = $mime;

    $location->applyToRequest($request);

    $request->addHeaders($headers);
    return $request;
  }

  public function createCDBResponse($parser = null) {
    return new RugResponse($parser);
  }

}
