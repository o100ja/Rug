<?php

namespace Rug\Message\Factory;

use Rug\Connector\Connector;

class DocumentFactory extends DatabaseFactory {

  protected $_id;

  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db);
    $this->_id = $id;
  }

  public function getID() {
    return $this->_id;
  }

  public function path($path = '') {
    if (empty($path)) {
      return parent::path($this->_id);
    }
    return parent::path($this->_id . '/' . $path);
  }

}
