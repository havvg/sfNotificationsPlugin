<?php

class InvalidtypeNotifier extends ValidtestNotifier
{
  /**
   * Returns an invalid type.
   *
   * @return false
   */
  public static function getNotificationType()
  {
    return false;
  }
}