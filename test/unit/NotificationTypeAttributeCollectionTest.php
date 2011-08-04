<?php

require_once dirname(__FILE__) . '/../bootstrap/unit.php';

class NotificationTypeAttributeCollectionTest extends sfPHPUnitBaseNotificationsPluginTestCase
{
  /**
   * A reference to a attribute collection.
   *
   * @var NotificationAttributeCollection
   */
  private $collection;

  protected function _start()
  {
    $this->collection = new NotificationTypeAttributeCollection();
  }

  protected function _end()
  {
    $this->collection = null;
  }

  public function testCountEmpty()
  {
    $this->assertEquals(0, count($this->collection));
  }

  public function testAddInvalid()
  {
    $this->setExpectedException('PHPUnit_Framework_Error');
    $this->collection->add('String');
  }

  public function testAddAndRemoveSingleAttribute()
  {
    $attribute = new NotificationTypeAttribute();
    $attribute->setName('First');

    $index = $this->collection->add($attribute);
    $this->assertEquals(0, $index);
    $this->assertEquals(1, count($this->collection));

    $this->collection->remove($index);
    $this->assertEquals(0, count($this->collection));
  }

  /**
   * @depends testAddAndRemoveSingleAttribute
   */
  public function testRemoveInvalidIndex()
  {
    $attribute = new NotificationTypeAttribute();
    $attribute->setName('First');
    $index = $this->collection->add($attribute);
    $this->assertEquals(0, $index);

    $this->setExpectedException('InvalidArgumentException', null, 11);
    $this->collection->remove(12);

    $this->setExpectedException('InvalidArgumentException', null, 11);
    $this->collection->remove('abc');

    $this->setExpectedException('InvalidArgumentException', null, 11);
    $this->collection->remove(-5);
  }

  /**
   * @depends testAddAndRemoveSingleAttribute
   */
  public function testAddTwoRemoveByIndex()
  {
    $attribute1 = new NotificationTypeAttribute();
    $attribute1->setName('First');
    $index1 = $this->collection->add($attribute1);

    $attribute2 = new NotificationTypeAttribute();
    $attribute2->setName('Second');
    $index2 = $this->collection->add($attribute2);

    $this->assertEquals(0, $index1);
    $this->assertEquals(1, $index2);
    $this->assertEquals(2, count($this->collection));

    $this->collection->remove($index1);
    $this->assertEquals(1, count($this->collection));

    $this->collection->remove($index2);
    $this->assertEquals(0, count($this->collection));
  }

  /**
   * @depends testAddTwoRemoveByIndex
   */
  public function testAddTwoIterator()
  {
    $attribute1 = new NotificationTypeAttribute();
    $attribute1->setName('First');
    $index1 = $this->collection->add($attribute1);

    $attribute2 = new NotificationTypeAttribute();
    $attribute2->setName('Second');
    $index2 = $this->collection->add($attribute2);

    $expectation = array(
      0 => 'First',
      1 => 'Second',
    );

    $c = 0;
    foreach ($this->collection as $attribute)
    {
      /* @var $attribute NotificationTypeAttribute */
      $this->assertEquals($expectation[$c], $attribute->getName());
      $c++;
    }

    $this->assertEquals(2, $c, 'Not all elements have been checked.');
  }

  /**
   * @depends testAddTwoIterator
   */
  public function testAddThreeRemoveIntermediateIterator()
  {
    $attribute1 = new NotificationTypeAttribute();
    $attribute1->setName('First');
    $index1 = $this->collection->add($attribute1);

    $attribute2 = new NotificationTypeAttribute();
    $attribute2->setName('Second');
    $index2 = $this->collection->add($attribute2);

    $attribute3 = new NotificationTypeAttribute();
    $attribute3->setName('Third');
    $index3 = $this->collection->add($attribute3);

    $this->collection->remove($index2);
    $this->assertEquals(2, count($this->collection));

    $expectation = array(
      0 => 'First',
      1 => 'Third',
    );

    $c = 0;
    foreach ($this->collection as $attribute)
    {
      /* @var $attribute NotificationTypeAttribute */
      $this->assertEquals($expectation[$c], $attribute->getName());
      $c++;
    }

    $this->assertEquals(2, $c, 'Not all elements have been checked.');
  }
}