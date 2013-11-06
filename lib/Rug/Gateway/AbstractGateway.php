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

  public function __construct(Connector $connector) {
    $this->_connector = $connector;
    $this->_validator = new RugValidator();
  }

  /**
   * @param AbstractFactory $factory
   * @return $this
   */
  protected function _setFactory(AbstractFactory $factory) {
    $this->_factory = $factory;
    return $this;
  }

  /**
   * @param AbstractParser $parser
   * @return $this
   */
  protected function _setParser(AbstractParser $parser) {
    $this->_parser = $parser;
    return $this;
  }

  /**
   * @param RugValidator $validator
   * @return $this
   */
  protected function _setValidator(RugValidator $validator) {
    $this->_validator = $validator;
    return $this;
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

  protected function _encode($data) {
    return $this->_factory()->encode($data);
  }

  /**
   * @param string $handler
   * @param string $method
   * @param string $path
   * @param array $params
   * @param null $content
   * @param array $headers
   * @param string $mime
   * @return mixed
   */
  protected function _call($handler = '',
                           $method = self::METHOD_GET, $path = '', array $params = array(),
                           $content = null, $headers = array(), $mime = 'application/json'
  ) {
    $response = $this->_send($method, $path, $params, $content, $headers, $mime, $handler);
    return $this->_parser()->handle($response, $handler);
  }

  protected function _send(
    $method, $path = '', array $params = array(), $content = null, $headers = array(),
    $mime = 'application/json',
    $parser = null
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
 