# desserlist
Schoolproject. A Webpage were you can register and create, edit and delete entries for eating on special days

## Was mit welcher Version installiert sein sollte
| Programm       | Version  | Link                                                              |
| -------------- | :------: | ----------------------------------------------------------------- |
| docker         | 18.09.06 | [docker](https://docs.docker.com/install/linux/docker-ce/ubuntu/) |
| composer       | 1.6.3    | [composer](https://getcomposer.org/download/)                     |
| docker-compose | 1.24.0   | [docker-compose](https://docs.docker.com/compose/install/)        |
| yarn           | 1.16.0   | [yarn](https://yarnpkg.com/lang/en/docs/install/#debian-stable)   |

## Container bauen und starten
# Container bauen
Mit dem Befehl `docker-compose build` baut man die Container.

# Container hochfahren
Mit dem Befehl `docker-compose up -d` fährt man die Container hoch.
(Es ist nicht schlimm wenn phpAdmin nicht hoch fährt.)

# Container starten
Mit dem Befehl `docker-compose start` startet man die Container nachdem sie pausiert wurden.

# Container pausieren
Mit dem Befehl `docker-compose stop` pausiert man die Container.

# Container löschen
Mit dem Befehl `docker-compose down` fährt man die Container runter.

## Container betretten
# PHP-Container betretten
Mit dem Befehl `docker-compose exec dsrt-php bash` kommt man auf den Container für php.
Fast alle Befehle sollte man dort im Verzeichniss `var/www/html` ausführen.

## Framework und Bundle installieren
# In den richtigen Ordner gehen
Man sollte auf den PHP Container gehen und dort in das Verzeichniss `var/www/html` gehen.

# Composer ausführen
In dem Verzeichniss sollte man dann den Befehl `composer install` ausführen.

## Yarn install ausführen
# In den richtigen Ordner gehen
Man sollte auf den PHP Container gehen und dort in das Verzeichniss `var/www/html` gehen.

# Yarn install
Dort sollte man den Befehl `yarn install` ausführen.

## Datenbank erstellen und befüllen
# In den richtigen Ordner gehen
Man sollte auf den PHP Container gehen und dort in das Verzeichniss `var/www/html`gehen.

# Datenbank aufbauen
Mit dem Befehl `bin/console doctrine:database:create` erstellt man die Datenbank.

# Datenbank schema aufbauen
Mit dem Befehl `bin/console doctrine:schema:create` erstellt man die Tabellen der Datenbank.

# Mit Fixture befüllen
Mit dem Befehl `bin/console doctrine:fixture:load` erstellt man random Daten in der Datenbank.
(Die user `dude-a@test.com` bis `dude-i@test.com` sollten mit dem Passwort `auto123` erstellt werden)

## Css und JS Dateien erstellen
Mit dem Befehl `yarn encore dev` erstelle man die css Dateien aus der scss Dateien.
