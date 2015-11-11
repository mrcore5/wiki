<?php namespace Mrcore\Wiki\Api;

/**
 * This is the main mrcore API interface
 * used by all "wiki app/workbench" developers
 */
interface MrcoreInterface
{

	public function post();

	public function router();

	public function user();

	public function layout();

	public function config();

	public function getInstance();

	public function lifecycle();

}