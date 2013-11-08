<?php
namespace Rug\Gateway;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\ConfigFactory;
use Rug\Message\Parser\ConfigParser;

class ConfigGateway extends AbstractGateway {

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder, Connector $connector) {
    parent::__construct($coder, $connector);
    $this->_setFactory(new ConfigFactory($coder, $connector));
    $this->_setParser(new ConfigParser($coder));
  }

  /********************************************************************************************************************/

  /**
   * @return mixed
   */
  public function dump() {
    return $this->_call(__FUNCTION__, self::METHOD_GET);
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function section($name) {
    return $this->_call(__FUNCTION__, self::METHOD_GET, $name);
  }

  /**
   * @param string $section
   * @param string $key
   * @return mixed
   */
  public function get($section, $key) {
    return $this->_call(__FUNCTION__, self::METHOD_GET, $section . '/' . $key);
  }

  /**
   * @param string $section
   * @param string $key
   * @param mixed $value
   * @return mixed
   */
  public function set($section, $key, $value) {
    return $this->_call(__FUNCTION__, self::METHOD_PUT, $section . '/' . $key, array(), $this->_encode($value));
  }

  /**
   * @param string $section
   * @param string $key
   * @return mixed
   */
  public function rem($section, $key) {
    return $this->_call(__FUNCTION__, self::METHOD_GET, $section . '/' . $key);
  }

  /********************************************************************************************************************/

}
 