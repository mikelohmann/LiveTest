<?php

namespace LiveTest\Config\Tags;

use Base\Config\Yaml;

class PageLists extends Base
{
  protected function doProcess(Config $config, array $parameters)
  {
    foreach ($parameters as $file)
    {
      $yaml = new Yaml($config->getBaseDir() . '/' . $file);
      $config->includePages($yaml->Pages->toArray());
    }
  }
}