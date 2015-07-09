<?php
namespace Infi\PushNotifier\Pusher;

use Infi\PushNotifier\Message\IMessage;

interface IPusherStrategy {
  function canSendMessage(IMessage $message);
}