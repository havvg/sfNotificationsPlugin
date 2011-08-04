<?php

class NotificationTest extends sfPHPUnitBaseNotificationsPluginTestCase
{
  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();

    require_once(self::getPluginFixturesDir() . '/SimpleFileNotifier.class.php');
    require_once(self::getPluginFixturesDir() . '/TestConcreteNotification.class.php');
    require_once(self::getPluginFixturesDir() . '/TestConcreteNotificationPeer.class.php');

    self::loadFixtures();
  }

  public static function tearDownAfterClass()
  {
    parent::tearDownAfterClass();

    NotificationConfigurationValuePeer::doDeleteAll();
    NotificationConfigurationPeer::doDeleteAll();
    NotificationTypeAttributePeer::doDeleteAll();
    NotificationTypePeer::doDeleteAll();
    sfGuardUserPeer::doDeleteAll();
  }

  public function testNotifierExists()
  {
    $notifier = NotifierFactory::createByName('SimpleFile');
    $this->assertInstanceOf('SimpleFileNotifier', $notifier);
  }

  public function testConcreteNotificationExists()
  {
    $notification = new TestConcreteNotification();
    $this->assertInstanceOf('TestConcreteNotification', $notification);
  }

  /**
	 * @depends testNotifierExists
	 * @depends testConcreteNotificationExists
   */
  public function testNotify()
  {
    /* @var $user sfGuardUser */
    $user = sfGuardUserPeer::retrieveByUsername('Username');
    $this->assertInstanceOf('sfGuardUser', $user);

    /* @var $type NotificationType */
    $type = NotificationTypePeer::retrieveByName('SimpleFile');
    $this->assertInstanceOf('NotificationType', $type);

    $criteria = new Criteria(NotificationConfigurationPeer::DATABASE_NAME);
    $criteria->add(NotificationConfigurationPeer::NAME, 'Sample Configuration for SimpleFile');
    $criteria->add(NotificationConfigurationPeer::NOTIFICATION_TYPE_ID, $type->getId());
    $criteria->add(NotificationConfigurationPeer::USER_ID, $user->getId());

    /* @var $configuration NotificationConfiguration */
    $configuration = NotificationConfigurationPeer::doSelectOne($criteria);
    $this->assertInstanceOf('NotificationConfiguration', $configuration);
    $this->assertTrue($configuration->hasAttribute('filename'));

    $notification = new TestConcreteNotification();
    $notification->setNotificationConfiguration($configuration);

    $data = array('Simple array', 'to put into a file.');
    $notification->notify($data);

    $filename = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'simple_notifications.log';
    $content = file($filename);

    $this->assertEquals($data, unserialize($content[0]));
    unlink($filename);
  }
}