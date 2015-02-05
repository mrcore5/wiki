<?php

// Order is Critical
$this->call('WikiPostItemsSeeder');
$this->call('WikiPostSeeder');
$this->call('WikiRoleSeeder');
$this->call('WikiPermissionSeeder');
$this->call('WikiUserPermissionSeeder');
$this->call('WikiPostPermissionSeeder');
$this->call('WikiBadgeSeeder');
$this->call('WikiTagSeeder');
$this->call('WikiRouterSeeder');

