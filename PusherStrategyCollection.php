<?php
namespace Infi\PushNotifier;

use ArrayObject;
use IteratorAggregate;
use Infi\PushNotifier\Pusher\IPusherStrategy;

class PusherStrategyCollection implements IteratorAggregate
{
  private $_strategies;
  private $_count = 0;

  public function __construct($strategies=array()) {
    $this->_strategies = new ArrayObject();

    foreach($strategies as $strategy) {
      $this->add($strategy);
    }
  }

  public function getIterator() {
    return $this->_strategies;
  }

  public function add(IPusherStrategy $strategy) {
    $this->_strategies[$this->_count++] = $strategy;
  }
}