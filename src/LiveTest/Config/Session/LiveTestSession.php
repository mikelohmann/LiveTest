<?php
namespace LiveTest\Config\Session;

use LiveTest\Config\Session\Session;

use Base\Http\Request\Request;

class LiveTestSession implements Session
{

  /**
   *
   * Holds the PageRequests
   * @var Array
   */
  private $includedPageRequests = array();

  /**
   *
   * Holds the excludedPageRequests
   * @var Array
   */
  private $excludedPageRequests = array();

  /**
   *
   * Container for Page Manipulators
   * @var array $pageManipulators
   */
  private $pageManipulators = array ();

  /**
   * @todo move to Session
   * Used to add a page manipulator. These manipulators are used to manipulate the
   * pages (url strings) registered in this config file.

   * @param PageManipulator $pageManipulator
   */
  public function addPageManipulator(PageManipulator $pageManipulator)
  {
    $this->pageManipulators[] = $pageManipulator;
  }

  /**
   * Include an additional page to the config.
   *
   * @param string $page
   */
  public function includePageRequest(Request $pageRequest)
  {
    $this->includedPageRequests[$pageRequest->getIdentifier()] = $pageRequest;
  }

  /**
   * Includes an array containing pages to the config.
   *
   * @param array[] $pageRequests
   */
  public function includePageRequests(array $pageRequests)
  {
    foreach ( $pageRequests as $aPageRequest )
    {
      $this->includePageRequest($aPageRequest);
    }

  }

  /**
   * Removes a page from the config.
   *
   * @param string $page
   */
  public function excludePageRequest(Request $pageRequest)
  {
    $this->excludedPageRequests[$pageRequest->getIdentifier()] = $pageRequest;
  }

  /**
   * Removes a set of pageRequests from this config.
   *
   * @param array[] $pageRequests
   */
  public function excludePageRequests($pageRequests)
  {
    foreach ( $pageRequests as $aPageRequest )
    {
      $this->excludePageRequest($aPageRequest);
    }
  }

 /**
   * Returns the list of pages.
   *
   * @return array[]
   */
  public function getPageRequests()
  {

    if ($this->inherit && !is_null($this->parentConfig))
    {
      $results = array_merge($this->includedPageRequests, $this->parentConfig->getPageRequests());
    }
    else
    {
      $results = $this->includedPageRequests;
    }

    $pageRequests = $this->getReducedPageRequests($results, $this->excludedPageRequests);

    /*foreach( $this->pageManipulators as $manipulator )
    {
      foreach( $pageRequests as &$pageRequest )
      {
        $pageRequest = $manipulator->manipulate($pageRequest);
      }
    }*/

    return $pageRequests;
  }

  private function getReducedPageRequests(array $includedPageRequest, array $excludedPageRequests)
  {
     foreach($excludedPageRequests as $identifier => $pageRequest)
      {
        if(array_key_exists($identifier, $includedPageRequest))
        {
          unset($includedPageRequest[$identifier]);
        }
      }

      return $includedPageRequest;
  }
}