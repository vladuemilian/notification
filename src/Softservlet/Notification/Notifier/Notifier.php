<?php namespace Softservlet\Notification\Notifier;

use Softservlet\Notification\Notification\NotificationInterface;
use Softservlet\Notification\NotificableInterface;
use Exception;
use DB;

class Notifier implements NotifierInterface
{
	public function __construct(NotificationRepositoryInterface $repository)
	{
		$this->repository = $repository;
	}

	public function notify($notificableArray, $notification)
	{
		foreach($notificableArray as $notificable)
		{
			if( !($notificable instanceof NotificableInterface) )
			{
				throw new Exception('Notifier exception - notificableArray should contain array of notificable');
			}
			$this->repository->attach($notificable, $notification);
		}
	}
}
