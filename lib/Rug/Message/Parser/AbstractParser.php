<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;
use Rug\Exception\RugException;
use Rug\Gateway\AbstractGateway;

abstract class AbstractParser {

  const MIME_JSON = AbstractGateway::MIME_JSON;

  public function handle(Response $response, $method, $mime = null) {
    if (method_exists($this, $method)) {
      return $this->$method($response, $mime);
    }
    return $this->_parse($response, $mime);
  }

  protected function _decode($content, $mime = AbstractGateway::MIME_JSON) {
    // TODO: create a decoder wrapper
    return json_decode($content);
  }

  public function _parse(Response $response, $mime = null) {
    $data = $this->_decode($response->getContent(), $mime);
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
