<?php

namespace Rug\Message\Factory;

use Rug\Connector\Connector;

class DesignFactory extends DocumentFactory {

  private $_name;

  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db, '_design/' . $id);
    $this->_name = $id;
  }


}
