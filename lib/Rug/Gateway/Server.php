<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
use Rug\Message\Factory\ServerFactory;
use Rug\Message\Parser\ServerParser;

class Server extends AbstractGateway {

  private $_name;

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db = null) {
    parent::__construct($connector, new ServerFactory($connector), new ServerParser());
    $this->_name = $db;
  }

  /********************************************************************************************************************/

  public function getDefaultDB() {
    return $this->_name;
  }

  /**
   * @param string|null $name
   * @return Database
   */
  public function db($name = null) {
    return new Database($this->_connector, empty($name) ? $this->_name : $name);
  }

  /**
   * @param $name
   * @return Database
   */
  public function createDB($name) {
    $this->_invoke('_create_db', self::METHOD_PUT, $this->_validator()->dbName($name));
    return $this->db($name);
  }

  /**
   * @param $name
   * @return $this
   */
  public function deleteDB($name) {
    $this->_invoke('_delete_db', self::METHOD_DELETE, $this->_validator()->dbName($name));
    return $this;
  }

  public function gatherDB() {
    return $this->_call(self::METHOD_GET, '_all_dbs');
  }

  /********************************************************************************************************************/

  /**
   * @param int $count
   * @return string[]
   */
  public function uuids($count = 1) {
    return $this->_call(self::METHOD_GET, '_uuids', array(
      'count' => $this->_validator()->count($count)
    ));
  }

  /**
   * @return string
   */
  public function uuid() {
    return reset($this->uuids(1));
  }

  /********************************************************************************************************************/

  public function restart() {
    return $this->_call(self::METHOD_POST, '_restart');
  }

  /********************************************************************************************************************/

  /**
   * @return string
   */
  public function version() {
    return $this->_call(self::METHOD_GET);
  }

  public function tasks() {
    return $this->_call(self::METHOD_GET, '_active_tasks');
  }

  public function updates() {
    return $this->_call(self::METHOD_GET, '_db_updates');
  }

  public function log($bytes = 1000, $offset = 0) {
    return $this->_call(self::METHOD_GET, '_log', array(
      'bytes'  => $bytes,
      'offset' => $offset,
    ));
  }

  public function stats() {
    return $this->_call(self::METHOD_GET, '_stats');
  }

  public function session() {
    return $this->_call(self::METHOD_GET, '_session');
  }

  /********************************************************************************************************************/

  public function replicate($src, $dst, $create = false, $continuous = false, $filter = null, array $params = array()) {
    $post = (object)array(
      'source'        => $this->_validator()->dbUrl($src),
      'target'        => $this->_validator()->dbUrl($dst),
      'continuous'    => $this->_validator()->boolean($continuous),
      'create_target' => $this->_validator()->boolean($create),
    );
    if (!empty($filter)) {
      $post->filter = $this->_validator()->filter($filter);
      if (!empty($params)) {
        $post->filterQueryParams = $params;
      }
    }
    return $this->_call(self::METHOD_POST, '_replicate', array(), $post);
  }


}
 