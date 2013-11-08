<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\Design\ShowFactory;
use Rug\Message\Parser\Database\Document\Design\ShowParser;

class ShowGateway extends AbstractSectionGateway {

  /********************************************************************************************************************/

  public function __construct(CoderManager $coder, Connector $connector, $db, $id, $name) {
    parent::__construct($coder, $connector, $db, $id, $name);
    $this->_setFactory(new ShowFactory($coder, $connector, $db, $id, $name));
    $this->_setParser(new ShowParser($coder, $db, $id, $name));
  }

  /********************************************************************************************************************/

  public function show($docID = null, array $parameters = array(), $mime = CoderManager::MIME_JSON) {
    if (empty($docID)) {
      $response = $this->_send(self::METHOD_GET, $docID, $parameters);
    } else {
      $response = $this->_send(self::METHOD_POST, $docID, $parameters);
    }
    return $this->_parse($response, __FUNCTION__, $mime);
  }

  /********************************************************************************************************************/

}
