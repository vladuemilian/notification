<?php namespace Softservlet\Notification\Resolver;

use Exception;
use Softservlet\Notification\Notification\NotificationInterface;

/**
 * @author Vladu Emilian Sorin <vladu@softservlet.com>
 *
 * @version 1.0
 */

/*
 * @brief this class will take as parameter a mapper which will respects
 * the following standard. Based on notifications array passed to resolver
 * parameter the single or group callback will executed and the content
 * will be returned.
 *
 *		$mapper = array
 *		(
 *			array
 *			(
 *				'object'	=> 'Softservlet\Friendship\Laravel\FriendshipEloquent',
 *				'actor'		=> 'Friends\User\User',
 *				'single'	=> 'App\Notification\TestNotification@photoLiked',
 *				'group'		=> 'App\Notification\TestNotification@photoMultipleLiked'
 *			),
 *			array
 *			(
 *				'object'	=> 'App\Album',
 *				'actor'		=> 'App\Action\Dislike',
 *				'single'	=> 'App\Notification\TestNotification@albumDisliked'
 *			),
 *			array
 *			(
 *				'object'	=> 'stdClass',
 *				'actor'		=> 'Friends\User\User',
 *				'single'	=> 'App\Notification\TestNotification@stdTest'
 *			)
 *		);
 *
 */

class Resolver implements ResolverInterface
{
	const CALLBACK_GROUP = 'group';
	const CALLBACK_SINGLE = 'single';

	public function __construct($mapper)
	{
		$this->mapper = $mapper;
	}

	/**
	 * @brief the method should resolve the array
	 * of notifications to a specified notification 
	 * controller.
	 *
	 * @return array
	 */
	public function resolve($notifications)
	{
		$response = $this->recursiveResolver($notifications);
		return $response;
	}

	/**
	 * @brief recursive resolver for notifications. it will call the
	 * recursive route maps if there exists one
	 *
	 * @param $notifications
	 *
	 * @return $response
	 */
	private function recursiveResolver($notifications, $response = array())
	{
		if(!empty($notifications))
		{
			$notification = reset($notifications);
			if( !($notification instanceof NotificationInterface) )
			{
				throw new Exception('Resolver array of notification must implement NotificationInterface');
			}
			$map = $this->mapExists($notification);

			//initialize a counter of how many notifications for each mapper
			$notificationCounter = 0;

			//check if there was found a mapper for this notification
			if(is_int($map))
			{
				//count how many identically notifications has been found in the array
				//if the map has CALLBACK_GROUP option and the specify notification
				//has been found more than once, then we execute CALLBACK_GROUP callback.
				$notificationCounter = $this->counter($notifications, $notification);
				
				//todo - rewrite this spaggeti code expression 
				if(isset($this->mapper[$map][self::CALLBACK_GROUP]) && $notificationCounter>1) 
				{
					$param = $this->_pruneNotifications($notifications, $notification);
					if(is_callable($this->mapper[$map][self::CALLBACK_GROUP]))
					{
						$response[] = call_user_func($this->mapper[$map][self::CALLBACK_GROUP], $param);
					}
					else
					{
						$response[] = $this->mapControllerResolver($this->mapper[$map], $param, self::CALLBACK_GROUP);
					}
				}
				else
				{
					if(is_callable($this->mapper[$map][self::CALLBACK_GROUP]))
					{
						$response[] = call_user_func($this->mapper[$map][self::CALLBACK_GROUP], $param);
					}
					else
					{
						$response[] = $this->mapControllerResolver($this->mapper[$map], $notification, self::CALLBACK_SINGLE);
					}
				}
			}
			
			//check if the current map has CALLBACK_GROUP defined
			//then check if the current notification has been found more than
			//once in the array of notifications. If yes, remove all notifications.
			if(isset($this->mapper[$map][self::CALLBACK_GROUP]) && $notificationCounter>1)
			{
				$notifications = $this->mapRemoveAll($notifications, $notification);
			}
			else
			{
				$notifications = $this->mapRemove($notifications, $notification);
			}
			
			//recursive resolve the notifications array	
			return $this->recursiveResolver($notifications, $response);
		}
		return $response;
	}

