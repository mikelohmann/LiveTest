<?php

namespace LiveTest\Report\Format;

use LiveTest\TestRun\Information;

use LiveTest\TestRun\Result\ResultSet;
use LiveTest\TestRun\Result\Result;

class Csv implements Format
{
  public function formatSet(ResultSet $set, array $connectionStatuses, Information $information)
  {
    $text = '';
    foreach ( $set->getResults() as $result )
    {
      $test = $result->getTest();

<<<<<<< HEAD
      $text .= $result->getUri() . ";".$test->getName().";".$test->getClassName().";"
=======
      $text .= $result->getUrl() . ";".$test->getName().";".$test->getClassName().";"
>>>>>>> 0dedd828b6fbccfed8a07eb12de3ccf8e5bfce07
               . $result->getStatus()."\n";
    }
    return $text;
  }
}
