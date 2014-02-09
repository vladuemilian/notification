<?php namespace Softservlet\Notification\Repositories;

use Softservlet\Notification\Notification\NotificationEntityInterface;
use Softservlet\Notification\NotificableInterface;
use Softservlet\Notification\Notification\NotificationInterface;
use DB;
use App;

class NotificationRepository implements NotificationRepositoryInterface
{
	/**
	 * @brief Create a Notification object and return it. 
	 *
	 * @param NotificationEntityInterface $object - the object of which notification will be made
	 * for example, it can be a Photo
	 * @param NotificationEntityInterface $actor - the actor of the notification - for example it
	 * can be a Like object.
	 *
	 * @return NotificationInterface
	 */
	public function create(NotificationEntityInterface $object, NotificationEntityInterface $actor)
	{
		//check if there is already a notification to this object
		$objectId = DB::table('notification_object')->insertGetId
		(
			array
			(
				'object'	=> $object->getName(),
				'object_id'	=> $object->getId()
			)
		);
		$actorId = DB::table('notification_actor')->insertGetId
		(
			array
			(
				'actor'		=> $actor->getName(),
				'actor_id'	=> $actor->getId()
			)
		);
		$created = time();
		$notificationId = DB::table('notification')->insertGetId
		(
			array
			(
				'object_id'	=> $objectId,
				'actor_id'	=> $actorId,
				'created'	=> $created 
			)
		);

		$notification = App::make('Softservlet\Notification\Notification\NotificationInterface', array($object, $actor, $notificationId));
		$notification->created = $created;

		return $notification;
	}

	/**
	 * @brief Return a Notification object based on $id
	 *
	 * @param int $id 
	 *
	 * @return NotificationInterface
	 */
	public function find($id)
	{
		$queryObject = DB::table('notification')
		->where('notification.id', $id)
		->join('notification_object', 'notification_object.id', '=', 'notification.object_id')
		->join('notification_actor', 'notification_actor.id', '=', 'notification.actor_id')
		->first();

		if($queryObject==null)
		{
			return null;
		}

		$object = App::make('Softservlet\Notification\Notification\NotificationEntityInterface', array($queryObject->object, $queryObject->object_id));
		$actor = App::make('Softservlet\Notification\Notification\NotificationEntityInterface', array($queryObject->actor, $queryObject->actor_id));
		$notification = App::make('Softservlet\Notification\Notification\NotificationInterface', array($object, $actor, $queryObject->id));
		$notification->created = $queryObject->created;

		return $notification;
	}

	/**
	 * @brief Attach a Notification to a Notificable object, usually to a user.
	 *
	 * @param NotificableInterface $notificable
	 * @param NotificationInterface $notification
	 *
	 * @return bool 
	 */
	public function attach(NotificableInterface $user, NotificationInterface $notification)
	{
		DB::table('notification_user')->insert
		(
			array
			(
				'user_id'	=> $user->getId(),
				'notification_id'	=> $notification->getId()
			)
		);
	}

	/**
	 * @brief Remove a notification
	 *
	 * @param NotificationInterface $notification
	 * 
	 * @return bool
	 */
	public function remove(NotificationInterface $notification)
	{
		return (bool) DB::table('notification')->where('id', $notification->getId())->delete();
	}

}
