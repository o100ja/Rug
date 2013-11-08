<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\Design\ListFactory;
use Rug\Message\Parser\Database\Document\Design\ListParser;

class ListGateway extends ViewGateway {

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder, Connector $connector, $db, $id, $name) {
    parent::__construct($coder, $connector, $db, $id, $name);
    $this->_setFactory(new ListFactory($coder, $connector, $db, $id, $name));
    $this->_setParser(new ListParser($coder, $db, $id, $name));
  }

  /********************************************************************************************************************/


}
