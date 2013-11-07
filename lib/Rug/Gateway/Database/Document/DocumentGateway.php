<?php

namespace Rug\Gateway\Database\Document;

use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\DocumentFactory;
use Rug\Message\Parser\Database\Document\DocumentParser;

class DocumentGateway extends AbstractDocumentGateway {

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db, $id);
    $this->_setFactory(new DocumentFactory($connector, $db, $id));
    $this->_setParser(new DocumentParser($db, $id));
  }

  /********************************************************************************************************************/

  public function save($data, $batch = true, $rev = null) {
    $parameters = array();
    if ($batch) {
      $parameters['batch'] = 'ok';
    }
    $headers = array();
    if (!empty($rev)) {
      $headers = array('If-Match' => $this->_validator()->rev($rev));
    }
    return $this->_call(__FUNCTION__, self::METHOD_PUT, '', $parameters, $this->_validator()->data($data), $headers);
  }

  public function copy($dstID, $srcRev = null, $dstRev = null) {
    $parameters = empty($srcRev) ? array() : array(
      'rev' => $this->_validator()->rev($srcRev)
    );
    $dstPath    = $dstID;
    $headers    = array(
      'Destination' => empty($dstRev) ? $dstPath : $dstPath . http_build_query(array(
            'rev' => $this->_validator()->rev($dstRev)
          ))
    );
    return $this->_call(__FUNCTION__, self::METHOD_COPY, '', $parameters, null, $headers);
  }

  /********************************************************************************************************************/

}
 