Publishing notifies is done by NotificationPublisher class.

As arguments, it takes simple Notification object and an array of Users.

You can configure NotificationPublisher in services.yaml to send notifications via different channels, one notification can be send by sms, push and email or any other configuration (only sms, sms and push, push and email, etc...)

Notification messages are put in a queue with RabbitMQ service (config/packages/messenger.yaml). Every notify channel has it own transport to have flexibility in handling messages (in batches, with time throttling) and handling it asynchronously. NotificationPublisher doesn't have to wait until every notification is handled (http calls can be time-consuming)

Consuming RabbitMQ messages can be done with various services like crontab, systemd, supervisorctl (bin/console messenger:consume) to provide throttling. For example, you can set limit to consumer and consume only 300 messages an hour (or any other time-interval, 5 messages every minute etc), or setup consumer to consume messages immediately.

When needed you can expand publisher and add few different queues for one channel with different consuming strategies, and expand Notification class with some type of handling configuration, when for example you need to send some sms notification immediately and other sms notification with throttling.

For every channel there are message handlers (consumers) which can have multiple providers (see SmsNotificationCommandHandler). For every handler you can add providers in configuration (in priority). When provider succeed in sending notification, message is considered as consumed successfully and no other provider does anything, but when provider fails, next one try to send notification. When every provider fails, message is delayed and consumed again (see config/messenger.yaml for retry_strategy), after 5 failed tries it goes to failed transport.

Usage tracking is done by logging to files (see config/packages/monolog.yaml), but it can be easily expanded to log aggregators or database logging. To see logs visit var/logs catalog.

I used few notification services (Twilio, Vonage, Pushy, Mailtrap) but unfortunately didn't have time to set up an account, fully configure every one of them and test it in testing environment, so they might not work, but I tried to be consistent with documentation they provide and hopefully configured it right. You can edit .env file and provide your own api keys.

Also run out of time to do integration testing, I've done just a scratch to show what it would look like.
