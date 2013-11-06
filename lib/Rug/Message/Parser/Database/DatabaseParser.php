<?php

namespace Rug\Message\Parser\Database;

use Buzz\Message\Response;

class DatabaseParser extends AbstractDatabaseParser {

  /********************************************************************************************************************/

  public function status(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function missingRevs(Response $response) {
    return $this->_parse($response)->missing_revs->keys;
  }

  /********************************************************************************************************************/

  public function changes(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function compact(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function commit(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

  public function getSecurity(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function setSecurity(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

  public function getRevsLimit(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function setRevsLimit(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

  public function find(Response $response) {
    return $this->_parseClearOK($response);
  }

  public function herd(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function bulk(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function save(Response $response) {
    $data = $this->_parseClearOK($response);
    return $data;
  }

  public function kill(Response $response) {
    $data = $this->_parseClearOK($response);
    return $data;
  }

  public function bury(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

}
