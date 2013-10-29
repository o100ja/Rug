<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;

class DocumentParser extends AbstractParser {

  public function parse(Response $response) {
    return $this->_parse($response);
  }

  public function parse_revs(Response $response) {
    $data = $this->_parse($response)->_revisions;
    $revs = array();
    foreach ($data->ids as $id) {
      $revs[] = $data->start-- . '-' . $id;
    }
    return $revs;
  }

  public function parse_data(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

}
