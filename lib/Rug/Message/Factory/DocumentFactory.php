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

  protected function _path($path = '') {
    if (empty($path)) {
      parent::_path($this->_id);
    }
    return parent::_path($this->_id) . '/' . $path;
  }

}
