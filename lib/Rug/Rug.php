<?php
namespace Rug;

//define('RUG_MAX', []);
//define('RUG_MIN', null);

use Buzz\Client\ClientInterface;
use Rug\Connector\Connector;
use Rug\Gateway\ConfigGateway;
use Rug\Gateway\Database\DatabaseGateway;
use Rug\Gateway\Database\Document\DesignGateway;
use Rug\Gateway\Database\Document\DocumentGateway;
use Rug\Gateway\ServerGateway;

class Rug {

  /**
   * @var ServerGateway
   */
  private $_server;
  /**
   * @var ConfigGateway
   */
  private $_config;

  public function __construct(array $options = array(), ClientInterface $client = null) {
    $this->_connector = new Connector($options, $client);
    $this->_server    = new ServerGateway($this->_connector, isset($options['name']) ? $options['name'] : null);
  }

  /********************************************************************************************************************/

  /**
   * @return ConfigGateway
   */
  public function config() {
    if (empty($this->_config)) {
      $this->_config = new ConfigGateway($this->_connector);
    }
    return $this->_config;
  }

  /**
   * @return ServerGateway
   */
  public function server() {
    return $this->_server;
  }

  /**
   * @param null $name
   * @return DatabaseGateway
   */
  public function db($name = null) {
    return $this->server()->db($name);
  }

  /**
   * @param $id
   * @param null $db
   * @return DocumentGateway
   */
  public function doc($id, $db = null) {
    return $this->db($db)->doc($id);
  }

  /**
   * @param $name
   * @param null $db
   * @return DesignGateway
   */
  public function design($name, $db = null) {
    return $this->db($db)->design($name);
  }

  /********************************************************************************************************************/

}
