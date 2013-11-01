<?php

namespace Rug\Message\Parser;

use Buzz\Message\Response;

class ConfigParser extends AbstractParser {

  /********************************************************************************************************************/

  public function dump(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

  public function section(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

  public function get(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  public function set(Response $response) {
    $data = $this->_parse($response);
    return $data;
  }

  /********************************************************************************************************************/

}
