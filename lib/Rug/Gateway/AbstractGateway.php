<?php
namespace Rug\Gateway;

use Buzz\Message\Response;
use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\AbstractFactory;
use Rug\Message\Parser\AbstractParser;
use Rug\Validator\RugValidator;

abstract class AbstractGateway {

  /********************************************************************************************************************/

  const METHOD_GET    = 'GET';
  const METHOD_HEAD   = 'HEAD';
  const METHOD_POST   = 'POST';
  const METHOD_PUT    = 'PUT';
  const METHOD_DELETE = 'DELETE';
  const METHOD_COPY   = 'COPY';

  /********************************************************************************************************************/

  /**
   * @var CoderManager
   */
  private $_coder;

  /**
   * @var Connector
   */
  private $_connector;

  /**
   * @var RugValidator
   */
  private $_validator;

  /**
   * @var AbstractParser
   */
  private $_parser;

  /**
   * @var AbstractFactory
   */
  private $_factory;

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder, Connector $connector) {
    $this->_coder     = $coder;
    $this->_connector = $connector;
    $this->_validator = new RugValidator();
  }

  /********************************************************************************************************************/

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
   * @return CoderManager
   */
  protected function _coder() {
    return $this->_coder;
  }

  /**
   * @return Connector
   */
  protected function _connector() {
    return $this->_connector;
  }

  /**
   * @return RugValidator
   */
  protected function _validator() {
    return $this->_validator;
  }

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

  /********************************************************************************************************************/

  /**
   * @param mixed $data
   * @param string $mime
   * @return mixed
   */
  protected function _encode($data, $mime = CoderManager::MIME_JSON) {
    return $this->_coder()->get($mime)->encode($data);
  }

  /**
   * @param Response $response
   * @param string $handler
   * @param string $mime
   * @return mixed
   */
  protected function _parse(Response $response, $handler, $mime = CoderManager::MIME_JSON) {
    return $this->_parser()->handle($response, $handler, $mime);
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
                           $content = null, $headers = array(), $mime = CoderManager::MIME_JSON
  ) {
    $response = $this->_send($method, $path, $params, $content, $headers, $mime, $handler);
    return $this->_parse($response, $handler, $mime);
  }

  /**
   * @param string $method
   * @param string $path
   * @param array $params
   * @param null|mixed $content
   * @param array $headers
   * @param string $mime
   * @param null|string $parser
   * @return Response
   */
  protected function _send(
    $method, $path = '', array $params = array(), $content = null, $headers = array(),
    $mime = CoderManager::MIME_JSON,
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

  /********************************************************************************************************************/

}
 