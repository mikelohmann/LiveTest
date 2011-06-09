<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LiveTest\Config;

use LiveTest\Config\Session\LiveTestSession;

use Base\Www\Uri;
use Base\Http\Request\Request;

/**
 * This class contains all information about the tests and the depending pages.
 *
 * @author Nils Langner
 */
use LiveTest\Config\PageManipulator\PageManipulator;

class TestSuite implements Config
{
   const DEFAULT_SESSION = "_default";

  /**
   * Pages that are included
   * @var array[]
   */
  private $includedSessions = array ();

  /**
   * Sessions that are excluded
   * @var array[]
   */
  private $excludedSessions = array ();

  /**
   * The created tests.
   * @var array
   */
  private $testCases = array ();

  /**
   * This flag indicates if this config file should inherit the pages from its
   * parent.
   *
   * @var bool
   */
  private $inherit = true;

  /**
   * The directory of the yaml file this configuration was created from
   * @var string
   */
  private $baseDir;

  /**
   * The parent configuration. Used to inherit pages.
   * @var TestSuite
   */
  private $parentConfig;

  /**
   *
   * The default domain
   * @var Uri $defaultDomain
   */
  private $defaultDomain = null;

  /**
   * Set the parent config if needed.
   *
   * @param TestSuite $parentConfig
   */
  public function __construct(TestSuite $parentConfig = null)
  {
    $this->parentConfig = $parentConfig;
    $this->includeSession(self::DEFAULT_SESSION, new LiveTestSession())
  }

  /**
   * Sets the base dir. This is needed because some tags need the path to the config
   * entry file.
   *
   * @param string $baseDir
   */
  public function setBaseDir($baseDir)
  {
    $this->baseDir = $baseDir;
  }

  /**
   *
   * sets the base domain
   * @param Uri $domain
   */
  public function setDefaultDomain(Uri $domain)
  {
    $this->defaultDomain = $domain;
  }

  /**
   *
   * gets the base domain
   * @return Uri $defaultDomain
   */
  public function getDefaultDomain()
  {
    return $this->defaultDomain;
  }

  /**
   * Returns the base directory of the config file.
   *
   * @return string
   */
  public function getBaseDir()
  {
    if (is_null($this->baseDir))
    {
      return $this->parentConfig->getBaseDir();
    }
    return $this->baseDir;
  }

  /**
   *
   * sets the current active session
   * @param String $sessionName
   */
  public function setCurrentSession($sessionName)
  {
    if(array_key_exists($sessionName, $this->includedSessions))
    {
      $this->currentSession = $this->includedSessions[$sessionName];
    }
    else
    {
      $this->currentSession = $this->includedSessions[self::DEFAULT_SESSION];
    }
  }

  /**
   * Include an additional page to the config.
   *
   * @param string $page
   */
  public function includeSession(Request $pageRequest)
  {
    $this->includedSessions[$pageRequest->getIdentifier()] = $pageRequest;
  }

  /**
   * Includes an array containing pages to the config.
   *
   * @param array[] $sessions
   */
  public function includeSessions(array $sessions)
  {
    foreach ( $sessions as $aSession )
    {
      $this->includeSession($aSession);
    }

  }

  /**
   * Removes a page from the config.
   *
   * @param string $page
   */
  public function excludeSession(Request $pageRequest)
  {
    $this->excludedSessions[$pageRequest->getIdentifier()] = $pageRequest;
  }

  /**
   * Removes a set of sessions from this config.
   *
   * @param array[] $sessions
   */
  public function excludeSessions($sessions)
  {
    foreach ( $sessions as $aSession )
    {
      $this->excludeSession($aSession);
    }
  }

  /**
   * This function is called if this config should not inherit the pages from its parent.
   */
  public function doNotInherit()
  {
    $this->inherit = false;
  }

  /**
   * This function adds a test to the config and returns a new config connected to the
   * test.
   *
   * @todo we should use the Test class for this
   *
   * @param string $name
   * @param string $className
   * @param array $parameters
   */
  public function createTestCase($name, $className, array $parameters)
  {
    $config = new self($this);

    $this->testCases[] = array ('config' => $config, 'name' => $name, 'className' => $className, 'parameters' => $parameters );

    return $config;
  }

  /**
   * Returns the list of sessions.
   *
   * @return array[]
   */
  public function getSessions()
  {

    if ($this->inherit && !is_null($this->parentConfig))
    {
      $results = array_merge($this->includedSessions, $this->parentConfig->getSessions());
    }
    else
    {
      $results = $this->includedSessions;
    }

    $sessions = $this->getReducedSessions($results, $this->excludedSessions);

    return $sessions;
  }

  private function getReducedSessions(array $includedSessions, array $excludedSessions)
  {
     foreach($excludedSessions as $identifier => $pageRequest)
      {
        if(array_key_exists($identifier, $includedSessions))
        {
          unset($includedSessions[$identifier]);
        }
      }

      return $includedSessions;
  }

  /**
   * Returns the tests.
   *
   * @return array
   */
  public function getTestCases()
  {
    return $this->testCases;
  }
}