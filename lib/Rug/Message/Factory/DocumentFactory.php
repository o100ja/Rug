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

  public function createURL($path = '', array $parameters = array()) {
    return $this->_url($this->_root($this->_db . '/' . $this->_id . '/' . $path), $parameters);
  }

}
