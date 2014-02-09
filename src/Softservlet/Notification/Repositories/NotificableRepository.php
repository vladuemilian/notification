<?php namespace Softservlet\Notification\Repositories;

use Softservlet\Notification\NotificableInterface;
use Softservlet\Notification\Repositories\NotificationRepositoryInterface;
use Softservlet\Notification\Notification\NotificationInterface;
use DB;

class NotificableRepository implements NotificableRepositoryInterface
{

	public function __construct(NotificableInterface $notificable, NotificationRepositoryInterface $notificationRepository)
	{
		$this->notificable = $notificable;
		$this->notificationRepository = $notificationRepository;
	}

	/**
	 * @brief return an array of notifications
	 *
	 * @param int $limit - the limit of notifications which 
	 * will be returned
	 * @param int $offset - the offset of selection
	 * 
	 * @return array $notifications
	 */
	public function get($limit = 10, $offset = 0)
	{
		$notifications = array();

		$notificationsIds = DB::table('notification_user')
		->where('user_id', $this->notificable->getId())
		->get();

		foreach($notificationsIds as $obj)
		{
			$notifications[] = $this->notificationRepository->find($obj->notification_id);
		}

		return $notifications;	
	}

	/**
	 * @brief return an array of unreaded notification to 
	 * specify user, usually passed within constructor.
	 *
	 * @param int $limit - the limit of notifications which 
	 * will be returned
	 * @param int $offset - the offset of selection
	 * 
	 * @return array $notifications
	 */
	public function getUnreaded($limit = 10, $offset = 0)
	{
		$notifications = array();

		$notificationsIds = DB::table('notification_user')
		->where('user_id', $this->notificable->getId())
		->where('viewed','=', 0)
		->get();

		foreach($notificationsIds as $obj)
		{
			$notifications[] = $this->notificationRepository->find($obj->notification_id);
		}

		return $notifications;	
	}

}
