<?php

namespace Rug\Message\Factory;

class ServerFactory extends AbstractFactory {

  public function createURL($path = '', array $parameters = array()) {
    return $this->_url($this->_root($path), $parameters);
  }

}
