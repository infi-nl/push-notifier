<?php
namespace Infi\PushNotifier\Message;

class GcmMessage implements IMessage {
	public $message;
	public $title;
	public $subtitle;
	public $tickerText;
	public $vibrate;
	public $sound;
	public $largeIcon;
	public $smallIcon;
  public $registrationIds = array();

  public function getJsonData() {
    $data = array();

    foreach (array("message", "title", "subtitle", "tickerText", "vibrate", "sound", "largeIcon", "smallIcon") as $field) {
      $value = $this->$field;
      if (!$value) {
        continue;
      }

      $data[$field] = $value;
    }

    return $data;
  }
}
