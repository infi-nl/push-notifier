<?php
namespace Infi\PushNotifier\Message;

class ApnMessage implements IMessage {
  public $badge;
  public $body;
  public $sound;
  public $actionLocKey;
  public $deviceToken;
  
  public $custom = array();
}