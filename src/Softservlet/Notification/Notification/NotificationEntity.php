<?php namespace Softservlet\Notification\Notification;

/**
 * @author Vladu Emilian Sorin <vladu@softservlet.com>
 *
 * @version 1.0
 */
class NotificationEntity implements NotificationEntityInterface
{
	private $name;
	private $objectId;

	public function __construct($object, $objectId = null)
	{
		if(is_object($object))
		{
			$this->name = get_class($object);
		}
		else
		{
			$this->name = (string) $object;
		}
		$this->objectId = $objectId;
	}

	/**
	 * @brief each entity has a name, usually the class name
	 * of the object
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @brief get the entity id if it's defined, null otherwise
	 *
	 * @return int/null
	 */
	public function getId()
	{
		return $this->objectId;
	}

}
