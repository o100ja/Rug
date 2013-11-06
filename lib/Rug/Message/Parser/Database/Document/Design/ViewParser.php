<?php

namespace Rug\Message\Parser\Database\Document\Design;

use Buzz\Message\Response;
use Rug\Record\ViewVector;

class ViewParser extends AbstractDesignParser {

  public function _get(Response $response) {
    $data = $this->_parse($response);
    return new ViewVector($data->rows, $this->getDB(), $this->getID(), $this->getName());
  }

}
