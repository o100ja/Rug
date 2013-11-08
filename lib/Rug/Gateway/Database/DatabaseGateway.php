<?php

namespace Rug\Gateway\Database;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Gateway\Database\Document\DesignGateway;
use Rug\Gateway\Database\Document\DocumentGateway;
use Rug\Message\Factory\Database\DatabaseFactory;
use Rug\Message\Parser\Database\DatabaseParser;

class DatabaseGateway extends AbstractDatabaseGateway {

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder, Connector $connector, $db) {
    parent::__construct($coder, $connector, $db);
    $this->_setFactory(new DatabaseFactory($coder, $connector, $db));
    $this->_setParser(new DatabaseParser($coder, $db));
  }

  /********************************************************************************************************************/

  /**
   * @param string $id
   * @return DocumentGateway
   */
  public function doc($id) {
    return new DocumentGateway($this->_coder(), $this->_connector(), $this->getDB(), $this->_validator()->id($id));
  }

  /**
   * @param $name
   * @return DesignGateway
   */
  public function design($name) {
    return new DesignGateway($this->_coder(), $this->_connector(), $this->getDB(), $this->_validator()->name($name));
  }

  /********************************************************************************************************************/

  /**
   * @return mixed
   */
  public function status() {
    return $this->_call(__FUNCTION__, self::METHOD_GET);
  }

  /**
   * @param array $revs
   * @return array
   */
  public function missingRevs(array $revs = array()) {
    return $this->_call(__FUNCTION__, self::METHOD_POST, '_missing_revs', array(), array(
      'keys' => $revs,
    ));
  }

  /********************************************************************************************************************/

  /**
   * @param array $ids
   * @return mixed
   */
  public function changes(array $ids = array()) {
    $parameters = array();
    if (!empty($ids)) {
      $parameters['doc_ids'] = $this->_encode($ids);
    }
    return $this->_call(__FUNCTION__, self::METHOD_GET, '_changes', $parameters);
  }

  /**
   * @param null|string $view
   * @return mixed
   */
  public function compact($view = null) {
    $path = '_compact';
    if (!empty($view)) {
      $path .= $this->_validator()->view($view);
    }
    return $this->_call(__FUNCTION__, self::METHOD_POST, $path);
  }

  /**
   * @return mixed
   */
  public function commit() {
    return $this->_call(__FUNCTION__, self::METHOD_POST, '_ensure_full_commit');
  }

  /********************************************************************************************************************/

  /**
   * @return mixed
   */
  public function getSecurity() {
    return $this->_call(__FUNCTION__, self::METHOD_GET, '_security');
  }

  /**
   * @param array $adminRoles
   * @param array $adminNames
   * @param array $readerRoles
   * @param array $readerNames
   * @return mixed
   */
  public function setSecurity(
    array $adminRoles = array(), array $adminNames = array(),
    array $readerRoles = array(), array $readerNames = array()
  ) {
    return $this->_call(__FUNCTION__, self::METHOD_PUT, '_security', array(), array(
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

  /**
   * @return mixed
   */
  public function getRevsLimit() {
    return $this->_call(__FUNCTION__, self::METHOD_GET, '_revs_limit');
  }

  /**
   * @param integer $limit
   * @return mixed
   */
  public function setRevsLimit($limit) {
    return $this->_call(__FUNCTION__, self::METHOD_GET, '_revs_limit', array(), $limit);
  }

  /********************************************************************************************************************/

  /**
   * @param string $id
   * @param null|string $rev
   * @param bool $conflicts
   * @param bool $info
   * @return mixed
   */
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
    return $this->_call(__FUNCTION__, self::METHOD_GET, $this->_validator()->id($id), $parameters);
  }

  /**
   * @param array $ids
   * @param bool $includeDocs
   * @param bool $deleted
   * @param bool $stale
   * @return object
   */
  public function herd(array $ids = null, $includeDocs = true, $deleted = false, $stale = false) {
    $parameters = array(
      'include_docs' => $includeDocs ? 'true' : 'false',
    );
    if ($stale) {
      $parameters['stale'] = 'ok';
    }
    if (empty($ids)) {
      $data = $this->_call(__FUNCTION__, self::METHOD_GET, '_all_docs', $parameters);
    } else {
      $data = $this->_call(__FUNCTION__, self::METHOD_POST, '_all_docs', $parameters, array(
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

  /**
   * @param array $docs
   * @param bool $allOrNothing
   * @return mixed
   */
  public function bulk(array $docs, $allOrNothing = true) {
    return $this->_call(__FUNCTION__, self::METHOD_POST, '_bulk_docs', array(), array(
      'docs'           => $docs,
      'all_or_nothing' => $this->_validator()->boolean($allOrNothing),
    ));
  }

  /**
   * @param array|object $doc
   * @param bool $batch
   * @return mixed
   */
  public function save($doc, $batch = false) {
    $parameters = array();
    if ($batch) {
      $parameters['batch'] = 'ok';
    }
    return $this->_call(__FUNCTION__, self::METHOD_POST, '', $parameters, $this->_validator()->doc($doc));
  }

  /**
   * @param string $id
   * @param null|string $rev
   * @return mixed
   */
  public function kill($id, $rev = null) {
    if (empty($rev)) {
      $rev = $this->doc($id)->rev();
    }
    return $this->_call(__FUNCTION__, self::METHOD_DELETE, $this->_validator()->id($id), array(
      'rev' => $this->_validator()->rev($rev)
    ));
  }

  /**
   * @param string $id
   * @param array $revs
   * @return mixed
   */
  public function bury($id, array $revs = null) {
    if (empty($revs)) {
      $revs = $this->doc($id)->revs();
    }
    return $this->_call(__FUNCTION__, self::METHOD_POST, '_purge', array(), array(
      $id => $revs,
    ));
  }

  /********************************************************************************************************************/

}
 