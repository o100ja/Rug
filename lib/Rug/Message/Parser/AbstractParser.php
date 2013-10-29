<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;
use Rug\Exception\RugException;

abstract class AbstractParser {

  public function handle(Response $response, $action) {
    $method = "parse$action";
    if (method_exists($this, $method)) {
      return $this->$method($response);
    }
    return $this->_parse($response);
  }

  protected function _decode($content) {
    return json_decode($content);
  }

  public function _parse(Response $response) {
    $data = $this->_decode($response->getContent());
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
