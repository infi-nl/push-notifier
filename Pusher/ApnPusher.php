<?php
namespace Infi\PushNotifier\Pusher;

use Infi\PushNotifier\Message\IMessage;
use Infi\PushNotifier\Message\ApnMessage;
use Infi\PushNotifier\Response\GenericResponse;

class ApnPusher implements IPusherStrategy {
    private $_pushEndpoint;
    private $_pemPath;
    private $_passphrase;

    public function __construct($pushEndpoint, $pemPath, $passphrase=null) {
      $this->_pushEndpoint = $pushEndpoint;
      $this->_pemPath      = $pemPath;
      $this->_passphrase   = $passphrase;
    }

    /**
     * @param IMessage $message
     * @return bool
     */
    public function canSendMessage(IMessage $message) {
      return ($message instanceof ApnMessage);
    }

    /**
     * @param ApnMessage $message
     * @return IResponse
     * @throws \Exception
     */
    public function push(ApnMessage $message) {
      // FIXME ED Use curl

      $ctx = stream_context_create();
      stream_context_set_option($ctx, "ssl", "local_cert", $this->_pemPath);

      if ($this->_passphrase) {
          stream_context_set_option($ctx, "ssl", "passphrase", $this->_passphrase);
      }

      $fp = stream_socket_client($this->_pushEndpoint, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
      if (!$fp) {
          throw new \Exception("Failed to connect: $err $errstr");
      }

      $body["aps"] = array(
          "alert" => array(
              "body"           => $message->body,
              "action-loc-key" => $message->actionLocKey,
          ),
          "badge" => $message->badge,
          "sound" => $message->sound,
      );

      $payload = json_encode($body);

      // Build the binary notification
      $msg = chr(0) . pack("n", 32) . pack("H*", $message->deviceToken) . pack("n", strlen($payload)) . $payload;

      $result = fwrite($fp, $msg, strlen($msg));

      fclose($fp);

      $response = new GenericResponse(!!$result);
      
      return $response;
    }
}