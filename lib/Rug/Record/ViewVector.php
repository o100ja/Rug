<?php
namespace Rug\Record;

class ViewVector extends Vector {

  /********************************************************************************************************************/

  /**
   * @var string
   */
  protected $_view;
  /**
   * @var string
   */
  protected $_name;

  /********************************************************************************************************************/

  public function __construct($rows, $db = null, $view = null, $name = null) {
    parent::__construct($rows, $db);
    $this->_view = $view;
    $this->_name = $name;
  }

  /********************************************************************************************************************/

  /**
   * @return string
   */
  public function getName() {
    return $this->_name;
  }

  /**
   * @return string
   */
  public function getView() {
    return $this->_view;
  }

  /********************************************************************************************************************/

}
 