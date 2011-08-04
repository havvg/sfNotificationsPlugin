<?php

class SimpleFileNotifier implements Notifier
{
  /**
   * The configured name of the file this Notifier writes into.
   *
   * @var resource
   */
  protected $file;

  /**
   * The bound data to be written to the file.
   *
   * @var mixed
   */
  protected $data;

  /**
   * Close opened file handler, if any.
   *
   * @return void
   */
  public function __destruct()
  {
    if ($this->getFile())
    {
      fclose($this->getFile());
    }
  }

  public function __toString()
  {
    return __CLASS__;
  }

  /**
   * Set up the Notification with the given NotificationConfiguration.
   *
   * @throws InvalidArgumentException
   *
   * @param NotificationConfiguration $configuration
   *
   * @return Notifiy $this
   */
  public function configure(NotificationConfiguration $configuration)
  {
    if (!$configuration->hasAttribute('filename'))
    {
      throw new InvalidArgumentException('The given configuration does not contain the "filename" attribute.');
    }

    return $this->setFile($configuration->getAttribute('filename')->getValue());
  }

  /**
   * Opens a file for appending new notifications to it.
   *
   * @param string $filename
   *
   * @return SimpleFileNotifier $this
   */
  protected function setFile($filename)
  {
    $this->file = fopen(sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename, 'a+');

    return $this;
  }

  /**
   * Returns the opened file handler.
   *
   * @return resource
   */
  protected function getFile()
  {
    return $this->file;
  }

  /**
   * Process the Notification with the bound data.
   *
   * @throws NotificationException
   *
   * @param mixed $dataset
   *
   * @return Notifiy $this
   */
  public function notify($dataset)
  {
    if (!fwrite($this->getFile(), $this->data . "\n"))
    {
      throw new NotificationException(sprintf('The given data set "%s" could not be written.', $this->data));
    }

    return $this;
  }

  /**
   * Binds the given data and validates it.
   *
   * @throws InvalidNotificationDataException
   *
   * @param mixed $data The data being processed for the Notfication.
   *
   * @return Notifiy $this
   */
  public function bindAndValidate($data)
  {
    if (empty($data))
    {
      throw new InvalidNotificationDataException('The given data is empty.');
    }

    $this->data = serialize($data);

    return $this;
  }

  /**
   * Returns the NotificationType to be registered with the sfNotificationsPlugin.
   *
   * @return NotificationType
   */
  public static function getNotificationType()
  {
    $type = new NotificationType();
    return $type->setName('SimpleFile');
  }

  /**
   * Returns all NotificationTypeAttributes to be registered with the sfNotificationsPlugin.
   *
   * @return NotificationTypeAttributeCollection
   */
  public static function getAttributeCollection()
  {
    $filenameAttribute = new NotificationTypeAttribute();
    $filenameAttribute->setName('filename');

    $collection = new NotificationTypeAttributeCollection();
    return $collection->add($filenameAttribute);
  }
}