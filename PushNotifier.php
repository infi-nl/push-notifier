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

  /**
   * @param IMessage $message
   * @return IResponse
   * @throws \Exception
   */
  public function push(IMessage $message)
  {
    foreach ($this->_strategies as $strategy) {
      if ($strategy->canSendMessage($message)) {
        return $strategy->push($message);
      }
    }

    throw new \Exception("No applicable message strategy found.");
  }
}