<?php

class sfPHPUnitBaseNotificationsPluginTestCase extends sfPHPUnitBaseTestCase
{
  /**
   * A reference to the data loader.
   *
   * @var sfPropelData
   */
  protected static $loader = null;

  /**
   * Creates the data loader and returns it.
   *
   * @param string $application
   *
   * @return sfPropelData
   */
  protected static function createLoader($application = 'frontend')
  {
    if (is_null(self::$loader))
    {
      new sfDatabaseManager(ProjectConfiguration::getApplicationConfiguration($application, 'test', true));
      self::$loader = new sfPropelData();
    }

    return self::$loader;
  }

  /**
   * Loads fixtures for the sfNotificationsPlugin
   *
   * @param string $application
   * @return void
   */
  protected static function loadFixtures($application = 'frontend')
  {
    self::clearDatabase($application);
    self::createLoader($application)->loadData(self::getPluginFixturesDir());
  }

  /**
   * Removes any entry on the database.
   *
   * @return void
   */
  public static function clearDatabase($application)
  {
    $map = new NotificationTableMap();
    $currentData = self::createLoader($application)->getData('all', 'propel', $map->getPhpName());

    if (!empty($currentData))
    {
      $models = array_reverse(array_keys($currentData));
      foreach ($models as $eachClassName)
      {
        if (!class_exists(constant($eachClassName.'::PEER')))
        {
          throw new InvalidArgumentException(sprintf('Unknown class "%sPeer".', $eachClassName));
        }

        call_user_func(array(constant($eachClassName.'::PEER'), 'doDeleteAll'));
      }
    }
  }

  /**
   * Returns the path to the fixtures directory.
   *
   * @return string
   */
  protected static function getPluginFixturesDir()
  {
    return sfConfig::get('sf_plugins_dir') . DIRECTORY_SEPARATOR . 'sfNotificationsPlugin' . DIRECTORY_SEPARATOR . 'test' . DIRECTORY_SEPARATOR . 'fixtures';
  }
}