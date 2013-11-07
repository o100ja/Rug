<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Connector\Connector;
use Rug\Gateway\Database\Document\AbstractDocumentGateway;

abstract class AbstractDesignGateway extends AbstractDocumentGateway {


  /**
   * @var string
   */
  private $_name;

  /**
   * @param Connector $connector
   * @param string $db
   * @param string $id
   * @param string $name
   */
  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id);
    $this->_name = $name;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->_name;
  }

}
 