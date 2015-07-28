Banking statistics
========================


= Pre-requisites = 

- install bower under /usr/bin/bower


= Installation

- clone repo
- composer install
- app/console sp:bower:install
- app/console assets:install web --symlink
- app/console assetic:dump --force
