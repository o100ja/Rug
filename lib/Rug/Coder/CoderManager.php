<?php
namespace Rug\Coder;

class CoderManager implements \Countable {

  const MIME_JSON = 'application/json';

  /**
   * @var AbstractCoder[]
   */
  private $_coders = array();

  /**
   * @var string
   */
  private $_default = self::MIME_JSON;

  /**
   * @var \finfo
   */
  private $_fInfo;

  /********************************************************************************************************************/

  public function __constructor($default = self::MIME_JSON) {
    $this->_default = $default;
  }

  /********************************************************************************************************************/

  /**
   * @param string $default
   * @return $this
   */
  public function setDefault($default) {
    $this->_default = $default;
    return $this;
  }

  /**
   * @return string
   */
  public function getDefault() {
    return $this->_default;
  }

  /********************************************************************************************************************/

  /**
   * @param $mime
   * @return AbstractCoder
   */
  protected function _factory($mime) {
    $this->_parse($mime, $dir, $cls, $ext);
    $class = sprintf('%s\\%s\\%s', __NAMESPACE__, ucfirst($dir), ucfirst($cls));
    return new $class($ext);
  }

  /**
   * @param $mime
   * @param $type
   * @param $sub
   * @param array $ext
   * @return $this
   */
  protected function _parse($mime, &$type, &$sub, &$ext = array()) {
    $ext = explode(';', $mime);
    list($type, $sub) = explode('/', array_shift($ext));
    return $this;
  }

  /********************************************************************************************************************/

  /**
   * @param $mime
   * @return AbstractCoder
   */
  public function get($mime = null) {
    if (empty($mime)) {
      $mime = $this->_default;
    }
    if (empty($this->_coders[$mime])) {
      $this->_coders[$mime] = $this->_factory($mime);
    }
    return $this->_coders[$mime];
  }

  /**
   * @param \SplFileInfo $file
   * @return string
   */
  public function mime(\SplFileInfo $file) {
    if (empty($this->_fInfo)) {
      $this->_fInfo = new \finfo(FILEINFO_MIME_TYPE);
    }
    return $this->_fInfo->file($file->getRealPath());
  }

  public function count() {
    return count($this->_coders);
  }

  /********************************************************************************************************************/

}
 