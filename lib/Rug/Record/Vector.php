<?php
namespace Rug\Record;

class Vector implements \Countable {

  /********************************************************************************************************************/

  /**
   * @var string
   */
  protected $_db;

  /**
   * @var array
   */
  protected $_rows;

  /********************************************************************************************************************/

  public function __construct($rows = null, $db = null) {
    $this->_db   = $db;
    $this->_rows = $rows;
  }

  /********************************************************************************************************************/

  /**
   * @return string
   */
  public function getDB() {
    return $this->_db;
  }

  /********************************************************************************************************************/

  /**
   * @param bool $raw
   * @return mixed|null
   */
  public function one($raw = false) {
    if (empty($this->_rows)) {
      return null;
    }
    $row = reset($this->_rows);
    return $raw ? $row : $row->value;
  }

  /**
   * @param bool $raw
   * @return array
   */
  public function all($raw = false) {
    if (empty($this->_rows)) {
      return array();
    }
    if ($raw) {
      return $this->_rows;
    }
    return array_map(function ($row) {
      return $row->value;
    }, $this->_rows);
  }

  /**
   * @return array
   */
  public function map() {
    $data = array();
    foreach ($this->_rows as $row) {
      $data[$row->key] = $row->value;
    }
    return $data;
  }

  /**
   * @return int
   */
  public function count() {
    return count($this->_rows);
  }

  /********************************************************************************************************************/

}
 