<?php
namespace Rug;

use Buzz\Client\ClientInterface;
use Rug\Connector\Connector;
use Rug\Gateway\Server;

class Rug {

  const METHOD_GET    = 'GET';
  const METHOD_HEAD   = 'HEAD';
  const METHOD_POST   = 'POST';
  const METHOD_PUT    = 'PUT';
  const METHOD_DELETE = 'DELETE';
  const METHOD_COPY   = 'COPY';

  /**
   * @var Server
   */
  private $_server;

  public function __construct(array $options = array(), ClientInterface $client = null) {
    $this->_connector = new Connector($options, $client);
    $this->_server    = new Server($this->_connector, isset($options['name']) ? $options['name'] : null);
  }

  /********************************************************************************************************************/

  public function config() {

  }

  public function server() {
    return $this->_server;
  }

  public function db($name = null) {
    return $this->server()->db($name);

  }

  /********************************************************************************************************************/

}
