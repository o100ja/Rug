<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
use Rug\Gateway\Design\View;
use Rug\Message\Factory\DesignFactory;
use Rug\Message\Parser\DesignParser;

class Design extends AbstractDocument {
  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db, $id);
    $this->_setFactory(new DesignFactory($connector, $db, $id));
    $this->_setParser(new DesignParser());
  }

  /********************************************************************************************************************/

  public function view($name) {
    return new View($this->_connector, $this->getDB(), $this->getID(), $name);
  }

  /********************************************************************************************************************/

  public function save($data) {
    $parameters = array();
    return $this->_invoke(__FUNCTION__, self::METHOD_PUT, '', $parameters, $this->_validator()->design($data));
  }

  public function info() {
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, '_info');
  }

  /********************************************************************************************************************/

}
 