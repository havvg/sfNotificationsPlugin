<?php

class InstallNotifierTask extends sfBaseTask
{
  /**
   * Set up information about this task.
   *
   * @see lib/task/Task#configure()
   *
   * @return void
   */
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
    ));

    parent::configure();

    $this->namespace = 'notifications';
    $this->name = 'install-notifier';
    $this->briefDescription = 'Install a new Notifier';
    $this->detailedDescription = 'This task installs a new Notifier by adding a NotificationType and its NotificationTypeAttributes.';

    $this->addArgument('notifier', sfCommandArgument::REQUIRED, 'The name of the notifier being installed.');
  }

  /**
   * Installs a new Notifier to the sfNotificationsPlugin
   *
   * @param array $arguments The argument passed to the task.
   * @param array $options The options set for this task.
   *
   * @return void
   */
  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    try
    {
      /* @var $class Notifier */
      $class = get_class(NotifierFactory::createByName($arguments['notifier']));
    }
    catch (InvalidArgumentException $e)
    {
      $this->logSection($this->namespace, sprintf('The Notifier implementation of "%s" could not be found.', $arguments['notifier']));
      throw $e;
    }

    try
    {
      $notificationType = $class::getNotificationType();
      $notificationAttributes = $class::getAttributeCollection();

      if (!($notificationType instanceof NotificationType))
      {
        throw new InvalidArgumentException('The given object is no instance of class "NotificationType"', 12);
      }

      if (!($notificationAttributes instanceof NotificationTypeAttributeCollection))
      {
        throw new InvalidArgumentException('The given object is no instance of "NotificationTypeAttributeCollection', 13);
      }
    }
    catch (InvalidArgumentException $e)
    {
      $this->logSection($this->namespace, 'The Notifier could not be installed.');
      $this->logSection($this->namespace, $e->getMessage());

      throw $e;
    }

    try
    {
      foreach ($notificationAttributes as $eachAttribute)
      {
        $notificationType->addNotificationTypeAttribute($eachAttribute);
      }

      $notificationType->save();
      $this->logSection($this->namespace, sprintf('Notifier "%s" has been installed.', $class));
    }
    catch (PropelException $e)
    {
      $this->logSection($this->namespace, 'The Notifier could not be installed.');
      $this->logSection($this->namespace, $e->getMessage());

      throw $e;
    }
  }
}