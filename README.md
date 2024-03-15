Aplikacja do Zarządzania Kodami Promocyjnymi


Aplikacja do Zarządzania Kodami Promocyjnymi to narzędzie webowe umożliwiające tworzenie, modyfikowanie, deaktywację i usuwanie kodów promocyjnych. Aplikacja ta została zbudowana z wykorzystaniem Symfony 7 i PHP 8, oferując proste i intuicyjne API RESTowe do zarządzania kodami.

Funkcje
Dodawanie Nowego Kodu: Umożliwia tworzenie nowego kodu promocyjnego.
Edycja Kodu: Zmiana nazwy istniejącego kodu promocyjnego.
Dezaktywacja Kodu: Możliwość oznaczenia kodu jako nieaktywnego.
Usunięcie Kodu: Usunięcie istniejącego kodu promocyjnego.
Wyświetlanie Kodu: Pobranie szczegółów kodu promocyjnego; z każdym wyświetleniem, limit dostępnych kodów zmniejsza się

Uruchamianie Aplikacji

Wymagania
Docker
Docker-compose
Klonowanie Repozytorium
Skopiuj repozytorium na swoją maszynę lokalną:
git clone <URL_DO_REPOZYTORIUM>

Uruchomienie Kontenerów Docker
W głównym katalogu projektu wykonaj:
docker-compose up -d
To polecenie zbuduje i uruchomi kontenery Docker zdefiniowane w docker-compose.yml, w tym kontener dla Symfony i MySQL.

Instalacja Zależności
docker-compose exec app composer install

Konfiguracja Bazy Danych
Upewnij się, że ciąg połączenia z bazą danych w pliku .env jest poprawnie skonfigurowany do użycia z kontenerem MySQL.

Endpoints API
POST /promo-code/add: Dodaj nowy kod promocyjny.
POST /promo-code/edit/{id}: Edytuj kod promocyjny.
POST /promo-code/deactivate/{id}: Dezaktywuj kod promocyjny.
GET /promo-code/{id}: Wyświetl szczegóły kodu promocyjnego.
DELETE /promo-code/delete/{id}: Usuń kod promocyjny.
