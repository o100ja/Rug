<?php
namespace Rug\Gateway;

use Rug\Connector\Connector;
use Rug\Exception\RugException;
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

  public function rev() {
    return $this->_invoke('_head', self::METHOD_HEAD);
  }

  public function revs() {
    return $this->_invoke('_revs', self::METHOD_GET, '', array(
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
    return $this->_invoke('_data', self::METHOD_GET, $parameters);
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
    return $this->_invoke('_head', self::METHOD_PUT, '', $parameters, $this->_validator()->data($data), $headers);
  }

  public function kill($rev = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_invoke('_head', self::METHOD_DELETE, '', array(
      'rev' => $this->_validator()->rev($rev)
    ));
  }

  /********************************************************************************************************************/

  public function copy($dstID, $srcRev = null, $dstRev = null) {
    $parameters = empty($srcRev) ? array() : array(
      'rev' => $this->_validator()->rev($srcRev)
    );
    $headers    = array(
      'Destination' => empty($dstRev) ? $dstID : $dstID . http_build_query(array(
            'rev' => $this->_validator()->rev($dstRev)
          ))
    );
    return $this->_invoke('_copy', self::METHOD_COPY, '', $parameters, null, $headers);
  }

  /********************************************************************************************************************/

  public function attachmentFile($name, $path = null) {
    if (empty($path)) {
      $path = tempnam(sys_get_temp_dir(), 'rug_' . $name . '_');
    }
    if (!is_writable($path)) {
      throw new RugException("The specified attachment download location is invalid");
    }

    try {
      if (!copy($this->attachmentLink($name), $path)) {
        throw new \Exception("Failed to download the attachment: $name");
      }
    } catch (\Exception $ex) {
      throw new RugException('attachment', "Failed to download the attachment: $name", 0, $ex);
    }
    return new \SplFileInfo($path);
  }

  public function attachmentLink($name, $absolute = true) {
    $url = $this->_factory()->createURL($this->_validator()->name($name));
    return $url->format($absolute ? 'HR' : 'R');
  }

  public function attach($name, \SplFileInfo $file, $rev = null, $mime = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_invoke('_head', self::METHOD_PUT, $this->_validator()->name($name), array(
      'rev' => $this->_validator()->rev($rev),
    ), $file, array(), $mime);
  }

  public function detach($name, $rev = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_invoke('_head', self::METHOD_DELETE, $name, array(
      'rev' => $this->_validator()->rev($rev),
    ));
  }

  /********************************************************************************************************************/

}
 