<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;
use Rug\Exception\RugException;

class AbstractDocumentParser extends AbstractParser {

  /********************************************************************************************************************/

  public function rev(Response $response) {
    if ($response->isSuccessful()) {
      return $this->_decode($response->getHeader('Etag'));
    }
    throw new RugException('not_found', 'The specified document or revision cannot be found or has been deleted');
  }

  public function revs(Response $response) {
    $data = $this->_parse($response)->_revisions;
    $revs = array();
    foreach ($data->ids as $id) {
      $revs[] = $data->start-- . '-' . $id;
    }
    return $revs;
  }

  /********************************************************************************************************************/

  public function data(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function save(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function kill(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function copy(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

  public function attach(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function detach(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

}
