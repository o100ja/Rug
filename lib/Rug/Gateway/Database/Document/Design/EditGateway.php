<?php

namespace Rug\Gateway\Database\Document\Design;

use Rug\Connector\Connector;
use Rug\Message\Factory\Database\Document\Design\EditFactory;
use Rug\Message\Parser\Database\Document\Design\EditParser;

class EditGateway extends AbstractSectionGateway {

  /********************************************************************************************************************/

  public function __construct(Connector $connector, $db, $id, $name) {
    parent::__construct($connector, $db, $id, $name);
    $this->_setFactory(new EditFactory($connector, $db, $id, $name));
    $this->_setParser(new EditParser($db, $id, $name));

  }

  /********************************************************************************************************************/

  public function save($docID = null, array $parameters = array(), $mime = self::MIME_JSON) {
    if (empty($docID)) {
      $response = $this->_send(self::METHOD_POST, $docID, $parameters);
    } else {
      $response = $this->_send(self::METHOD_PUT, $docID, $parameters);
    }
    return $this->_parse($response, __FUNCTION__, $mime);
  }

  /********************************************************************************************************************/

}
