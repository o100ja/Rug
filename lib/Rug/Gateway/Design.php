<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
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

  public function view() {

  }

  public function save($data) {
    $parameters = array();
    return $this->_invoke('_head', self::METHOD_PUT, '', $parameters, $this->_validator()->design($data));
  }

  public function info() {
    return $this->_invoke('_info', self::METHOD_GET, '_info');
  }
}
 