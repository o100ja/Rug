<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;
use Rug\Coder\CoderManager;
use Rug\Exception\RugException;

abstract class AbstractParser {

  /**
   * @var CoderManager
   */
  protected $_coder;

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder) {
    $this->_coder = $coder;
  }

  /********************************************************************************************************************/

  public function handle(Response $response, $method = null, $mime = null) {
    if (empty($method) || !method_exists($this, $method)) {
      return $this->_parse($response, $mime);
    }
    return $this->$method($response, $mime);
  }

  public function decode($data, $mime = CoderManager::MIME_JSON) {
    return $this->_coder->get($mime)->decode($data);
  }

  /********************************************************************************************************************/

  public function _parse(Response $response, $mime = null) {
    $data = $this->decode($response->getContent(), $mime);
    if (isset($data->error)) {
      throw new RugException($data->error, $data->reason);
    }
    return $data;
  }

  protected function _parseClearOK(Response $response) {
    $data = $this->_parse($response);
    unset($data->ok);
    return $data;
  }

  protected function _parseOK(Response $response) {
    $data = $this->_parse($response);
    return isset($data->ok) ? $data->ok : false;
  }

}
