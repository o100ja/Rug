<?php

namespace Rug\Message\Factory;

class ConfigFactory extends AbstractFactory {

  public function path($path = '') {
    if (empty($path)) {
      return '_config';
    }
    return '_config/' . $path;
  }

}
