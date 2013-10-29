<?php
namespace Rug\Message;

use Buzz\Message\Response;
use Rug\Message\Parser\AbstractParser;

class RugResponse extends Response {

  private $_action;

  public function __construct($action = null) {
    $this->_action = $action;
  }

  public function parse(AbstractParser $parser) {
    return $parser->handle($this, $this->_action);
  }

}
 