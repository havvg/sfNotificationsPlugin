<?php

class TestConcreteNotification extends Notification
{
  public function notify($data)
  {
    return $this->doNotify($data);
  }
}