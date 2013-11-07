<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\Design\ListFactory;
use Rug\Message\Parser\Database\Document\Design\ListParser;

class ListGateway extends ViewGateway {

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id, $name);
    $this->_setFactory(new ListFactory($connector, $db, $id, $name));
    $this->_setParser(new ListParser($db, $id, $name));

  }

  /********************************************************************************************************************/


}
