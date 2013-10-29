<?php
namespace Rug\Gateway\Design;

use Rug\Connector\Connector;
use Rug\Gateway\AbstractGateway;
use Rug\Message\Factory\Design\ViewFactory;
use Rug\Message\Parser\DesignParser;

class View extends AbstractGateway {
  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id);
    $this->_setFactory(new ViewFactory($connector, $db, $id, $name));
    $this->_setParser(new DesignParser());
  }

  /********************************************************************************************************************/

  protected function _get(array $parameters,
                          $reduce = true, $group = false,
                          $level = 0, $skip = 0, $limit = 0,
                          $includeDocs = false, $stale = false
  ) {
    $parameters = array_merge($parameters, array(
      'group' => json_encode($group),
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
    return $this->_invoke('', self::METHOD_GET, '', $parameters);
  }

  public function fetchAll(
    $reduce = true, $group = false, $level = 0,
    $skip = 0, $limit = 0,
    $includeDocs = false, $stale = false
  ) {
    $parameters = array();
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }

  public function fetchKey($key,
                           $reduce = true, $group = false, $level = 0,
                           $skip = 0, $limit = 0,
                           $includeDocs = false, $stale = false
  ) {
    $parameters = array(
      'key' => json_encode($key),
    );
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }


  public function rangeKey($start, $final, $descending = false, $inclusiveEnd = true,
                           $reduce = true, $group = false, $level = 0,
                           $skip = 0, $limit = 0,
                           $includeDocs = false, $stale = false
  ) {
    $parameters = array(
      'startkey'      => json_encode($start),
      'endkey'        => json_encode($final),
      'descending'    => json_encode($descending),
      'inclusive_end' => json_encode($inclusiveEnd),
    );
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }

  public function rangeDoc($start, $final, $descending = false, $inclusiveEnd = true,
                           $reduce = true, $group = false, $level = 0,
                           $skip = 0, $limit = 0,
                           $includeDocs = false, $stale = false
  ) {
    $parameters = array(
      'startkey_docid' => json_encode($start),
      'endkey_docid'   => json_encode($final),
      'descending'     => json_encode($descending),
      'inclusive_end'  => json_encode($inclusiveEnd),
    );
    return $this->_get($parameters, $reduce, $group, $level, $skip, $limit, $includeDocs, $stale);
  }

}
