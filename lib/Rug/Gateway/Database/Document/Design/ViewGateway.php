<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\Design\ViewFactory;
use Rug\Message\Parser\Database\Document\Design\ViewParser;
use Rug\Record\ViewVector;

class ViewGateway extends AbstractSectionGateway {


  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id, $name);
    $this->_setFactory(new ViewFactory($connector, $db, $id, $name));
    $this->_setParser(new ViewParser($db, $id, $name));

  }

  /********************************************************************************************************************/

  /**
   * @param array $parameters
   * @param bool $reduce
   * @param bool $group
   * @param int $level
   * @param int $skip
   * @param int $limit
   * @param bool $includeDocs
   * @param bool $stale
   * @return ViewVector
   */
  protected function _get(array $parameters,
                          $reduce = true, $group = false,
                          $level = 0, $skip = 0, $limit = 0,
                          $includeDocs = false, $stale = false
  ) {
    $parameters = array_merge($parameters, array(
      'group' => $this->_encode($group),
    ));
    if (empty($reduce)) {
      $parameters['reduce'] = 'false';
    }
    if (!empty($stale)) {
      $parameters['stale'] = 'ok';
    }
    if (!empty($level)) {
      $parameters['group_level'] = $level;
    }
    if (!empty($skip)) {
      $parameters['skip'] = $skip;
    }
    if (!empty($limit)) {
      $parameters['limit'] = $limit;
    }
    if (!empty($includeDocs)) {
      $parameters['include_docs'] = 'true';
    }
    return $this->_call(__FUNCTION__, self::METHOD_GET, '', $parameters);
  }

  /**
   * @param bool $reduce
   * @param bool $group
   * @param int $level
   * @param int $skip
   * @param int $limit
   * @param bool $includeDocs
   * @param bool $stale
   * @return ViewVector
   */
  public function fetchAll(
    $reduce = true, $group = false, $level = 0,
    $skip = 0, $limit = 0,
    $includeDocs = false, $stale = false
  ) {
    $parameters = array();
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }

  /**
   * @param $key
   * @param bool $reduce
   * @param bool $group
   * @param int $level
   * @param int $skip
   * @param int $limit
   * @param bool $includeDocs
   * @param bool $stale
   * @return ViewVector
   */
  public function fetchKey($key,
                           $reduce = true, $group = false, $level = 0,
                           $skip = 0, $limit = 0,
                           $includeDocs = false, $stale = false
  ) {
    $parameters = array(
      'key' => $this->_encode($key),
    );
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }

  /**
   * @param $start
   * @param $final
   * @param bool $descending
   * @param bool $inclusiveEnd
   * @param bool $reduce
   * @param bool $group
   * @param int $level
   * @param int $skip
   * @param int $limit
   * @param bool $includeDocs
   * @param bool $stale
   * @return ViewVector
   */
  public function rangeKey($start, $final,
                           $descending = false, $inclusiveEnd = true,
                           $reduce = true, $group = false, $level = 0,
                           $skip = 0, $limit = 0,
                           $includeDocs = false, $stale = false
  ) {
    $parameters = array(
      'startkey'      => $this->_encode($start),
      'endkey'        => $this->_encode($final),
      'descending'    => $this->_encode($descending),
      'inclusive_end' => $this->_encode($inclusiveEnd),
    );
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }

  /**
   * @param $start
   * @param $final
   * @param bool $descending
   * @param bool $inclusiveEnd
   * @param bool $reduce
   * @param bool $group
   * @param int $level
   * @param int $skip
   * @param int $limit
   * @param bool $includeDocs
   * @param bool $stale
   * @return ViewVector
   */
  public function rangeDoc($start, $final,
                           $descending = false, $inclusiveEnd = true,
                           $reduce = true, $group = false, $level = 0,
                           $skip = 0, $limit = 0,
                           $includeDocs = false, $stale = false
  ) {
    $parameters = array(
      'startkey_docid' => $this->_encode($start),
      'endkey_docid'   => $this->_encode($final),
      'descending'     => $this->_encode($descending),
      'inclusive_end'  => $this->_encode($inclusiveEnd),
    );
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }

}
