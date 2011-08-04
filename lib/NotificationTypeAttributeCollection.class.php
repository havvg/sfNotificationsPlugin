<?php

class NotificationTypeAttributeCollection implements Iterator, Countable
{
  /**
   * Internal position of the attributes array.
   *
   * For Iterator interface.
	 *
   * @var int
   */
  private $position = 0;

  /**
   * The list of collected NotificationTypeAttributes.
   *
   * @var array of NotificationTypeAttribute
   */
  private $attributes = array();

  /**
   * An internal counter of valid indexes.
   *
   * Required to add removal of attributes while remaining Iterator interface.
   * This counter will never be decreased. So backward indexes won't be overwritten by future add() calls.
   *
   * @var int
   */
  private $index = -1;

  /**
   * Add another NotificationAttribute to the collection.
   *
   * @param NotificationTypeAttribute $attribute
   *
   * @return int A reference index of the new entry.
   */
  public function add(NotificationTypeAttribute $attribute)
  {
    $index = ++$this->index;
    $this->attributes[$index] = $attribute;

    return $index;
  }

  /**
   * Removes an attribute by its given index.
   *
   * @throws InvalidArgumentException
   *
   * @param int $index The index retrieved from adding the attribute before.
   *
   * @return NotificationTypeAttributeCollection $this
   */
  public function remove($index)
  {
    if (!is_int($index) || empty($this->attributes[$index]))
    {
      throw new InvalidArgumentException(sprintf('The given index "%d" is not valid.', $index), 11);
    }

    unset($this->attributes[$index]);

    return $this;
  }

  // Countable implementation

  public function count()
  {
    return count($this->attributes);
  }

  // Iterator implementations

  public function rewind()
  {
    $this->position = 0;
  }

  public function current()
  {
    return $this->attributes[$this->position];
  }

  public function key()
  {
    return $this->position;
  }

  public function next()
  {
    do
    {
      ++$this->position;
    }
    while (!$this->valid() and $this->position <= $this->index);
  }

  public function valid()
  {
    return isset($this->attributes[$this->position]);
  }
}