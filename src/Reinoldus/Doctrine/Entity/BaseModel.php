<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 10/7/14
 * Time: 10:23 AM
 */

namespace Reinoldus\Doctrine\Model;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseEntity {
	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue()
	 */
	protected $id;

	/**
	 * @var \DateTime $modified
	 * @ORM\Column(type="datetime")
	 */
	protected $modified;

	/**
	 * @var \DateTime $created
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist() {
		$this->created = new \DateTime('now');
		$this->modified = new \DateTime('now');
	}

	/**
	 * @param \DateTime $created
	 * @return $this
	 */
	public function setCreated(\DateTime $created)
	{
		$this->created = $created;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated()
	{
		return $this->created;
	}

	/**
	 * @param \DateTime $modified
	 */
	public function setModified(\DateTime $modified)
	{
		$this->modified = $modified;
	}

	/**
	 * @return \DateTime
	 */
	public function getModified()
	{
		return $this->modified;
	}

	public function getAttributes() {
		return array_keys(get_class_vars(get_class($this)));
	}
} 