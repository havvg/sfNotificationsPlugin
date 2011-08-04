<?php

class PluginNotificationConfigurationValue extends BaseNotificationConfigurationValue
{
  public function __toString()
  {
    return (string) $this->getValue();
  }
}
