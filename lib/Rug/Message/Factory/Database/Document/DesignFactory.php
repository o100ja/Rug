<?php

namespace Rug\Message\Factory\Database\Document;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;

class DesignFactory extends DocumentFactory {

  public function __construct(CoderManager $coder, Connector $connector, $db, $id) {
    parent::__construct($coder, $connector, $db, $id);
  }

  protected function _path() {
    return '_design/' . parent::_path();
  }

}
