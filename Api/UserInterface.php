<?php namespace Mrcore\Modules\Wiki\Api;

interface UserInterface
{
	public function id();

	public function uuid();

	public function email();

	public function first();

	public function last();

	public function name();

	public function alias();

	public function avatar();

	public function globalPostID();

	public function homePostID();

	/**
	 * This if user is super admin
	 * @return boolean
	 */
	public function isAdmin();

}