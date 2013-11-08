<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Gateway\Database\Document\AbstractDocumentGateway;

abstract class AbstractSectionGateway extends AbstractDocumentGateway {

  /**
   * @var string
   */
  private $_name;

  public function __construct(CoderManager $coder, Connector $connector, $db, $id, $name) {
    parent::__construct($coder, $connector, $db, $id);
    $this->_name = $name;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->_name;
  }

}
 