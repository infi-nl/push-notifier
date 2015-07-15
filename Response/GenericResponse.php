<?php
namespace Infi\PushNotifier\Response;

class GenericResponse implements IResponse {
  private $_isSuccessful;

  public function __construct($isSuccessful) {
    $this->_isSuccessful = $isSuccessful;
  }

  public function isSuccessful() {
    return $this->_isSuccessful;
  }
}