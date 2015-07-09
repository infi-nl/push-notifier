<?php
namespace Infi\PushNotifier;

use Infi\PushNotifier\Message\IMessage;
use Infi\PushNotifier\PusherStrategyCollection;

class PushNotifier
{
  private $_strategies;

  public function __construct(PusherStrategyCollection $strategies)
  {
    $this->_strategies = $strategies;
  }

  public function push(IMessage $message)
  {
    foreach ($this->_strategies as $strategy) {
      if ($strategy->canSendMessage($message)) {
          $strategy->push($message);
          return;
      }
    }

    throw new \Exception("No applicable message strategy found.");
  }
}