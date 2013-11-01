<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
use Rug\Message\Factory\DatabaseFactory;
use Rug\Message\Parser\DatabaseParser;

class Database extends AbstractGateway {

  private $_name;

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db) {
    parent::__construct($connector);
    $this->_setFactory(new DatabaseFactory($connector, $db));
    $this->_setParser(new DatabaseParser());
    $this->_name = $db;
  }

  public function getName() {
    return $this->_name;
  }

  /********************************************************************************************************************/

  /**
   * @param string $id
   * @param string $rev
   * @return Document
   */
  public function doc($id) {
    return new Document($this->_connector, $this->_name, $this->_validator()->id($id));
  }

  /**
   * @param $name
   * @return Design
   */
  public function design($name) {
    return new Design($this->_connector, $this->_name, $this->_validator()->name($name));
  }

  /********************************************************************************************************************/

  public function status() {
    return $this->_invoke(__FUNCTION__, self::METHOD_GET);
  }


  public function missingRevs(array $revs = array()) {
    return $this->_invoke(__FUNCTION__, self::METHOD_POST, '_missing_revs', array(), array(
      'keys' => $revs,
    ));
  }

  /********************************************************************************************************************/

  public function changes(array $ids = array()) {
    $parameters = array();
    if (!empty($ids)) {
      $parameters['doc_ids'] = json_encode($ids);
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, '_changes', $parameters);
  }

  /**
   * @param string|bool $db
   * @param string|null $view
   * @return boolean
   */
  public function compact($view = null) {
    $path = '_compact';
    if (!empty($view)) {
      $path .= $this->_validator()->view($view);
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_POST, $path);
  }

  public function commit() {
    return $this->_invoke(__FUNCTION__, self::METHOD_POST, '_ensure_full_commit');
  }

  /********************************************************************************************************************/

  public function getSecurity() {
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, '_security');
  }

  public function setSecurity(
    array $adminRoles = array(), array $adminNames = array(),
    array $readerRoles = array(), array $readerNames = array()
  ) {
    return $this->_invoke(__FUNCTION__, self::METHOD_PUT, '_security', array(), array(
      'admins'  => array(
        'roles' => $adminRoles,
        'names' => $adminNames,
      ),
      'readers' => array(
        'roles' => $readerRoles,
        'names' => $readerNames,
      ),
    ));
  }

  /********************************************************************************************************************/

  public function getRevsLimit() {
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, '_revs_limit');
  }

  public function setRevsLimit($limit) {
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, '_revs_limit', array(), $limit);
  }

  /********************************************************************************************************************/

  public function find($id, $rev = null, $conflicts = false, $info = false) {
    $parameters = array();
    if (!empty($rev)) {
      $parameters['rev'] = $this->_validator()->rev($rev);
    }
    if ($conflicts) {
      $parameters['conflicts'] = 'true';
    }
    if ($info) {
      $parameters['revs_info'] = 'true';
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, $this->_validator()->id($id), $parameters);
  }

  public function herd(array $ids = null, $includeDocs = true, $deleted = false, $stale = false) {
    $parameters = array(
      'include_docs' => $includeDocs ? 'true' : 'false',
    );
    if ($stale) {
      $parameters['stale'] = 'ok';
    }
    if (empty($ids)) {
      $data = $this->_invoke(__FUNCTION__, self::METHOD_GET, '_all_docs', $parameters);
    } else {
      $data = $this->_invoke(__FUNCTION__, self::METHOD_POST, '_all_docs', $parameters, array(
        'keys' => $ids,
      ));
    }
    if ($deleted) {
      return $data;
    }
    $data->rows = array_filter($data->rows, function ($row) {
      return empty($row->value->deleted);
    });

    $data->total_rows = count($data->rows);
    return $data;
  }

  public function bulk(array $docs, $allOrNothing = false) {
    return $this->_invoke(__FUNCTION__, self::METHOD_POST, '_bulk_docs', array(), array(
      'docs'           => $docs,
      'all_or_nothing' => $this->_validator()->boolean($allOrNothing),
    ));
  }

  public function save($doc, $batch = false) {
    $parameters = array();
    if ($batch) {
      $parameters['batch'] = 'ok';
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_POST, '', $parameters, $this->_validator()->doc($doc));
  }

  public function kill($id, $rev = null) {
    if (empty($rev)) {
      $rev = $this->doc($id)->rev();
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_DELETE, $this->_validator()->id($id), array(
      'rev' => $this->_validator()->rev($rev)
    ));
  }

  public function bury($id, array $revs = null) {
    if (empty($revs)) {
      $revs = $this->doc($id)->revs();
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_POST, '_purge', array(), array(
      $id => $revs,
    ));
  }

  /********************************************************************************************************************/

}
 