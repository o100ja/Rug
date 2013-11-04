<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;
use Rug\Exception\RugException;

class DesignParser extends AbstractDocumentParser {

  public function info(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function _get(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

}
