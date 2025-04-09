# Pergam.in Recruitment Task

## Exact Task Description

Dziękujemy za zainteresowanie rekrutacją na stanowisko Senior Backend Developer. W ramach pierwszego etapu rekrutacji prosimy o wykonanie prostego REST API. Będzie to element systemu do zarządzania medycznymi dokumentami pacjentów, z którego korzystają lekarze. System przechowuje informacje o użytkownikach (lekarzach), ich pacjentach oraz dokumentach z historii leczenia pacjenta. Każdy pacjent jest przypisany tylko do jednego lekarza. Każdy dokument jest przypisany tylko do jednego pacjenta.

### Założenia techniczne:
- Docker
- Laravel 11
- Relacyjna baza danych Twojego wyboru

### Założenia zadania:
Jako użytkownik (lekarz) chcę mieć możliwość:
- Dodawać dokumenty dla pacjenta którego leczenie prowadzę
- Przechowanie dokumentu powinno być operacją asynchroniczną.
- Dodawany dokument musi być plikiem pdf nie większym niż określona liczba MB. Domyślnie powinno być to 5MB z możliwością konfiguracji w zmiennej środowiskowej
- Użytkownik (lekarz) może zarządzać dokumentami tylko swoich pacjentów

### Uwagi:
Nie ma potrzeby implementacji logowania i rejestracji z wykorzystaniem bearer tokenów. Na potrzeby zadania wykorzystaj basic auth - email i hasło użytkownika. Wymagana jest tylko implementacja zarządzania dokumentami pacjentów. Przykładowe rekordy pacjentów i lekarzy należy udostępnić w przesłanym rozwiązaniu.

## How to Build and Test the Application

To build the application, simply run the following command:
```sh
make start
```

To test the application, run:
```sh
make test
```

## Sample Data

Sample records of patients and doctors are included in the project as seeders (executed automatically when the application is built).

## Asynchronous Document Storage

The requirement for asynchronous document storage was likely a tricky question, as files cannot be passed to asynchronous jobs, and there's probably no reasonable workaround.
Therefore, the file is saved immediately, and in the asynchronous part, only the Document object with the path to the saved file is added.

## Additional Information

I hope I haven't forgotten anything, and if there are any uncertainties, please feel free to contact me - of course I will explain or discuss anything further.
