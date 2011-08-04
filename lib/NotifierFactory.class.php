<?php

class NotifierFactory
{
  /**
   * Returns a Notifier object for the given name.
   *
   * The class name will be the given name (ucfirst) suffixed with "Notifier".
   * The class is required to implement the Notifier interface.
   *
   * @throws InvalidArgumentException
   *
   * @param string $name
   *
   * @return Notifier
   */
  public static function createByName($name)
  {
    $className = ucfirst($name) . 'Notifier';
    if (class_exists($className))
    {
      if (in_array('Notifier', class_implements($className)))
      {
        return new $className();
      }
      else
      {
        throw new InvalidArgumentException(sprintf('The notification class "%s" does not implement the Notifier interface.', $className), 3);
      }
    }
    else
    {
      throw new InvalidArgumentException(sprintf('The notification class "%s" could not be found.', $className), 2);
    }
  }
}