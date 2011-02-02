<?php

namespace LiveTest\Extensions;

use LiveTest\TestRun\Properties;

use Base\Http\Response;

use LiveTest\TestRun\Test;
use LiveTest\TestRun\Result\Result;

interface Extension
{
  public function __construct($runId, \Zend_Config $config = null);

  public function preRun(Properties $properties);
  
  public function handleResult( Result $result, Test $test, \Zend_Http_Response $response );
  
  public function postRun( );
}