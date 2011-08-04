<?php

interface Notifier
{
  /**
   * Returns a string representation (possibly the name) of the Notifier.
   *
   * @return string
   */
  public function __toString();

  /**
   * Set up the Notification with the given NotificationConfiguration.
   *
   * @throws InvalidArgumentException
   *
   * @param NotificationConfiguration $configuration
   *
   * @return Notifiy $this
   */
  public function configure(NotificationConfiguration $configuration);

  /**
   * Process the Notification with the bound data.
   *
   * @throws NotificationException
   *
   * @param mixed $dataset The dataset for the Notification being triggered.
   *
   * @return Notifiy $this
   */
  public function notify($dataset);

  /**
   * Binds the given data and validates it.
   *
   * @throws InvalidNotificationDataException
   *
   * @param mixed $data The data being processed for the Notfication.
   *
   * @return Notifiy $this
   */
  public function bindAndValidate($data);

  /**
   * Returns the NotificationType to be registered with the sfNotificationsPlugin.
   *
   * @return NotificationType
   */
  public static function getNotificationType();

  /**
   * Returns all NotificationTypeAttributes to be registered with the sfNotificationsPlugin.
   *
   * @return NotificationTypeAttributeCollection
   */
  public static function getAttributeCollection();
}