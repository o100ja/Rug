<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;

class ServerParser extends AbstractParser {

  public function parse(Response $response) {
    return $this->_parse($response)->version;
  }

  public function parse_active_tasks(Response $response) {
    return $this->_parse($response);
  }

  public function parse_uuids(Response $response) {
    return $this->_parse($response)->uuids;
  }

  public function parse_stats(Response $response) {
    return $this->_parse($response)->couchdb;
  }

  public function parse_replicate(Response $response) {
    return $this->_parseClearOK($response);
  }

  public function parse_session(Response $response) {
    return $this->_parseClearOK($response);
  }

  public function parse_restart(Response $response) {
    return $this->_parseOK($response);
  }

  public function parse_log(Response $response) {
    return $response->getContent();
  }

  public function parse_create_db(Response $response) {
    return $this->_parseOK($response);
  }

  public function parse_delete_db(Response $response) {
    return $this->_parseOK($response);
  }

}
