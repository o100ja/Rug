<?php

namespace Rug\Message\Parser\Database\Document;

use Buzz\Message\Response;

class DesignParser extends AbstractDocumentParser {

  public function info(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

}
