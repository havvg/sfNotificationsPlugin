<?php

class ValidtestNotifier implements Notifier
{
  /**
   * Returns the name of the class of this Notifier.
   *
   * @return string
   */
  public function __toString()
  {
    return __CLASS__;
  }

  /**
   * Set up the Notification with the given NotificationConfiguration.
   *
   * @throws InvalidArgumentException
   *
   * @param NotificationConfiguration $configuration
   *
   * @return Notifiy $this
   */
  public function configure(NotificationConfiguration $configuration)
  {
    return $this;
  }

  /**
   * Process the Notification with the bound data.
   *
   * @throws NotificationException
   *
   * @param mixed $dataset
   *
   * @return Notifiy $this
   */
  public function notify($dataset)
  {
    return $this;
  }

  /**
   * Binds the given data and validates it.
   *
   * @throws InvalidNotificationDataException
   *
   * @param mixed $data The data being processed for the Notfication.
   *
   * @return Notifiy $this
   */
  public function bindAndValidate($data)
  {
    return $this;
  }

  /**
   * Returns the NotificationType to be registered with the sfNotificationsPlugin.
   *
   * @return NotificationType
   */
  public static function getNotificationType()
  {
    $type = new NotificationType();
    return $type->setName('Validtest');
  }

  /**
   * Returns all NotificationTypeAttributes to be registered with the sfNotificationsPlugin.
   *
   * @return NotificationTypeAttributeCollection
   */
  public static function getAttributeCollection()
  {
    return new NotificationTypeAttributeCollection();
  }
}