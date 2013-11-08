<?php

namespace Rug\Message\Parser\Database\Document\Design;

use Buzz\Message\Response;
use Rug\Coder\CoderManager;

class EditParser extends AbstractSectionParser {

  public function save(Response $response, $mime = CoderManager::MIME_JSON) {
    $oldRev = $this->_parse($response, $mime);
    $newRev = $response->getHeader('X-Couch-Update-NewRev');
    $id     = $response->getHeader('X-Couch-Id');
    return (object)array(
      'id'      => $id,
      'rev'     => $newRev,
      'rev_new' => $newRev,
      'rev_old' => $oldRev,
    );
  }
}

