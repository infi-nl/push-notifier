# Push Notifier
PHP library for sending push notifications to multiple device types.

## Apple Push Notification example ##

Prepare the PushNotifier for sending Apple Push Notifications. Note that you can provide your own Pusher-strategies, to send notifications for Android and Windows Phone for example.

```php
$pushNotifier = new PushNotifier(
  new PusherStrategyCollection(array(
      new ApnPusher(
          'ssl://gateway.sandbox.push.apple.com:2195',
          '<path-to-pem-file>'
      )
  ))
);
```

Sending an Apple Push Notification
```php
$message              = new ApnMessage();
$message->body        = "<my message>";
$message->badge       = 0;
$message->deviceToken = "<device token>";

$pushNotifier->push($message);
```
