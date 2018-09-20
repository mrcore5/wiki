## Mrcore Wiki Module v2.0

`mrcore/wiki` is a module for the [mRcore Framework](https://github.com/mrcore5/framework)

This module provides wiki and CMS dynamic app loading functionality.


## What Is mRcore

mRcore is a module/package system for Laravel allowing you to build all your applications as reusable modules.
Modules resemble the Laravel folder structure and can be plugged into a single Laravel instance.
mRcore solves module loading dependency order and in-place live asset handling.  Modules can be
full web UIs, REST APIs and/or full Console command line apps.  A well built module is not only your
UI and API, but a shared PHP library, a native API or repository which can be reused as dependencies in other modules.

We firmly believe that all code should be built as modules and not in Laravel's directory structure itself.
Laravel simply becomes the "package server".  A single Laravel instance can host any number of modules.

See https://github.com/mrcore5/framework for details and installation instructions.


## Versions

* 1.0 is for Laravel 5.1 and below
* 2.0 is for Laravel 5.3, 5.4, 5.5
* 5.6 is for Laravel 5.6
* 5.7 is for Laravel 5.7
* ... Following Laravel versions from here on

## Contributing

Thank you for considering contributing to the mRcore framework!  Fork and pull!

### License

mRcore is open source software licensed under the [MIT license](http://mreschke.com/license/mit)









## Enhancement Ideas

* NO-Foundation installer should prompt and notify about to delete laravel models, migrations...
* Build `mrcore5-installer` symfony console command to install fresh laravel + foundation.  Options for --wiki install or --blog or whatever.
* Perfect entities with `mreschke/repository` for auth, foundation and wiki.  Split tables between them
* DONE-Change namespace of bootswatch theme, maybe just Mrcore/BootswatchTheme ?


### Wiki Tables

* badges
* comments
* formats (wiki, php, phpw, html, text, markdown, htmlw)
* frameworks (custom, workbench)
* hashtags
* modes (default, simple, raw, source)
* post_badges
* post_indexes
* post_locks
* post_permissions
* post_reads
* post_tags
* posts
* revisions
* router
* tags
* types (doc, page, app)


    $wiki = App::make('Mrcore\Wiki');


### Auth Tables

* permissions (update with per type, like wiki, or user, or blog...)
* roles
* user_permissions
* user_roles
* users


    $auth = App::make('Mrcore\Auth');
    $user = $auth->user->find(3);
    $user->roles;
    $user->permissions;


