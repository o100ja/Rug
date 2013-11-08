<?php

namespace Rug\Gateway\Database\Document;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\DocumentFactory;
use Rug\Message\Parser\Database\Document\DocumentParser;

class DocumentGateway extends AbstractDocumentGateway {

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder, Connector $connector, $db, $id) {
    parent::__construct($coder, $connector, $db, $id);
    $this->_setFactory(new DocumentFactory($coder, $connector, $db, $id));
    $this->_setParser(new DocumentParser($coder, $db, $id));
  }

  /********************************************************************************************************************/

  /**
   * @param array|object $data
   * @param bool $batch
   * @param null|string $rev
   * @return mixed
   */
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

  /**
   * @param string $dstID
   * @param null|string $srcRev
   * @param null|string $dstRev
   * @return mixed
   */
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
 