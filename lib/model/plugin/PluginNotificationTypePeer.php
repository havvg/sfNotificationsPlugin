<?php

class PluginNotificationTypePeer extends BaseNotificationTypePeer
{
  /**
   * Returns the NotificationType with the given name.
   *
   * @param string $name
   *
   * @return NotificationType
   */
  public static function retrieveByName($name)
  {
    $criteria = new Criteria(self::DATABASE_NAME);
    $criteria->add(self::NAME, $name, Criteria::EQUAL);

    return self::doSelectOne($criteria);
  }
}
