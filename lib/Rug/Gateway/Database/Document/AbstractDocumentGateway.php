<?php

namespace Rug\Gateway\Database\Document;

use Rug\Connector\Connector;
use Rug\Exception\RugException;
use Rug\Gateway\Database\AbstractDatabaseGateway;

abstract class AbstractDocumentGateway extends AbstractDatabaseGateway {

  /**
   * @var string
   */
  private $_id;

  /**
   * @param Connector $connector
   * @param string $db
   * @param string $id
   */
  public function __construct(Connector $connector, $db, $id) {
    parent::__construct($connector, $db);
    $this->_id = $id;
  }

  /**
   * @return string
   */
  public function getID() {
    return $this->_id;
  }

  /********************************************************************************************************************/

  public function rev() {
    return $this->_call(__FUNCTION__, self::METHOD_HEAD);
  }

  public function revs() {
    return $this->_call(__FUNCTION__, self::METHOD_GET, '', array(
      'revs' => 'true',
    ));
  }

  /********************************************************************************************************************/

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
    return $this->_call(__FUNCTION__, self::METHOD_GET, $parameters);
  }

  public function kill($rev = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_call(__FUNCTION__, self::METHOD_DELETE, '', array(
      'rev' => $this->_validator()->rev($rev)
    ));
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
    return $this->_call(__FUNCTION__, self::METHOD_PUT, $this->_validator()->name($name), array(
      'rev' => $this->_validator()->rev($rev),
    ), $file, array(), $mime);
  }

  public function detach($name, $rev = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_call(__FUNCTION__, self::METHOD_DELETE, $name, array(
      'rev' => $this->_validator()->rev($rev),
    ));
  }

  /********************************************************************************************************************/

}
 