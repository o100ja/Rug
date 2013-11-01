<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
use Rug\Message\Factory\DocumentFactory;
use Rug\Message\Parser\DocumentParser;

class Document extends AbstractDocument {

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db, $id);
    $this->_setFactory(new DocumentFactory($connector, $db, $id));
    $this->_setParser(new DocumentParser());
  }

  /********************************************************************************************************************/

  public function revs() {
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, '', array(
      'revs' => 'true',
    ));
  }

  public function data($rev = null, $conflicts = false, $info = false) {
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
    return $this->_invoke(__FUNCTION__, self::METHOD_GET, $parameters);
  }

  public function save($data, $batch = true, $rev = null) {
    $parameters = array();
    if ($batch) {
      $parameters['batch'] = 'ok';
    }
    $headers = array();
    if (!empty($rev)) {
      $headers = array('If-Match' => $this->_validator()->rev($rev));
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_PUT, '', $parameters, $this->_validator()->data($data), $headers);
  }

  public function kill($rev = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_invoke(__FUNCTION__, self::METHOD_DELETE, '', array(
      'rev' => $this->_validator()->rev($rev)
    ));
  }

  public function copy($dstID, $srcRev = null, $dstRev = null) {
    $parameters = empty($srcRev) ? array() : array(
      'rev' => $this->_validator()->rev($srcRev)
    );
    $dstPath = $dstID;
    $headers    = array(
      'Destination' => empty($dstRev) ? $dstPath : $dstPath . http_build_query(array(
            'rev' => $this->_validator()->rev($dstRev)
          ))
    );
    return $this->_invoke(__FUNCTION__, self::METHOD_COPY, '', $parameters, null, $headers);
  }

  /********************************************************************************************************************/

}
 