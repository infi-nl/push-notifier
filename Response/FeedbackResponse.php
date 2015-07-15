<?php
namespace Infi\PushNotifier\Response;

class FeedbackResponse implements IResponse {
  private $_tokens;

  public function __construct($tokens) {
    $this->_tokens = $tokens;
  }

  public function getTokens() {
    return $this->_tokens;
  }
}