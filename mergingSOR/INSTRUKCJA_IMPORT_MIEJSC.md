# INSTRUKCJA IMPORTU MIEJSC Z PLIKÓW CSV

## 📋 Przygotowane pliki do importu

✅ **storage/app/public/miejsca_startowe_import.csv** (19 miejsc startowych)
- Punkty wyjazdu (miejsca początkowe wycieczek)
- miejsce_poczatkowe = "tak"

✅ **storage/app/public/miejsca_docelowe_import.csv** (299 miejsc docelowych)  
- Miejsca docelowe wycieczek
- miejsce_poczatkowe = "nie"

## 🔄 Proces importu przez panel administracyjny

### Krok 1: Przejdź do zarządzania miejscami
1. Zaloguj się do panelu Filament
2. Przejdź do sekcji **Miejsca** (Places)

### Krok 2: Import miejsc startowych
1. Kliknij przycisk **"Importuj CSV"**
2. Wybierz plik: `storage/app/public/miejsca_startowe_import.csv`
3. Kliknij **"Importuj"**
4. Sprawdź czy wszystkie 19 miejsc zostało zaimportowane

### Krok 3: Import miejsc docelowych
1. Ponownie kliknij **"Importuj CSV"**
2. Wybierz plik: `storage/app/public/miejsca_docelowe_import.csv`
3. Kliknij **"Importuj"**
4. Sprawdź czy wszystkie 299 miejsc zostało zaimportowane

## 📊 Format danych w plikach CSV

### Struktura kolumn:
- **nazwa** - nazwa miejsca
- **opis** - opis z adresem i nazwą wycieczki
- **tagi** - tagi oddzielone średnikami (;)
- **miejsce_poczatkowe** - "tak" dla startowych, "nie" dla docelowych
- **szerokosc_geograficzna** - latitude
- **dlugosc_geograficzna** - longitude

### Przykładowe dane:

**Miejsca startowe:**
```csv
nazwa,opis,tagi,miejsce_poczatkowe,szerokosc_geograficzna,dlugosc_geograficzna
Warszawa,Stolica Polski,miasto;wojewodzkie;punkty_wyjazdu,tak,52.237049,21.017532
```

**Miejsca docelowe:**
```csv
nazwa,opis,tagi,miejsce_poczatkowe,szerokosc_geograficzna,dlugosc_geograficzna
Kraków,Wycieczka: miasto wojewódzkie,miejsce_docelowe;miasto_wojewodzkie;malopolskie,nie,50.049683,19.944544
```

## ⚠️ Uwagi techniczne

1. **Kodowanie**: Pliki są w UTF-8, mogą wystąpić problemy z polskimi znakami w niektórych programach
2. **Separator**: Przecinek (,) jako separator CSV
3. **Tagi**: Używają znormalizowanych nazw bez polskich znaków (np. "malopolskie" zamiast "małopolskie")
4. **Współrzędne**: Format dziesiętny (np. 52.237049, 21.017532)

## 🔍 Weryfikacja po imporcie

Po imporcie sprawdź:
- [ ] Łączna liczba miejsc: 318 (19 startowych + 299 docelowych)
- [ ] Filtrowanie według typu: startowe vs docelowe
- [ ] Poprawność współrzędnych geograficznych
- [ ] Prawidłowe przypisanie tagów

## 🛠️ Rozwiązywanie problemów

### Problem z kodowaniem polskich znaków:
- Upewnij się, że plik jest zapisany w UTF-8
- Sprawdź ustawienia przeglądarki
- W razie problemów użyj wersji bez polskich znaków w tagach

### Problem z importem:
- Sprawdź czy plik istnieje w katalogu storage/app/public/
- Upewnij się, że separator CSV to przecinek
- Sprawdź logi Laravel w storage/logs/

### Duplikaty:
- System powinien automatycznie obsługiwać duplikaty na podstawie nazwy
- W razie problemów można wyczyścić tabelę places przed importem

## 📁 Lokalizacja plików

```
storage/app/public/
├── miejsca_startowe_import.csv      (19 rekordów)
├── miejsca_docelowe_import.csv      (299 rekordów)
└── places_template.csv              (szablon do nowych importów)
```

---
*Import przygotowany na podstawie plików źródłowych z katalogu zrodla/*
