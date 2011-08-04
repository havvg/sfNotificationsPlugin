<?php

class ValidwithattributesNotifier extends ValidtestNotifier
{
  public static function getNotificationType()
  {
    $type = parent::getNotificationType();
    return $type->setName('Validwithattributes');
  }

  /**
   * Returns all NotificationTypeAttributes to be registered with the sfNotificationsPlugin.
   *
   * @return NotificationTypeAttributeCollection
   */
  public static function getAttributeCollection()
  {
    $collection = new NotificationTypeAttributeCollection();

    $subject = new NotificationTypeAttribute();
    $subject->setName('subject')->setDisplayName('Subject');
    $collection->add($subject);

    $recipient = new NotificationTypeAttribute();
    $recipient->setName('recipient')->setDisplayName('Recipient');
    $collection->add($recipient);

    return $collection;
  }
}