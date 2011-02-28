<?php

namespace LiveTest\Config\Tags\TestSuite;



use Base\Config\Yaml;

class PageLists extends Base
{
  protected function doProcess(\LiveTest\Config\TestSuite $config, array $parameters)
  {
    foreach ($parameters as $file)
    {
      $yaml = new Yaml($config->getBaseDir() . '/' . $file);
      $this->getParser()->parse( $yaml->toArray( ), $config );
    }
  }
}