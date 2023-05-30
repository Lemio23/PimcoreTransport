# PimcoreTransport

Udało się zrealizować wszystkie zadania wypisane w instrukcji.
Korzystałem z pakietu xampp (serwer apache + baza MySql).
W mailu załączyłem film, na którym prezentuje działanie strony.

Instalacja:
1.Skopiuj projekt folder pimcore_windows do folderu ze stronami, z którego korzysta apache (dla xampp domyślnie C:/xampp/htdocs/)
2.Zainstaluj composer
3.Uruchom wiersz poleceń jako administrator, przejdź do folderu z projektem (C:/xampp/htdocs/pimcore_windows) i wpisz polecenie "install composer".
4.Edytuj plik httpd.conf (w apache) tak aby w "documentRoot" był folder C:/xampp/htdocs/pimcore_windows/public
5.Stwórz bazę danych dla projektu (localhost/phpmyadmin)
6.Uruchom w wierszu poleceń pimcore_windows/vendor/bin/pimcore-install
7.Podaj mysql username, mysql password i nazwę wcześniej utworzonej bazy danych
8.Zresetuj apache
9.Wejdź w localhost/admin w przeglądarce i zaloguj się loginem i hasłem, ustawionym przy instalacji pimcore
10.Wejdź w documents i utwórz 2 dokumenty(kliknij prawym na home->Add Page->Blank)
a)nazwa: mainPage
+SEO+ & Settings -> Controller, action & template -> Controller i ustaw App\Controller\ContentController::defaultAction
+SEO+ & Settings -> Controller, action & template -> Template i ustaw layout.php.twig
Wybierz "save and publish"

b)nazwa: formUpload
+SEO+ & Settings -> Controller, action & template -> Controller i ustaw App\Controller\DefaultController::formAction
+SEO+ & Settings -> Controller, action & template -> Template i ustaw default\default.html.twig
wybierz "save and publish"

11.Strona jest dostępna pod "localhost/mainPage"

Piotr Szymański piotrszym01@wp.pl
