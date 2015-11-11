<?php namespace Mrcore\Wiki\Api;

interface PostInterface
{
	public function id();

	public function uuid();

	public function title();

	public function slug();

	public function content();

	public function workbench();

	public function formatID();

	public function formatConstant();

	public function clicks();

	public function prepare();

	/**
	 * Check if user has this permission item to this post
	 * @param  string  $constant
	 * @return boolean
	 */
	public function hasPermission($constant);
}
