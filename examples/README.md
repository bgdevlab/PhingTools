# Usage
* Install composer - http://getcomposer.org/
* Create composer.json file - see examples folder for all example files.
    * add dependency

        "require": {
            "bgdevlab/phing-tools": "dev-master"
        }

    * add repository

        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/bgdevlab/PhingTools"

            }
        ]

* Run composer
    composer install

* Run Phing
    ./vendor/bin/phing -l

