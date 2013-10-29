<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;
use Rug\Exception\RugException;

class DocumentParser extends AbstractParser {

  public function parse(Response $response) {
    return $this->_parse($response);
  }

  public function parse_head(Response $response) {
    if ($response->isSuccessful()) {
      return $this->_decode($response->getHeader('Etag'));
    }
    throw new RugException('not_found', 'The specified document or revision cannot be found or has been deleted');
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
