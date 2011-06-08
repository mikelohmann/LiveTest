<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Config\Tags\TestSuite;

/**
 * This tag adds the test cases to the configuration. All tags that are not known withing this class are
 * handed to parser.
 *
 * @example
 * Sessions:
     login:
       Pages:
         - /login.php:
           - auth:
               user: value
               pass: value
           - post:
               key1: value1
               key2: value2

 *
 * @author Mike Lohmann
 */
class Sessions extends Base
{
  /**
   * @see LiveTest\Config\Tags\TestSuite.Base::doProcess()
   */
  protected function doProcess(\LiveTest\Config\TestSuite $config, array $parameters)
  {
    foreach ( $parameters as $sessionName => $sessionParameter )
    {
      echo "\n\n";
      print_r($sessionName);
      echo "\n";
      print_r($sessionParameter);

    }
    die();
  }
}