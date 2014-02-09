<?php namespace Softservlet\Notification\Notification;

use Softservlet\Notification\NotificableInterface;

/**
 * @author Vladu Emilian Sorin <vladu@softservlet.com>
 *
 * @version 1.0
 */
interface NotificationInterface
{
	/**
	 * @brief a notification is made by a object and an actor.
	 * The object could be a Photo object and the actor could
	 * be a Like object.
	 *
	 * @return NotificationEntityInterface $object
	 */
	public function getObject();

	/**
	 * @brief a notification is made by a object and an actor.
	 * The object could be a Photo object and the actor could
	 * be a Like object.
	 *
	 * @return NotificationEntityInterface $actor
	 */
	public function getActor();

	/**
	 * @brief each notification has a unique id. 
	 *
	 * @return int $id
	 */
	public function getId();

	/**
	 * @brief mark a notification to be readed/viewed by the user.
	 * By default, when a notification was created, viewed property
	 * is 0.
	 *
	 * @param NotificationInterface $notification
	 *
	 * @return bool
	 */
	public function markViewed(NotificableInterface $user);

}
