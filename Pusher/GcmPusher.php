<?php
namespace Infi\PushNotifier\Pusher;

use Infi\PushNotifier\Response\GenericResponse;
use Infi\PushNotifier\Message\IMessage;
use Infi\PushNotifier\Message\GcmMessage;

class GcmPusher implements IPusherStrategy {
  private $_pushEndpoint;
  private $_apiAccessKey;

  public function __construct($pushEndpoint, $apiAccessKey) {
    $this->_pushEndpoint = $pushEndpoint;
    $this->_apiAccessKey = $apiAccessKey;
  }

  /**
   * @param IMessage $message
   * @return bool
   */
  public function canSendMessage(IMessage $message) {
    return ($message instanceof GcmMessage);
  }

  /**
   * @param GcmMessage $message
   * @return IResponse
   * @throws \Exception
   */
  public function push(GcmMessage $message) {
    $fields = array(
      "registration_ids" 	=> $message->registrationIds,
      "data"			        => $message->getJsonData()
    );

    $headers = array(
      sprintf("Authorization: key=%s", $this->_apiAccessKey),
      "Content-Type: application/json"
    );

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $this->_pushEndpoint);
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response = new GenericResponse($httpCode === 200);

    return $response;
  }
}