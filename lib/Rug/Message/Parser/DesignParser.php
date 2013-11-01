<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;

class DesignParser extends AbstractParser {

  public function info(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function save(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function _get(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

}
