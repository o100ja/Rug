<?php
namespace Rug\Coder\Application;

use Rug\Coder\AbstractCoder;

class Json extends AbstractCoder {

  public function encode($data) {
    return json_encode($data);
  }

  public function decode($data) {
    return json_decode($data);
  }

}
 