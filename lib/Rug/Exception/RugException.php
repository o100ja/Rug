<?php
namespace Rug\Exception;

use Exception;

class RugException extends \RuntimeException {

  public $error;

  public function __construct($error, $message = "", $code = 0, Exception $previous = null) {
    $this->error = $error;
    parent::__construct($message, $code, $previous);
  }

}
 