	/**
	 * @brief check if the specify notification has a defined route map in mapper
	 * array.
	 *
	 * @param NotificationInterface $notification
	 *
	 * @return int $key - the array key of the map, bool false if there not exists
	 * a map for this notification
	 */
	private function mapExists(NotificationInterface $notification)
	{	
		foreach($this->mapper as $key => $map)
		{
			if($map['object'] == $notification->getObject()->getName() &&
			$map['actor'] == $notification->getActor()->getName())
			{
				return $key;
			}
		}
		return false;
	}

	/**
	 * @brief count how many notifications of the same type exists in $notifications
	 * array.
	 *
	 * @param array $notifications - an array of NotificationInterface objects
	 * @param NotificationInterface $notification
	 *
	 * @return int 
	 */
	private function counter($notifications, NotificationInterface $notification)
	{
		$i = 0;
		foreach($notifications as $obj)
		{
			if($obj->getObject()->getName() == $notification->getObject()->getName() &&
			$obj->getActor()->getName() == $notification->getActor()->getName())
			{
				$i++;
			}
		}
		return $i;
	}

	/**
	 * @brief take an array of NotificationInterface and a NotificationInterface as
	 * parameters. It will remove the first found NotificationInterface in array and
	 * returns the array.
	 *
	 * @param array $notifications
	 * @param NotificationInterface $notification
	 * 
	 * @return array $notifications
	 */
	private function mapRemove($notifications, NotificationInterface $notification)
	{
		foreach($notifications as $key => $iterator)
		{
			if($iterator->getObject()->getName() == $notification->getObject()->getName()
			&& $iterator->getActor()->getName() == $notification->getActor()->getName())
			{
				unset($notifications[$key]);
				return $notifications;
			}
		}
		return $notifications;
	}

	/**
	 * @brief take an array of NotificationInterface and a NotificationInterface as
	 * parameters. It will remove all NotificationInterface object from the array
	 * and returns it.
	 *
	 * @param array $notifications
	 * @param NotificationInterface $notification
	 *
	 * @return array $notifications
	 */
	private function mapRemoveAll($notifications, NotificationInterface $notification)
	{
		$result = $notifications;
		foreach($notifications as $key => $iterator)
		{
			if($iterator->getObject()->getName() == $notification->getObject()->getName()
			&& $iterator->getActor()->getName() == $notification->getActor()->getName())
			{
				unset($result[$key]);
				$result;
			}
		}
		return $result;
	}

	/**
	 * @brief call the map callback(depends by $callbackType) and returns it 
	 *
	 * @param $map - a single entry from $mapper array
	 * @param $param - it can be an array of $notifications or just a single
	 * notification of NotificationInterface type
	 * @param $callbackType - are constants for make the difference between
	 * CALLBACK_SINGLE and CALLBACK_GROUP
	 *
	 * @return string 
	 */
	private function mapControllerResolver($map, $param, $callbackType)
	{
		try
		{
			$callbackData = explode('@', $map[$callbackType]);
			$object = new $callbackData[0];
			return $object->$callbackData[1]($param);
		}
		catch(Exception $e)
		{
			echo 'Notification resolver, can\'t resolve. '.  PHP_EOL;
			echo $e->getMessage() . PHP_EOL;
		}
	}

	/**
	 * @brief loop through $notifications and remove each NotificationInterface
	 * $notification, then return the $notfications array
	 *
	 * @param array $notifications 
	 * @param NotificationInterface $notification
	 *
	 * @return array $notifications
	 */
	private function _pruneNotifications($notifications, NotificationInterface $notification)
	{
		$result = array();
		foreach($notifications as $obj)
		{
			if($obj->getObject()->getName() == $notification->getObject()->getName() &&
			$obj->getActor()->getName() == $notification->getActor()->getName())
			{
				$result[] = $obj;
			}
		}
		return $result;
	}

}
