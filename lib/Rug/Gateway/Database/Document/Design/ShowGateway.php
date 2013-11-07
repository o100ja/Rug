<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\Design\ShowFactory;
use Rug\Message\Parser\Database\Document\Design\ShowParser;

class ShowGateway extends AbstractSectionGateway {

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id, $name);
    $this->_setFactory(new ShowFactory($connector, $db, $id, $name));
    $this->_setParser(new ShowParser($db, $id, $name));

  }

  /********************************************************************************************************************/

  public function show($docID = null, array $parameters = array(), $mime = self::MIME_JSON) {
    if (empty($docID)) {
      $response = $this->_send(self::METHOD_GET, $docID, $parameters);
    } else {
      $response = $this->_send(self::METHOD_POST, $docID, $parameters);
    }
    return $this->_parse($response, __FUNCTION__, $mime);
  }

  /********************************************************************************************************************/

}
