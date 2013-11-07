<?php

namespace Rug\Message\Parser\Database\Document\Design;

use Rug\Message\Parser\Database\Document\AbstractDocumentParser;

class AbstractSectionParser extends AbstractDocumentParser {

  /**
   * @var string
   */
  private $_name;

  /**
   * @param string $db
   * @param string $id
   * @param string $name
   */
  public function __construct($db, $id, $name) {
    parent::__construct($db, $id);
    $this->_name = $name;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->_name;
  }

}

