<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;

class DatabaseParser extends AbstractParser {

  public function parse(Response $response) {
    return $this->_parse($response);
  }

  public function parse_all_docs(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function parse_ensure_full_commit(Response $response) {
    return $this->_parseClearOK($response);
  }

  public function parse_compact(Response $response) {
    return $this->_parse($response);
  }

  public function parse_security(Response $response) {
    return $this->_parse($response);
  }

  public function parse_missing_revs(Response $response) {
    return $this->_parse($response)->missing_revs->keys;
  }

  public function parse_head(Response $response) {
    return $this->_parseClearOK($response);
  }

  public function parse_body(Response $response) {
    return $this->_parseClearOK($response);
  }

}
