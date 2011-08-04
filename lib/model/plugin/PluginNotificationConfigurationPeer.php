<?php

class PluginNotificationConfigurationPeer extends BaseNotificationConfigurationPeer
{
  /**
   * Returns a list of NotificationConfiguration created by the given user.
   *
   * @param sfGuardUser $user
   *
   * @return array of NotificationConfiguration
   */
  public static function retrieveByUser(sfGuardUser $user)
  {
    $criteria = new Criteria(self::DATABASE_NAME);
    $criteria->add(self::USER_ID, $user->getId(), Criteria::EQUAL);

    return self::doSelect($criteria);
  }
}
