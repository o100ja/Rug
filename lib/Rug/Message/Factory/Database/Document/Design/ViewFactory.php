<?php

namespace Rug\Message\Factory\Database\Document\Design;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;

class ViewFactory extends AbstractSectionFactory {

  public function __construct(CoderManager $coder, Connector $connector, $db, $id, $name) {
    parent::__construct($coder, $connector, $db, $id, $name, '_view');
  }

}
