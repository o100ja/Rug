<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
use Rug\Message\Factory\AbstractFactory;
use Rug\Message\Parser\AbstractParser;
use Rug\Validator\RugValidator;

abstract class AbstractGateway {

  const METHOD_GET    = 'GET';
  const METHOD_HEAD   = 'HEAD';
  const METHOD_POST   = 'POST';
  const METHOD_PUT    = 'PUT';
  const METHOD_DELETE = 'DELETE';
  const METHOD_COPY   = 'COPY';

  /********************************************************************************************************************/

  /**
   * @var AbstractParser
   */
  private $_parser;

  /**
   * @var AbstractFactory
   */
  private $_factory;

  /**
   * @var RugValidator
   */
  private $_validator;

  /********************************************************************************************************************/

  public function __construct(Connector $connector, AbstractFactory $factory, AbstractParser $parser) {
    $this->_connector = $connector;
    $this->_factory   = $factory;
    $this->_parser    = $parser;
    $this->_validator = new RugValidator();
  }

  /********************************************************************************************************************/

  /**
   * @return AbstractFactory
   */
  protected function _factory() {
    return $this->_factory;
  }

  /**
   * @return AbstractParser
   */
  protected function _parser() {
    return $this->_parser;
  }

  /**
   * @return RugValidator
   */
  protected function _validator() {
    return $this->_validator;
  }

  /********************************************************************************************************************/

  /**
   * @param $method
   * @param string $path
   * @param array $params
   * @param null $content
   * @param array $headers
   * @return mixed
   */
  protected function _call(
    $method, $path = '', array $params = array(), $content = null, $headers = array(),
    $mime = 'application/json'
  ) {
    return $this->_invoke($path, $method, $path, $params, $content, $headers, $mime);
  }

  protected function _invoke(
    $parser, $method, $path = '', array $params = array(), $content = null, $headers = array(), $mime = 'application/json'
  ) {
    return $this->_parser()->handle(
      $this->_send($method, $path, $params, $content, $headers, $mime), $parser
    );
  }

  protected function _send(
    $method, $path = '', array $params = array(), $content = null, $headers = array(),
    $mime = 'application/json', $parser = null
  ) {
    $options  = array();
    $request  = $this->_factory->createCDBRequest(
      $method, $path, $params, $content, $headers, $mime, $options
    );
    $response = $this->_factory->createCDBResponse(
      $parser
    );
    return $this->_connector->send($request, $response, $options);
  }

}
 