<?php

namespace Rug\Gateway\Database\Document;

use Rug\Connector\Connector;
use Rug\Gateway\Database\Document\Design\EditGateway;
use Rug\Gateway\Database\Document\Design\ListGateway;
use Rug\Gateway\Database\Document\Design\ShowGateway;
use Rug\Gateway\Database\Document\Design\ViewGateway;
use Rug\Message\Factory\DesignFactory;
use Rug\Message\Parser\Database\Document\DesignParser;

class DesignGateway extends AbstractDocumentGateway {

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db, $id);
    $this->_setFactory(new DesignFactory($connector, $db, $id));
    $this->_setParser(new DesignParser($db, $id));
  }

  /********************************************************************************************************************/

  public function view($name) {
    return new ViewGateway($this->_connector, $this->getDB(), $this->getID(), $name);
  }

  public function edit($name) {
    return new EditGateway($this->_connector, $this->getDB(), $this->getID(), $name);
  }

  public function show($name) {
    return new ShowGateway($this->_connector, $this->getDB(), $this->getID(), $name);
  }

  public function page($name) {
    return new ListGateway($this->_connector, $this->getDB(), $this->getID(), $name);
  }

  /********************************************************************************************************************/

  public
  function save($data) {
    $parameters = array();
    return $this->_call(__FUNCTION__, self::METHOD_PUT, '', $parameters, $this->_validator()->design($data));
  }

  public
  function info() {
    return $this->_call(__FUNCTION__, self::METHOD_GET, '_info');
  }

  /********************************************************************************************************************/

}
 