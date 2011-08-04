<?php

abstract class PluginNotification extends BaseNotification
{
  /**
   * Sends the Notification with the given data.
   *
   * @throws NotificationException
   *
   * @param mixed $data
   *
   * @return Notification $this
   */
  protected function doNotify($data)
  {
    $name = $this->getNotificationConfiguration()->getNotificationType()->getName();

    try
    {
      $notifier = NotifierFactory::createByName($name);
    }
    catch (InvalidArgumentException $e)
    {
      throw new NotificationException(sprintf('Could not load Notifier "%s".', $name), 1, $e);
    }

    try
    {
      $notifier
        ->configure($this->getNotificationConfiguration())
        ->bindAndValidate($data)
        ->notify($this->getDataset());
    }
    catch (InvalidArgumentException $e)
    {
      throw new NotificationException(sprintf('Error configuring "%s". Error: "%s"', $notifier, $e->getMessage()), 3, $e);
    }
    catch (NotificationException $e)
    {
      throw new NotificationException(sprintf('Could not send Notification "%s" with error "%s".', $notifier, $e->getMessage()), 2, $e);
    }

    return $this;
  }
}
