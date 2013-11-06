<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
use Rug\Message\Factory\ConfigFactory;
use Rug\Message\Parser\ConfigParser;

class ConfigGateway extends AbstractGateway {

  /********************************************************************************************************************/

  public function __construct(Connector $connector) {
    parent::__construct($connector);
    $this->_setFactory(new ConfigFactory($connector));
    $this->_setParser(new ConfigParser());
  }

  /********************************************************************************************************************/

  public function dump() {
    return $this->_call(__FUNCTION__, self::METHOD_GET);
  }

  public function section($name) {
    return $this->_call(__FUNCTION__, self::METHOD_GET, $name);
  }

  public function get($section, $key) {
    return $this->_call(__FUNCTION__, self::METHOD_GET, $section . '/' . $key);
  }

  public function set($section, $key, $value) {
    return $this->_call(__FUNCTION__, self::METHOD_PUT, $section . '/' . $key, array(), $this->_encode($value));
  }

  public function rem($section, $key) {
    return $this->_call(__FUNCTION__, self::METHOD_GET, $section . '/' . $key);
  }

  /********************************************************************************************************************/

}
 