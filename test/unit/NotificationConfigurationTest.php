<?php

class NotificationConfigurationTest extends sfPHPUnitBaseNotificationsPluginTestCase
{
  /**
   * A reference to the NotificationConfiguration.
   *
   * @var NotificationConfiguration
   */
  protected $configuration;

  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();

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

  protected function _start()
  {
    $criteria = new Criteria(NotificationConfigurationPeer::DATABASE_NAME);
    $criteria->add(NotificationConfigurationPeer::NAME, 'Sample Configuration for SimpleFile');

    $this->configuration = NotificationConfigurationPeer::doSelectOne($criteria);
  }

  public function testRetrieve()
  {
    $this->assertInstanceOf('NotificationConfiguration', $this->configuration);
  }

  /**
   * @depends testRetrieve
   */
  public function testHasAttributeInvalid()
  {
    $this->setExpectedException('InvalidArgumentException');
    $this->configuration->hasAttribute(5);

    $this->assertFalse($this->configuration->hasAttribute('no attribute'));
  }

  /**
   * @depends testHasAttributeInvalid
   */
  public function testHasAttributesInvalid()
  {
    $this->setExpectedException('InvalidArgumentException');
    $this->assertTrue($this->configuration->hasAttributes(array('filename', 5)));

    $this->assertFalse($this->configuration->hasAttributes(array('filename', 'no attribute')));
  }

  /**
   * @depends testRetrieve
   */
  public function testHasAttributeValid()
  {
    $this->assertTrue($this->configuration->hasAttribute('filename'));
  }

  /**
   * @depends testHasAttributeValid
   */
  public function testHasAttributesValid()
  {
    $this->assertTrue($this->configuration->hasAttributes(array('filename')));
  }

  /**
   * @depends testRetrieve
   */
  public function testGetAttributeInvalid()
  {
    $this->setExpectedException('NotificationException');
    $this->configuration->getAttribute('no attribute');

    $this->setExpectedException('NotificationException');
    $this->configuration->getAttribute(5);
  }

  /**
   * @depends testRetrieve
   */
  public function testGetAttributeValid()
  {
    $this->assertEquals('simple_notifications.log', $this->configuration->getAttribute('filename')->getValue());
  }
}