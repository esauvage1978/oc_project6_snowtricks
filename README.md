# oc_project6_snowtricks

### Openclassrooms / Projet 6 : Développez de A à Z le site communautaire SnowTricks
[![Maintainability]()


## Getting Started :metal:

1. copy repository `git clone https://github.com/esauvage1978/oc_project6_snowtricks.git`
2. `composer install`
3. configure BDD connect on `.env` file
4. Create database `php bin/console doctrine:database:create`
5. Migrate table on database `php bin/console doctrine:migrations:migrate`
6. (for test) load fixtures into the database `php bin/console doctrine:fixtures:load`
7. Account fixtures:
      * Role : Admin
        * username : manu
        * Pass : u12345678
     * Role : Gestionnaire
        * username : pauline
        * Pass : u12345678
     * Role : User 
        * Email : paul
        * Pass : u12345678
