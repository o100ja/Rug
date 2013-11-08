<?php

namespace Rug\Gateway\Database\Document;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Gateway\Database\Document\Design\EditGateway;
use Rug\Gateway\Database\Document\Design\ListGateway;
use Rug\Gateway\Database\Document\Design\ShowGateway;
use Rug\Gateway\Database\Document\Design\ViewGateway;
use Rug\Message\Factory\Database\Document\DesignFactory;
use Rug\Message\Parser\Database\Document\DesignParser;

class DesignGateway extends AbstractDocumentGateway {

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder, Connector $connector, $db, $id) {
    parent::__construct($coder, $connector, $db, $id);
    $this->_setFactory(new DesignFactory($coder, $connector, $db, $id));
    $this->_setParser(new DesignParser($coder, $db, $id));
  }

  /********************************************************************************************************************/

  /**
   * @param string $name
   * @return ViewGateway
   */
  public function view($name) {
    return new ViewGateway($this->_coder(), $this->_connector(), $this->getDB(), $this->getID(), $name);
  }

  /**
   * @param string $name
   * @return EditGateway
   */
  public function edit($name) {
    return new EditGateway($this->_coder(), $this->_connector(), $this->getDB(), $this->getID(), $name);
  }

  /**
   * @param string $name
   * @return ShowGateway
   */
  public function show($name) {
    return new ShowGateway($this->_coder(), $this->_connector(), $this->getDB(), $this->getID(), $name);
  }

  /**
   * @param string $name
   * @return ListGateway
   */
  public function page($name) {
    return new ListGateway($this->_coder(), $this->_connector(), $this->getDB(), $this->getID(), $name);
  }

  /********************************************************************************************************************/

  /**
   * @param array|object $data
   * @return mixed
   */
  public function save($data) {
    $parameters = array();
    return $this->_call(__FUNCTION__, self::METHOD_PUT, '', $parameters, $this->_validator()->design($data));
  }

  /**
   * @return mixed
   */
  public function info() {
    return $this->_call(__FUNCTION__, self::METHOD_GET, '_info');
  }

  /********************************************************************************************************************/

}
 