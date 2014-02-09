<?php namespace Softservlet\Notification\Notifier;

interface NotifierInterface
{
	public function notify($notificableArray, $notification);
}
