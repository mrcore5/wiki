## Mrcore Wiki Module

This is an mRcore module that provides wiki functionality.

## What Is Mrcore

Mrcore is a set of Laravel and Lumen components used to build various systems.
It is a framework, a development platform and a CMS.  It is a modularized version of Laravel
providing better package development support.  Think of Laravel 4.x workbenches on steroids.

See https://github.com/mrcore5/framework for details and installation instructions.

## Official Documentation

For this wiki module, well, there isn't any...yet.

Wiki specific documentaion will be here in the future.

For now, see https://github.com/mrcore5/framework






## Enhancement Ideas

* Foundation installer should prompt and notify about to delete laravel models, migrations...
* Build `mrcore5-installer` symfony console command to install fresh laravel/lumen + foundation.  Options for --wiki install or --blog or whatever.
* Perfect entities with `mreschke/repository` for auth, foundation and wiki.  Split tables between them
* Change namespace of bootswatch theme, maybe just Mrcore/BootswatchTheme ?


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


### Foundation Tables

*







## Contributing

Thank you for considering contributing to the mRcore framework!  Fork and pull!

### License

Mrcore is open-sourced software licensed under the [MIT license](http://mreschke.com/license/mit)
