<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;

class ServerParser extends AbstractParser {

  /********************************************************************************************************************/

  public function createDB(Response $response) {
    return $this->_parseOK($response);
  }

  public function deleteDB(Response $response) {
    return $this->_parseOK($response);
  }

  public function gatherDB(Response $response) {
    return $this->_parse($response);
  }

  /********************************************************************************************************************/

  public function uuids(Response $response) {
    return $this->_parse($response)->uuids;
  }

  /********************************************************************************************************************/

  public function restart(Response $response) {
    return $this->_parseOK($response);
  }

  public function version(Response $response) {
    return $this->_parse($response)->version;
  }

  /********************************************************************************************************************/

  public function tasks(Response $response) {
    return $this->_parse($response);
  }

  public function updates(Response $response) {
    return $this->_parse($response);
  }

  public function log(Response $response) {
    return $response->getContent();
  }

  public function stats(Response $response) {
    return $this->_parse($response)->couchdb;
  }

  public function session(Response $response) {
    return $this->_parseClearOK($response);
  }

  /********************************************************************************************************************/

  public function replicate(Response $response) {
    return $this->_parseOK($response);
  }

  /********************************************************************************************************************/

}
