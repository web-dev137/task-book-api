Restful API for small library application

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      modules/            contains module api for library
      queriesPostman      contains example queries for API
      rbac                contains rbac rule for owner book
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 8.1


INSTALLATION
------------
Clone repository:
git clone https://github.com/web-dev137/task-book-api.git

Rename config files from ./config(drop ending "example")

create database library_db


Apply migrations via next commands:
php  yii migrate

php yii migrate --migrationPath=@yii/rbac/migrations

Apply permission:

php yii rbac/init
