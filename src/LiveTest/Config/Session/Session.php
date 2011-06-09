<?php
namespace LiveTest\Config\Session;

interface Session
{
  public function includePageRequest(Request $pageRequest);
  public function includePageRequests(array $pageRequests);
  public function excludePageRequest(Request $pageRequest);
  public function excludePageRequests($pageRequests);
  public function getPageRequests();
}