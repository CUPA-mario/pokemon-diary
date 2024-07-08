## # Overview

A Drupal project.

To create a visually appealing, shareable Pokémon diary, allowing a user to register on a website, add their “friendcode” and then add an entry for every pokemon they capture.

## # Installation
- Requirements:

    - WSL2
    - Docker
    - DDEV 

    For installation guides for the above mentioned, please refer to these documentations:
    - WSL : https://learn.microsoft.com/en-us/windows/wsl/install
    - Docker Desktop : https://docs.docker.com/guides/getting-started/
    - DDEV :  https://ddev.readthedocs.io/en/latest/users/install/ddev-installation/

- Installation

    Assuming we completed the requirements above:
    - Step 1: Clone this [Repository](https://github.com/CUPA-mario/pokemon-diary).
    - Step 2: Open a terminal inside the directory and run `ddev start`.
    - Step 3: run `ddev composer install`.
    - Step 4: run `ddev start` to start DDEV services, link will be generated to access the site on your local machine.

- Getting Started

    - First setup on your local machine:
        - run `ddev snapshot restore` to sync with the base Drupal Project configuration.
        - run `ddev restart` to ensure the configurations are applied properly .and restart services.
        - run `ddev composer install`.
        - run `ddev drush deploy` to code deployment.
        - report if there's any error occuring at this point.

    - Routine development
        - pull from `develop` via running `git pull origin develop` to ensure your branches is up to date. *Note: Do this before creating branches, Before committing and pushing branches, or when Important commits are pushed on develop branch.* 
        - run `ddev drush deploy`.

    - Merging codes to develop
        - after `git push` on your feature branches, create a pull request ticket on repository to have your codes reviewed.

- Syncing Database Between Platform.sh and Local Environment
    - Installing platformsh cli
        - run this on your ubuntu app `curl -sS https://platform.sh/cli/installer | php`
        - run `platform login` enter your created credentials here
        - run `platform environment:info` check if you are in develop
        - to check all of platform commands run `paltform list`
        - to run drush commands directly on the server
        - run `platform ssh` you should be inside the drupal root project.
        - run `drush status`

    - Pulling database changes from Platform.sh
        - Before working on your feature branch, ensure your local database is synced with the latest database from Platform.sh:
        - run `git checkout develop`
        - run `git pull origin develop`
        - run `ddev restart`
        - run `ddev pull platform --skip-files --skip-confirmation --skip-import -y`
        *this command fetches the latest database changes without importing files, confirming prompts, or requiring user input.
        - run `ddev import-db --file=.ddev/.downloads/db.sql.gz`
        - run `ddev drush deploy`

    - Pushing local database changes to Platform.sh
        - After completing your feature and merging it into the develop branch, update Platform.sh with your local database changes:
        - run `git checkout develop`
        - run `git pull origin develop`
        - run `ddev push platform --skip-files --skip-confirmation --skip-import -y`
        *this command pushes your local database changes to Platform.sh, ensuring synchronization across environments.

*Thank you for taking time to read this documentation. - CUPA - Mario Collaborators*

<!-- Todo further information will be added once requirements has been wrapped up -->