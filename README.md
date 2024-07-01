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
        - run `ddev drush cr` to rebuild existing cache.
        - run `ddev drush cim -y` to acquire latest yml changes.
        - report if there's any error occuring at this point.

    - Routine development
        - pull from `develop` via running `git pull origin develop` to ensure your branches is up to date. *Note: Do this before creating branches, Before committing and pushing branches, or when Important commits are pushed on develop branch.* 
        - run `ddev drush cr`.
        - run `ddev drush cim -y`.
        - run `ddev drush cr` again.

    - Merging codes to develop
        - after `git push` on your feature branches, create a pull request ticket on repository to have your codes reviewed.

*Thank you for taking time to read this documentation. - CUPA - Mario Collaborators*

<!-- Todo further information will be added once requirements has been wrapped up -->