<?php

namespace Rug\Message\Factory\Database\Document\Design;

use Rug\Connector\Connector;

class ListFactory extends AbstractSectionFactory {

  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id, $name, '_list');
  }

}
