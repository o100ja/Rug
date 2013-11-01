<?php
namespace Rug;

use Buzz\Client\ClientInterface;
use Rug\Connector\Connector;
use Rug\Gateway\Config;
use Rug\Gateway\Server;

class Rug {

  /**
   * @var Server
   */
  private $_server;

  public function __construct(array $options = array(), ClientInterface $client = null) {
    $this->_connector = new Connector($options, $client);
    $this->_server    = new Server($this->_connector, isset($options['name']) ? $options['name'] : null);
    $this->_config    = new Config($this->_connector);
  }

  /********************************************************************************************************************/

  public function config() {
    return $this->_config;
  }

  public function server() {
    return $this->_server;
  }

  public function db($name = null) {
    return $this->server()->db($name);

  }

  /********************************************************************************************************************/

}
