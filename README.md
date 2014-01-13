# PhingTools

About
-----

Phing Tools is a set of useful [Phing](http://www.phing.info/) custom tasks to help in development.


Installation
------------

First you need to add `bgdevlab/phing-tools` to `composer.json`:

    {
       "require": {
            "bgdevlab/phing-tools": "dev-master",
        }
    }


Depending on your [minimum-stability](http://getcomposer.org/doc/04-schema.md#minimum-stability) you may need to add


    {
        "require": {
            "chobie/jira-api-restclient": "~1.0@dev"
        }
    }

Please note that `dev-master` points to the latest release. If you want to use the latest development version please use `dev-develop`. Of course you can also use an explicit version number, e.g., `1.3.*`.

