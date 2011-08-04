<?php

require_once dirname(__FILE__) . '/../bootstrap/unit.php';

class InstallNotifierTaskTest extends sfPHPUnitBaseNotificationsPluginTestCase
{
  /**
   * A reference to the InstallNotifierTask.
   *
   * @var InstallNotifierTask
   */
  private $task;

  /**
   * Log entries produced by the task.
   *
   * @var array
   */
  private $taskLog = array();

  public static function setUpBeforeClass()
  {
    $fixtureDir = self::getPluginFixturesDir();

    require_once($fixtureDir . '/InvalidinterfaceNotifier.class.php');
    require_once($fixtureDir . '/ValidtestNotifier.class.php');
    require_once($fixtureDir . '/InvalidtypeNotifier.class.php');
    require_once($fixtureDir . '/ValidwithattributesNotifier.class.php');

    self::createLoader();
  }

  protected function _start()
  {
    $formatter = new sfFormatter(80);

    $dispatcher = new sfEventDispatcher();
    $dispatcher->connect('command.log', array($this, "logTask"));

    $this->taskLog = array();
    $this->task = new InstallNotifierTask($dispatcher, $formatter);
  }

  protected function _end()
  {
    $this->deleteNotificationType('Validwithattributes');
    $this->deleteNotificationType('Validtest');
  }

  public function logTask(sfEvent $event)
  {
    $params = $event->getParameters();
    $this->taskLog[] = $params[0];
  }

  protected function getTaskLog()
  {
    return $this->taskLog;
  }

  protected function deleteNotificationType($name)
  {
    if ($type = NotificationTypePeer::retrieveByName($name))
    {
      foreach ($type->getAttributeCollection() as $eachAttribute)
      {
        /* @var $eachAttribute NotificationTypeAttribute */
        $this->assertEquals($eachAttribute->getNotificationType(), $type);

        $eachAttribute->delete();
      }

      $type->delete();
    }

    return $this;
  }

  public function testTaskRequireNameArgument()
  {
    try
    {
      $this->task->run(array(), array('env' => 'test'));
      $this->fail('The task does not require the name argument!');
    }
    catch (sfCommandArgumentsException $e)
    {
      $this->assertStringEndsWith('Not enough arguments.', $e->getMessage());
    }
  }

  public function testNoClass()
  {
    $this->setExpectedException('InvalidArgumentException', 'The notification class "NoclassNotifier" could not be found.', 2);
    $this->task->run(array('notifier' => 'Noclass'), array('env' => 'test'));

    $log = $this->getTaskLog();
    $this->assertStringEndsWith('The Notifier could not be installed.', $log[0]);
  }

  public function testInvalidInterface()
  {
    $this->setExpectedException('InvalidArgumentException', 'The notification class "InvalidinterfaceNotifier" does not implement the Notifier interface.', 3);
    $this->task->run(array('notifier' => 'Invalidinterface'), array('env' => 'test'));

    $log = $this->getTaskLog();
    $this->assertStringEndsWith('The Notifier could not be installed.', $log[0]);
  }

  public function testInvalidtype()
  {
    $this->setExpectedException('InvalidArgumentException', 'The given object is no instance of class "NotificationType"', 12);
    $this->task->run(array('notifier' => 'Invalidtype'), array('env' => 'test'));

    $log = $this->getTaskLog();
    $this->assertStringEndsWith('The Notifier could not be installed.', $log[0]);
  }

  public function testValidWithoutAttributes()
  {
    $this->task->run(array('notifier' => 'Validtest'), array('env' => 'test'));

    $log = $this->getTaskLog();
    $this->assertStringEndsWith('Notifier "ValidtestNotifier" has been installed.', $log[0]);

    $type = NotificationTypePeer::retrieveByName('Validtest');
    $this->assertInstanceOf('NotificationType', $type);
  }

  public function testValidWithAttributes()
  {
    $this->task->run(array('notifier' => 'Validwithattributes'), array('env' => 'test'));

    $log = $this->getTaskLog();
    $this->assertStringEndsWith('Notifier "ValidwithattributesNotifier" has been installed.', $log[0]);

    $type = NotificationTypePeer::retrieveByName('Validwithattributes');
    $this->assertInstanceOf('NotificationType', $type);

    $collection = $type->getAttributeCollection();
    $this->assertInstanceOf('NotificationTypeAttributeCollection', $collection);
    $this->assertEquals(2, count($collection));
  }

  /**
   * @depends testValidWithAttributes
   */
  public function testTypeAlreadyExists()
  {
    $this->task->run(array('notifier' => 'Validwithattributes'), array('env' => 'test'));
    $log = $this->getTaskLog();
    $this->assertStringEndsWith('Notifier "ValidwithattributesNotifier" has been installed.', $log[0]);
    $type = NotificationTypePeer::retrieveByName('Validwithattributes');
    $this->assertInstanceOf('NotificationType', $type);

    $this->setExpectedException('PropelException');
    $this->task->run(array('notifier' => 'Validwithattributes'), array('env' => 'test'));
    $log = $this->getTaskLog();
    $this->assertStringEndsWith('The Notifier could not be installed.', $log[0]);
  }
}