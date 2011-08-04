<?php

class TestConcreteNotificationPeer extends NotificationPeer
{
  public static function getOMClass($withPrefix = true)
  {
    return 'TestConcreteNotification';
  }
}