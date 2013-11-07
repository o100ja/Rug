<?php

namespace Rug\Message\Factory\Database\Document;

use Rug\Connector\Connector;

class DesignFactory extends DocumentFactory {

  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db, $id);
  }

  protected function _path() {
    return '_design/' . parent::_path();
  }

}
