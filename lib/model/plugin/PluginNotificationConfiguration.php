<?php

class PluginNotificationConfiguration extends BaseNotificationConfiguration
{
  /**
   * Checks whether this NotificationConfiguration has set an attribute with the given name.
   *
   * @throws InvalidArgumentException
   *
   * @param string $name
   *
   * @return bool
   */
  public function hasAttribute($name)
  {
    if (!is_string($name))
    {
      throw new InvalidArgumentException('The given name is invalid.');
    }

    $configurationValues = $this->getNotificationConfigurationValues();
    if (!empty($configurationValues))
    {
      foreach ($configurationValues as $eachConfigurationValue)
      {
        /* @var $eachConfigurationValue NotificationConfigurationValue */
        if ($name === $eachConfigurationValue->getNotificationTypeAttribute()->getName())
        {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Checks whether this NotificationConfiguration has all attributes with the given names.
   *
   * @throws InvalidArgumentException
   *
   * @param array $attributes A list of names to check for.
   *
   * @return bool
   */
  public function hasAttributes(array $attributes)
  {
    foreach ($attributes as $name)
    {
      if (!$this->hasAttribute($name))
      {
        return false;
      }
    }

    return true;
  }

  /**
   * Returns the attribute for the given name.
   *
   * @throws NotificationException
   *
   * @param string $name
   *
   * @return NotificationConfigurationValue
   */
  public function getAttribute($name)
  {
    foreach ($this->getNotificationConfigurationValues() as $eachConfigurationValue)
    {
      /* @var $eachConfigurationValue NotificationConfigurationValue */
      if ($name === $eachConfigurationValue->getNotificationTypeAttribute()->getName())
      {
        return $eachConfigurationValue;
      }
    }

    throw new NotificationException('The given attribute does not exist.', 4);
  }
}
