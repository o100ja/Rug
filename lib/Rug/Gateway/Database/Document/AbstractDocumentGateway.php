<?php

namespace Rug\Gateway\Database\Document;

use Rug\Coder\CoderManager;
use Rug\Connector\Connector;
use Rug\Exception\RugException;
use Rug\Gateway\Database\AbstractDatabaseGateway;

abstract class AbstractDocumentGateway extends AbstractDatabaseGateway {

  /**
   * @var string
   */
  private $_id;

  public function __construct(CoderManager $coder, Connector $connector, $db, $id) {
    parent::__construct($coder, $connector, $db);
    $this->_id = $id;
  }

  /**
   * @return string
   */
  public function getID() {
    return $this->_id;
  }

  /********************************************************************************************************************/

  /**
   * @return string
   */
  public function rev() {
    return $this->_call(__FUNCTION__, self::METHOD_HEAD);
  }

  /**
   * @return array
   */
  public function revs() {
    return $this->_call(__FUNCTION__, self::METHOD_GET, '', array(
      'revs' => 'true',
    ));
  }

  /********************************************************************************************************************/

  /**
   * @param null|string $rev
   * @param bool $conflicts
   * @param bool $info
   * @return mixed
   */
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

  /**
   * @param null $rev
   * @return mixed
   */
  public function kill($rev = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_call(__FUNCTION__, self::METHOD_DELETE, '', array(
      'rev' => $this->_validator()->rev($rev)
    ));
  }

  /**
   * @param string $dstID
   * @param null $srcRev
   * @param null $dstRev
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

  /**
   * @param string $name
   * @param null $path
   * @return \SplFileInfo
   * @throws \Rug\Exception\RugException
   */
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

  /**
   * @param string $name
   * @param bool $absolute
   * @return string
   */
  public function attachmentLink($name, $absolute = true) {
    $url = $this->_factory()->createURL($this->_validator()->name($name));
    return $url->format($absolute ? 'HR' : 'R');
  }

  /**
   * @param string $name
   * @param \SplFileInfo $file
   * @param null|string $rev
   * @param null|string $mime
   * @return mixed
   */
  public function attach($name, \SplFileInfo $file, $rev = null, $mime = null) {
    if (empty($rev)) {
      $rev = $this->rev();
    }
    return $this->_call(__FUNCTION__, self::METHOD_PUT, $this->_validator()->name($name), array(
      'rev' => $this->_validator()->rev($rev),
    ), $file, array(), $mime);
  }

  /**
   * @param string $name
   * @param null|string $rev
   * @return mixed
   */
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
 