sudo & user---------------------
apt update &&  apt upgrade -y
# apt install sudo
adduser motto  (bekéri a jelszót többit leokézni)
usermod -aG sudo motto
teszt:
su motto
sudo apt update

webszerver ólaravel-----------------------
https://www.linuxtuto.com/how-to-install-laravel-9-on-debian-11/
$ 
sudo apt update && sudo apt upgrade -y
apche-----------
sudo apt install apache2
sudo systemctl start apache2
Then enable it to start on boot time.
 sudo systemctl enable apache2

 php---------------
a 8.1-es nics alapból a debiánban sima telepítéssel csak a 7-est teszi fel
le kell tölteni:
sudo apt-get install ca-certificates apt-transport-https software-properties-common -y
 A sudo kimaradt a második részből permission hibát dob javitott parancs:
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/sury-php.list
kulcs
wget -qO - https://packages.sury.org/php/apt.gpg | sudo apt-key add -
Warning: apt-key is deprecated. Manage keyring files in trusted.gpg.d instead (see apt-key(8)).
apt-get update -y
telepítés: a warning ellenére lefut
 sudo apt-get install php8.1 libapache2-mod-php php8.1-dev php8.1-zip php8.1-curl php8.1-mbstring php8.1-mysql php8.1-gd php8.1-xml
7.4 eltávolítás ha fent van (nem nagyon mükszik):
sudo apt purge PHP7.4
sudo apt-get autoremove

maria db--------------------
sudo apt install mariadb-server mariadb-client
systemctl start mariadb
systemctl enable mariadb
MariaDB [(none)]> 
        CREATE DATABASE laravel_db;
        CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'Password';
        GRANT ALL ON laravel_db.* TO 'laravel_user'@'localhost';
        FLUSH PRIVILEGES;
        EXIT


composer telepítés---------------------
curl:
sudo apt install curl
curl -sS https://getcomposer.org/installer | sudo php  //sudo-t én írtam be
sudo mv composer.phar  /usr/local/bin/composer
sudo chmod +x   /usr/local/bin/composer
composer --version

laravel telepítés---------------
cd /var/www/html
sudo composer create-project laravel/laravel laravelapp  //sudo-ra a composer tiltakozik! de ha nincs akkor meg permission hiba!
sudo chown -R www-data:www-data /var/www/html/laravelapp
sudo chmod -R 775 /var/www/html/laravelapp/storage
cd laravelapp
php artisan  //teszt
sudo nano /etc/apache2/sites-available/laravel.conf :
---------------------------------------------------
<VirtualHost *:80>

ServerName your-domain.com

ServerAdmin webmaster@your-domain.com
DocumentRoot /var/www/html/laravelapp/public

<Directory /var/www/html/laravelapp/>
Options +FollowSymlinks
AllowOverride All
Require all granted
</Directory>

ErrorLog ${APACHE_LOG_DIR}/your-domain.com_error.log
CustomLog ${APACHE_LOG_DIR}/your-domain.com_access.log combined

</VirtualHost>
-----------------------------------------

sudo a2ensite laravel.conf
sudo systemctl restart apache2

git------------------------------------------
sudo rm -r dirname  //törlés rekurzívan ha már van egy laravel ami nem kell
sudo apt update
sudo apt install git
git --version
beállítás:
git config --global user.name "Winnie"
git config --global user.email "Winnie@xyz.com"
git config --list  // kiirja a mentett adatokat



kész könyvtár feltöltése a gitre problémás egyszerőbb ha csinálunk egy üre repot és klónozzuk,
ha a githubon megcsináljuk a repot kiirja a kloónozás parancsait:
sudo git init
sudo git add .
sudo git commit -m "first commit"
sudo git branch -M main
sudo git remote add origin https://github.com/motto001/laravelapp.git
sudo git push -u origin main

kéri a felhasználónevet és a jelszőt de a jelszavas hozzáférést már letiltották. Helyette Personal access tokens adjuk meg.
A githubon kell generálni a Settings/Developer settings/ lapon
 előtte kellhet:
 git config -l

git push origin master  //feltöltés 
git pull origin master  //letöltés.

ssh-keygen -t ed25519 -C "motto001@github.com"  // ssh kulcs létrehozása githubhoz
eval "$(ssh-agent -s)"   // ssh kulcs aktiválása (nem múködött vele a push)


phpmyadmin-------------

Download latest version of phpMyAdmin with wget command.

DATA="$(wget https://www.phpmyadmin.net/home_page/version.txt -q -O-)"
URL="$(echo $DATA | cut -d ' ' -f 3)"
VERSION="$(echo $DATA | cut -d ' ' -f 1)"
wget https://files.phpmyadmin.net/phpMyAdmin/${VERSION}/phpMyAdmin-${VERSION}-all-languages.tar.gz