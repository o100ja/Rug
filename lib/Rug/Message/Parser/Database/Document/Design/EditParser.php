<?php

namespace Rug\Message\Parser\Database\Document\Design;

use Buzz\Message\Response;

class EditParser extends AbstractDesignParser {

  public function save(Response $response, $mime = self::MIME_JSON) {
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

