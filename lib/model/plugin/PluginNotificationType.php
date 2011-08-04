<?php

class PluginNotificationType extends BaseNotificationType
{
  /**
   * Returns all attributes of this type as collection.
   *
   * @return NotificationTypeAttributeCollection
   */
  public function getAttributeCollection()
  {
    $collection = new NotificationTypeAttributeCollection();

    foreach ($this->getNotificationTypeAttributes() as $eachAttribute)
    {
      $collection->add($eachAttribute);
    }

    return $collection;
  }
}
