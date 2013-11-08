<?php

namespace Rug\Message\Parser\Database\Document\Design;

use Rug\Coder\CoderManager;
use Rug\Message\Parser\Database\Document\AbstractDocumentParser;

class AbstractSectionParser extends AbstractDocumentParser {

  /**
   * @var string
   */
  private $_name;

  public function __construct(CoderManager $coder, $db, $id, $name) {
    parent::__construct($coder, $db, $id);
    $this->_name = $name;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->_name;
  }

}

