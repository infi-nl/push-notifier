<?php
namespace Infi\PushNotifier\Feedback;

use Infi\PushNotifier\Message\IMessage;
use Infi\PushNotifier\Response\FeedbackResponse;

abstract class ApnFeedbackEndpoint
{
    const SANDBOX = "feedback.sandbox.push.apple.com:2196";
    const PRODUCTION = "feedback.push.apple.com:2196";
}

class ApnFeedback {
    private $_endpoint;
    private $_pemPath;
    private $_passphrase;

    public function __construct($endpoint, $pemPath, $passphrase=null) {
      $this->_endpoint = $endpoint;
      $this->_pemPath      = $pemPath;
      $this->_passphrase   = $passphrase;
    }

    /**
     * @return IResponse
     * @throws \Exception
     */
    public function get() {
      // FIXME ED Use curl

      $ctx = stream_context_create();
      stream_context_set_option($ctx, "ssl", "local_cert", $this->_pemPath);

      if ($this->_passphrase) {
          stream_context_set_option($ctx, "ssl", "passphrase", $this->_passphrase);
      }

      $fp = stream_socket_client($this->_endpoint, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
      if (!$fp) {
          throw new \Exception("Failed to connect: $err $errstr");
      }

      $feedbackTokens = array();

      while(!feof($fp)) {
        $data = fread($fp, 38);
        
        if(strlen($data)) {
          $feedbackTokens[] = unpack("N1timestamp/n1length/H*devtoken", $data);
        }
      }

      fclose($fp);

      $response = new FeedbackResponse($feedbackTokens);
      
      return $response;
    }
}