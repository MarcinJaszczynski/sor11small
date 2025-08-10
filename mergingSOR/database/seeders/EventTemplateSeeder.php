<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventTemplate;
use App\Models\Tag;
use App\Models\EventTemplateProgramPoint;
use Illuminate\Support\Str;

class EventTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pobierz tagi
        $integrationTag = Tag::where('name', 'Impreza integracyjna')->first();
        $companyTag = Tag::where('name', 'Impreza firmowa')->first();
        $workshopTag = Tag::where('name', 'Warsztaty')->first();
        $dinnerTag = Tag::where('name', 'Kolacja')->first();

        // Pobierz punkty programu
        $workshopPoint = EventTemplateProgramPoint::where('name', 'Warsztaty integracyjne')->first();
        $dinnerPoint = EventTemplateProgramPoint::where('name', 'Kolacja w restauracji')->first();
        $cookingPoint = EventTemplateProgramPoint::where('name', 'Warsztaty kulinarne')->first();
        $teamBuildingPoint = EventTemplateProgramPoint::where('name', 'Warsztaty team building')->first();

        $templates = [
            // 4-dniowe
            [
                'name' => 'Czterodniowa wycieczka objazdowa',
                'slug' => 'czterodniowa-wycieczka-objazdowa',
                'duration_days' => 4,
                'event_description' => 'Czterodniowa wycieczka autokarowa po najciekawszych miastach regionu, zwiedzanie zabytków, warsztaty tematyczne, wieczorne integracje.',
                'office_description' => 'Dla szkół i firm. Wymaga rezerwacji autokaru i hoteli.',
                'notes' => 'Sprawdzić dostępność przewodników i hoteli.',
            ],
            [
                'name' => 'Czterodniowy obóz sportowy',
                'slug' => 'czterodniowy-oboz-sportowy',
                'duration_days' => 4,
                'event_description' => 'Treningi sportowe, turnieje, wycieczki, wieczorne ogniska i integracje.',
                'office_description' => 'Dla młodzieży i dorosłych. Wymaga rezerwacji obiektów sportowych.',
                'notes' => 'Zamówić sprzęt sportowy.',
            ],
            [
                'name' => 'Czterodniowy plener artystyczny',
                'slug' => 'czterodniowy-plener-artystyczny',
                'duration_days' => 4,
                'event_description' => 'Warsztaty malarskie, plenery, wystawa prac na zakończenie, spotkania z artystami.',
                'office_description' => 'Dla artystów i amatorów. Wymaga pleneru i sali wystawowej.',
                'notes' => 'Zamówić materiały plastyczne.',
            ],
            [
                'name' => 'Czterodniowy obóz językowy',
                'slug' => 'czterodniowy-oboz-jezykowy',
                'duration_days' => 4,
                'event_description' => 'Zajęcia językowe, warsztaty, konkursy, wycieczki edukacyjne, wieczorne gry integracyjne.',
                'office_description' => 'Dla dzieci i młodzieży. Wymaga lektora języka.',
                'notes' => 'Zamówić materiały edukacyjne.',
            ],
            [
                'name' => 'Czterodniowy obóz naukowy',
                'slug' => 'czterodniowy-oboz-naukowy',
                'duration_days' => 4,
                'event_description' => 'Zajęcia naukowe, eksperymenty, warsztaty, wycieczki edukacyjne, spotkania z naukowcami.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji laboratoriów.',
                'notes' => 'Zamówić sprzęt laboratoryjny.',
            ],
            [
                'name' => 'Czterodniowy obóz integracyjny',
                'slug' => 'czterodniowy-oboz-integracyjny',
                'duration_days' => 4,
                'event_description' => 'Warsztaty team building, wycieczki, wieczorne integracje, gry terenowe.',
                'office_description' => 'Dla firm. Wymaga rezerwacji hotelu.',
                'notes' => 'Zamówić animatora integracji.',
            ],
            [
                'name' => 'Czterodniowa zielona szkoła',
                'slug' => 'czterodniowa-zielona-szkola',
                'duration_days' => 4,
                'event_description' => 'Zajęcia przyrodnicze, warsztaty ekologiczne, gry terenowe, wycieczki edukacyjne.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji ośrodka edukacyjnego.',
                'notes' => 'Sprawdzić dostępność kadry edukacyjnej.',
            ],
            [
                'name' => 'Czterodniowy obóz przygodowy',
                'slug' => 'czterodniowy-oboz-przygodowy',
                'duration_days' => 4,
                'event_description' => 'Zajęcia survivalowe, gry terenowe, wycieczki, ogniska, nocne podchody.',
                'office_description' => 'Dla młodzieży. Wymaga instruktora survivalu.',
                'notes' => 'Zamówić sprzęt survivalowy.',
            ],

            // 6-dniowe i dłuższe
            [
                'name' => 'Sześciodniowy obóz sportowy',
                'slug' => 'szesciodniowy-oboz-sportowy',
                'duration_days' => 6,
                'event_description' => 'Intensywne treningi sportowe, turnieje, wycieczki, wieczorne ogniska i integracje.',
                'office_description' => 'Dla młodzieży i dorosłych. Wymaga rezerwacji obiektów sportowych.',
                'notes' => 'Zamówić sprzęt sportowy.',
            ],
            [
                'name' => 'Sześciodniowy obóz językowy',
                'slug' => 'szesciodniowy-oboz-jezykowy',
                'duration_days' => 6,
                'event_description' => 'Zajęcia językowe, warsztaty, konkursy, wycieczki edukacyjne, wieczorne gry integracyjne.',
                'office_description' => 'Dla dzieci i młodzieży. Wymaga lektora języka.',
                'notes' => 'Zamówić materiały edukacyjne.',
            ],
            [
                'name' => 'Sześciodniowy plener artystyczny',
                'slug' => 'szesciodniowy-plener-artystyczny',
                'duration_days' => 6,
                'event_description' => 'Warsztaty malarskie, plenery, wystawa prac na zakończenie, spotkania z artystami.',
                'office_description' => 'Dla artystów i amatorów. Wymaga pleneru i sali wystawowej.',
                'notes' => 'Zamówić materiały plastyczne.',
            ],
            [
                'name' => 'Sześciodniowy obóz naukowy',
                'slug' => 'szesciodniowy-oboz-naukowy',
                'duration_days' => 6,
                'event_description' => 'Zajęcia naukowe, eksperymenty, warsztaty, wycieczki edukacyjne, spotkania z naukowcami.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji laboratoriów.',
                'notes' => 'Zamówić sprzęt laboratoryjny.',
            ],
            [
                'name' => 'Sześciodniowy obóz integracyjny',
                'slug' => 'szesciodniowy-oboz-integracyjny',
                'duration_days' => 6,
                'event_description' => 'Warsztaty team building, wycieczki, wieczorne integracje, gry terenowe.',
                'office_description' => 'Dla firm. Wymaga rezerwacji hotelu.',
                'notes' => 'Zamówić animatora integracji.',
            ],
            [
                'name' => 'Sześciodniowa zielona szkoła',
                'slug' => 'szesciodniowa-zielona-szkola',
                'duration_days' => 6,
                'event_description' => 'Zajęcia przyrodnicze, warsztaty ekologiczne, gry terenowe, wycieczki edukacyjne.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji ośrodka edukacyjnego.',
                'notes' => 'Sprawdzić dostępność kadry edukacyjnej.',
            ],
            [
                'name' => 'Sześciodniowy obóz przygodowy',
                'slug' => 'szesciodniowy-oboz-przygodowy',
                'duration_days' => 6,
                'event_description' => 'Zajęcia survivalowe, gry terenowe, wycieczki, ogniska, nocne podchody.',
                'office_description' => 'Dla młodzieży. Wymaga instruktora survivalu.',
                'notes' => 'Zamówić sprzęt survivalowy.',
            ],
            [
                'name' => 'Siedmiodniowy obóz sportowy',
                'slug' => 'siedmiodniowy-oboz-sportowy',
                'duration_days' => 7,
                'event_description' => 'Tydzień intensywnych treningów, turniejów, wycieczek i wieczornych ognisk.',
                'office_description' => 'Dla młodzieży i dorosłych. Wymaga rezerwacji obiektów sportowych.',
                'notes' => 'Zamówić sprzęt sportowy.',
            ],
            [
                'name' => 'Siedmiodniowy obóz językowy',
                'slug' => 'siedmiodniowy-oboz-jezykowy',
                'duration_days' => 7,
                'event_description' => 'Tydzień zajęć językowych, warsztatów, konkursów i wycieczek edukacyjnych.',
                'office_description' => 'Dla dzieci i młodzieży. Wymaga lektora języka.',
                'notes' => 'Zamówić materiały edukacyjne.',
            ],
            [
                'name' => 'Siedmiodniowy plener artystyczny',
                'slug' => 'siedmiodniowy-plener-artystyczny',
                'duration_days' => 7,
                'event_description' => 'Tydzień warsztatów malarskich, plenerów, wystaw i spotkań z artystami.',
                'office_description' => 'Dla artystów i amatorów. Wymaga pleneru i sali wystawowej.',
                'notes' => 'Zamówić materiały plastyczne.',
            ],
            [
                'name' => 'Siedmiodniowy obóz naukowy',
                'slug' => 'siedmiodniowy-oboz-naukowy',
                'duration_days' => 7,
                'event_description' => 'Tydzień zajęć naukowych, eksperymentów, warsztatów i wycieczek edukacyjnych.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji laboratoriów.',
                'notes' => 'Zamówić sprzęt laboratoryjny.',
            ],
            [
                'name' => 'Siedmiodniowy obóz integracyjny',
                'slug' => 'siedmiodniowy-oboz-integracyjny',
                'duration_days' => 7,
                'event_description' => 'Tydzień warsztatów team building, wycieczek, wieczornych integracji i gier terenowych.',
                'office_description' => 'Dla firm. Wymaga rezerwacji hotelu.',
                'notes' => 'Zamówić animatora integracji.',
            ],
            [
                'name' => 'Siedmiodniowa zielona szkoła',
                'slug' => 'siedmiodniowa-zielona-szkola',
                'duration_days' => 7,
                'event_description' => 'Tydzień zajęć przyrodniczych, warsztatów ekologicznych, gier terenowych i wycieczek edukacyjnych.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji ośrodka edukacyjnego.',
                'notes' => 'Sprawdzić dostępność kadry edukacyjnej.',
            ],
            [
                'name' => 'Siedmiodniowy obóz przygodowy',
                'slug' => 'siedmiodniowy-oboz-przygodowy',
                'duration_days' => 7,
                'event_description' => 'Tydzień zajęć survivalowych, gier terenowych, wycieczek i ognisk.',
                'office_description' => 'Dla młodzieży. Wymaga instruktora survivalu.',
                'notes' => 'Zamówić sprzęt survivalowy.',
            ],
            // 1-dniowe
            [
                'name' => 'Jednodniowa impreza integracyjna',
                'slug' => 'jednodniowa-impreza-integracyjna',
                'duration_days' => 1,
                'event_description' => 'Intensywna jednodniowa impreza integracyjna z warsztatami, grami zespołowymi, wspólną kolacją i wieczorną integracją. Uczestnicy biorą udział w kreatywnych zadaniach, które wzmacniają współpracę i komunikację.',
                'office_description' => 'Impreza przeznaczona dla zespołów do 30 osób. Wymaga rezerwacji z wyprzedzeniem. Możliwość dostosowania programu do potrzeb klienta.',
                'notes' => 'Sprawdzić dostępność sali konferencyjnej, restauracji oraz animatora integracji.',
            ],
            [
                'name' => 'Dzień motywacyjny dla pracowników',
                'slug' => 'dzien-motywacyjny-pracownikow',
                'duration_days' => 1,
                'event_description' => 'Jednodniowe warsztaty motywacyjne z trenerem, gry integracyjne, lunch i podsumowanie dnia z nagrodami.',
                'office_description' => 'Dla zespołów do 20 osób. Wymaga rezerwacji sali szkoleniowej.',
                'notes' => 'Zamówić catering i materiały szkoleniowe.',
            ],
            [
                'name' => 'Wyjazd edukacyjny do muzeum',
                'slug' => 'wyjazd-edukacyjny-muzeum',
                'duration_days' => 1,
                'event_description' => 'Wycieczka do muzeum z przewodnikiem, warsztaty tematyczne, obiad i quiz wiedzy.',
                'office_description' => 'Dla szkół i firm. Wymaga rezerwacji biletów grupowych.',
                'notes' => 'Sprawdzić dostępność przewodnika.',
            ],
            [
                'name' => 'Dzień sportu',
                'slug' => 'dzien-sportu',
                'duration_days' => 1,
                'event_description' => 'Turniej sportowy, gry zespołowe, piknik na świeżym powietrzu, wręczenie medali.',
                'office_description' => 'Dla firm i szkół. Wymaga rezerwacji boiska.',
                'notes' => 'Zamówić sprzęt sportowy i nagrody.',
            ],
            [
                'name' => 'Warsztaty kulinarne z degustacją',
                'slug' => 'warsztaty-kulinarne-degustacja',
                'duration_days' => 1,
                'event_description' => 'Warsztaty gotowania z szefem kuchni, degustacja potraw, konkurs na najlepsze danie.',
                'office_description' => 'Dla grup do 15 osób. Wymaga kuchni warsztatowej.',
                'notes' => 'Zamówić produkty spożywcze.',
            ],
            [
                'name' => 'Dzień kreatywności',
                'slug' => 'dzien-kreatywnosci',
                'duration_days' => 1,
                'event_description' => 'Warsztaty kreatywne: malowanie, rękodzieło, konkurs na najciekawszy projekt.',
                'office_description' => 'Dla dzieci i dorosłych. Wymaga sali warsztatowej.',
                'notes' => 'Zamówić materiały plastyczne.',
            ],
            [
                'name' => 'Dzień zdrowia i wellness',
                'slug' => 'dzien-zdrowia-wellness',
                'duration_days' => 1,
                'event_description' => 'Zajęcia fitness, wykład o zdrowym stylu życia, konsultacje z dietetykiem.',
                'office_description' => 'Dla firm i szkół. Wymaga sali fitness.',
                'notes' => 'Zamówić sprzęt sportowy i zaprosić dietetyka.',
            ],
            [
                'name' => 'Jednodniowa wycieczka krajoznawcza',
                'slug' => 'jednodniowa-wycieczka-krajoznawcza',
                'duration_days' => 1,
                'event_description' => 'Wycieczka autokarowa do atrakcji turystycznych, zwiedzanie z przewodnikiem, obiad.',
                'office_description' => 'Dla szkół i firm. Wymaga rezerwacji autokaru.',
                'notes' => 'Sprawdzić dostępność autokaru i przewodnika.',
            ],

            // 2-dniowe
            [
                'name' => 'Weekend integracyjny w górach',
                'slug' => 'weekend-integracyjny-gory',
                'duration_days' => 2,
                'event_description' => 'Dwudniowy wyjazd integracyjny do malowniczej miejscowości górskiej. W programie: warsztaty team building, wycieczka piesza z przewodnikiem, ognisko z muzyką na żywo oraz relaks w strefie wellness.',
                'office_description' => 'Wymaga rezerwacji hotelu i transportu. Maksymalnie 40 osób. Zalecane wcześniejsze zgłoszenie preferencji dietetycznych.',
                'notes' => 'Uwzględnić czas na dojazd i powrót uczestników. Sprawdzić dostępność przewodnika górskiego.',
            ],
            [
                'name' => 'Weekend z jogą',
                'slug' => 'weekend-z-joga',
                'duration_days' => 2,
                'event_description' => 'Warsztaty jogi, medytacje, zdrowe posiłki, wieczorne ognisko.',
                'office_description' => 'Dla grup do 20 osób. Wymaga rezerwacji sali do jogi.',
                'notes' => 'Zamówić maty do jogi i catering.',
            ],
            [
                'name' => 'Wyjazd survivalowy',
                'slug' => 'wyjazd-survivalowy',
                'duration_days' => 2,
                'event_description' => 'Szkolenie survivalowe w lesie, budowa szałasów, ognisko, nauka orientacji w terenie.',
                'office_description' => 'Dla grup do 15 osób. Wymaga instruktora survivalu.',
                'notes' => 'Zamówić sprzęt survivalowy.',
            ],
            [
                'name' => 'Weekend z fotografią',
                'slug' => 'weekend-z-fotografia',
                'duration_days' => 2,
                'event_description' => 'Warsztaty fotograficzne, plenery, konkurs na najlepsze zdjęcie.',
                'office_description' => 'Dla pasjonatów fotografii. Wymaga rezerwacji sali i plenerów.',
                'notes' => 'Zamówić sprzęt fotograficzny.',
            ],
            [
                'name' => 'Weekend rodzinny',
                'slug' => 'weekend-rodzinny',
                'duration_days' => 2,
                'event_description' => 'Gry i zabawy rodzinne, warsztaty kreatywne, wspólne ognisko.',
                'office_description' => 'Dla rodzin z dziećmi. Wymaga animatora.',
                'notes' => 'Zamówić materiały do zabaw.',
            ],
            [
                'name' => 'Weekend z kulturą',
                'slug' => 'weekend-z-kultura',
                'duration_days' => 2,
                'event_description' => 'Wyjazd do teatru, zwiedzanie muzeum, warsztaty artystyczne.',
                'office_description' => 'Dla miłośników kultury. Wymaga rezerwacji biletów.',
                'notes' => 'Sprawdzić repertuar teatru.',
            ],
            [
                'name' => 'Weekend sportowy',
                'slug' => 'weekend-sportowy',
                'duration_days' => 2,
                'event_description' => 'Turniej sportowy, zajęcia fitness, wieczorna integracja.',
                'office_description' => 'Dla firm i szkół. Wymaga rezerwacji obiektów sportowych.',
                'notes' => 'Zamówić sprzęt sportowy.',
            ],
            [
                'name' => 'Weekend z muzyką',
                'slug' => 'weekend-z-muzyka',
                'duration_days' => 2,
                'event_description' => 'Warsztaty muzyczne, koncert, wspólne jam session.',
                'office_description' => 'Dla muzyków i amatorów. Wymaga sali prób.',
                'notes' => 'Zamówić instrumenty muzyczne.',
            ],

            // 3-dniowe
            [
                'name' => 'Wyjazd szkoleniowy nad morze',
                'slug' => 'wyjazd-szkoleniowy-morze',
                'duration_days' => 3,
                'event_description' => 'Trzydniowy wyjazd szkoleniowy nad Bałtyk. W programie: szkolenia branżowe, warsztaty rozwoju osobistego, wieczorne integracje na plaży oraz wycieczka do latarni morskiej.',
                'office_description' => 'Wymaga rezerwacji ośrodka nadmorskiego. Grupa do 25 osób. Możliwość organizacji transportu autokarem.',
                'notes' => 'Sprawdzić dostępność sal szkoleniowych i sprzętu multimedialnego.',
            ],
            [
                'name' => 'Trzydniowa wycieczka krajoznawcza',
                'slug' => 'trzydniowa-wycieczka-krajoznawcza',
                'duration_days' => 3,
                'event_description' => 'Wycieczka autokarowa do trzech miast, zwiedzanie zabytków, warsztaty tematyczne.',
                'office_description' => 'Dla szkół i firm. Wymaga rezerwacji autokaru i hoteli.',
                'notes' => 'Sprawdzić dostępność przewodników.',
            ],
            [
                'name' => 'Szkolenie z rozwoju osobistego',
                'slug' => 'szkolenie-rozwoj-osobisty',
                'duration_days' => 3,
                'event_description' => 'Szkolenia i warsztaty z rozwoju osobistego, coaching, wieczorne integracje.',
                'office_description' => 'Dla firm. Wymaga rezerwacji sal szkoleniowych.',
                'notes' => 'Zamówić materiały szkoleniowe.',
            ],
            [
                'name' => 'Trzydniowy plener artystyczny',
                'slug' => 'trzydniowy-plener-artystyczny',
                'duration_days' => 3,
                'event_description' => 'Warsztaty malarskie, plenery, wystawa prac na zakończenie.',
                'office_description' => 'Dla artystów i amatorów. Wymaga pleneru i sali wystawowej.',
                'notes' => 'Zamówić materiały plastyczne.',
            ],
            [
                'name' => 'Trzydniowy obóz sportowy',
                'slug' => 'trzydniowy-oboz-sportowy',
                'duration_days' => 3,
                'event_description' => 'Treningi sportowe, turnieje, wieczorne ognisko.',
                'office_description' => 'Dla młodzieży i dorosłych. Wymaga rezerwacji obiektów sportowych.',
                'notes' => 'Zamówić sprzęt sportowy.',
            ],
            [
                'name' => 'Trzydniowy obóz językowy',
                'slug' => 'trzydniowy-oboz-jezykowy',
                'duration_days' => 3,
                'event_description' => 'Zajęcia językowe, gry i zabawy, konkursy z nagrodami.',
                'office_description' => 'Dla dzieci i młodzieży. Wymaga lektora języka.',
                'notes' => 'Zamówić materiały edukacyjne.',
            ],
            [
                'name' => 'Trzydniowy wyjazd integracyjny',
                'slug' => 'trzydniowy-wyjazd-integracyjny',
                'duration_days' => 3,
                'event_description' => 'Warsztaty team building, wycieczki, wieczorne integracje.',
                'office_description' => 'Dla firm. Wymaga rezerwacji hotelu.',
                'notes' => 'Zamówić animatora integracji.',
            ],
            [
                'name' => 'Trzydniowa zielona szkoła',
                'slug' => 'trzydniowa-zielona-szkola',
                'duration_days' => 3,
                'event_description' => 'Zajęcia przyrodnicze, warsztaty ekologiczne, gry terenowe.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji ośrodka edukacyjnego.',
                'notes' => 'Sprawdzić dostępność kadry edukacyjnej.',
            ],

            // 5-dniowe
            [
                'name' => 'Zielona szkoła w lesie',
                'slug' => 'zielona-szkola-las',
                'duration_days' => 5,
                'event_description' => 'Pięciodniowy pobyt edukacyjny w lesie. Zajęcia przyrodnicze, warsztaty ekologiczne, gry terenowe, ognisko i nocne obserwacje gwiazd. Program dostosowany do różnych grup wiekowych.',
                'office_description' => 'Wymaga rezerwacji ośrodka edukacyjnego. Grupa do 40 osób. Opieka wykwalifikowanej kadry.',
                'notes' => 'Sprawdzić dostępność przewodnika przyrodniczego i sprzętu terenowego.',
            ],
            [
                'name' => 'Pięciodniowy obóz sportowy',
                'slug' => 'pietniodniowy-oboz-sportowy',
                'duration_days' => 5,
                'event_description' => 'Treningi sportowe, turnieje, wycieczki, wieczorne ogniska.',
                'office_description' => 'Dla młodzieży i dorosłych. Wymaga rezerwacji obiektów sportowych.',
                'notes' => 'Zamówić sprzęt sportowy.',
            ],
            [
                'name' => 'Pięciodniowy obóz językowy',
                'slug' => 'pietniodniowy-oboz-jezykowy',
                'duration_days' => 5,
                'event_description' => 'Zajęcia językowe, warsztaty, konkursy, wycieczki edukacyjne.',
                'office_description' => 'Dla dzieci i młodzieży. Wymaga lektora języka.',
                'notes' => 'Zamówić materiały edukacyjne.',
            ],
            [
                'name' => 'Pięciodniowy plener artystyczny',
                'slug' => 'pietniodniowy-plener-artystyczny',
                'duration_days' => 5,
                'event_description' => 'Warsztaty malarskie, plenery, wystawa prac na zakończenie.',
                'office_description' => 'Dla artystów i amatorów. Wymaga pleneru i sali wystawowej.',
                'notes' => 'Zamówić materiały plastyczne.',
            ],
            [
                'name' => 'Pięciodniowy obóz naukowy',
                'slug' => 'pietniodniowy-oboz-naukowy',
                'duration_days' => 5,
                'event_description' => 'Zajęcia naukowe, eksperymenty, warsztaty, wycieczki edukacyjne.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji laboratoriów.',
                'notes' => 'Zamówić sprzęt laboratoryjny.',
            ],
            [
                'name' => 'Pięciodniowy obóz integracyjny',
                'slug' => 'pietniodniowy-oboz-integracyjny',
                'duration_days' => 5,
                'event_description' => 'Warsztaty team building, wycieczki, wieczorne integracje.',
                'office_description' => 'Dla firm. Wymaga rezerwacji hotelu.',
                'notes' => 'Zamówić animatora integracji.',
            ],
            [
                'name' => 'Pięciodniowa zielona szkoła',
                'slug' => 'pietniodniowa-zielona-szkola',
                'duration_days' => 5,
                'event_description' => 'Zajęcia przyrodnicze, warsztaty ekologiczne, gry terenowe.',
                'office_description' => 'Dla szkół. Wymaga rezerwacji ośrodka edukacyjnego.',
                'notes' => 'Sprawdzić dostępność kadry edukacyjnej.',
            ],
            [
                'name' => 'Pięciodniowy obóz przygodowy',
                'slug' => 'pietniodniowy-oboz-przygodowy',
                'duration_days' => 5,
                'event_description' => 'Zajęcia survivalowe, gry terenowe, wycieczki, ogniska.',
                'office_description' => 'Dla młodzieży. Wymaga instruktora survivalu.',
                'notes' => 'Zamówić sprzęt survivalowy.',
            ],
        ];

        foreach ($templates as $template) {
            $eventTemplate = \App\Models\EventTemplate::firstOrCreate(
                ['slug' => $template['slug']],
                $template
            );

            // Przykładowe tagi i punkty programu dla każdego szablonu
            if ($template['slug'] === 'jednodniowa-impreza-integracyjna') {
                $eventTemplate->tags()->attach([$integrationTag->id, $workshopTag->id, $dinnerTag->id]);
                $eventTemplate->programPoints()->attach($workshopPoint->id, [
                    'day' => 1,
                    'order' => 1,
                    'notes' => 'Warsztaty poranne: gry integracyjne, ćwiczenia kreatywne, zadania zespołowe.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
                $eventTemplate->programPoints()->attach($dinnerPoint->id, [
                    'day' => 1,
                    'order' => 2,
                    'notes' => 'Kolacja wieczorna z muzyką na żywo i animacjami integracyjnymi.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
            } elseif ($template['slug'] === 'weekend-integracyjny-gory') {
                $eventTemplate->tags()->attach([$integrationTag->id, $companyTag->id, $workshopTag->id]);
                $eventTemplate->programPoints()->attach($teamBuildingPoint->id, [
                    'day' => 1,
                    'order' => 1,
                    'notes' => 'Warsztaty team building: zadania w terenie, budowanie zaufania, rozwiązywanie problemów.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
                $eventTemplate->programPoints()->attach($cookingPoint->id, [
                    'day' => 1,
                    'order' => 2,
                    'notes' => 'Warsztaty kulinarne: wspólne gotowanie regionalnych potraw, degustacja.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
                $eventTemplate->programPoints()->attach($dinnerPoint->id, [
                    'day' => 1,
                    'order' => 3,
                    'notes' => 'Kolacja integracyjna przy ognisku z muzyką na żywo.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
            } elseif ($template['slug'] === 'wieczor-firmowy-gala') {
                $eventTemplate->tags()->attach([$companyTag->id, $dinnerTag->id]);
                $eventTemplate->programPoints()->attach($dinnerPoint->id, [
                    'day' => 1,
                    'order' => 1,
                    'notes' => 'Kolacja degustacyjna z występem artystycznym i wręczeniem nagród.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
            } elseif ($template['slug'] === 'wyjazd-szkoleniowy-morze') {
                $eventTemplate->tags()->attach([$companyTag->id, $workshopTag->id]);
                $eventTemplate->programPoints()->attach($workshopPoint->id, [
                    'day' => 1,
                    'order' => 1,
                    'notes' => 'Szkolenia branżowe: prezentacje, case studies, praca w grupach.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
                $eventTemplate->programPoints()->attach($teamBuildingPoint->id, [
                    'day' => 2,
                    'order' => 1,
                    'notes' => 'Warsztaty rozwoju osobistego na plaży: ćwiczenia motywacyjne, integracja.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
                $eventTemplate->programPoints()->attach($dinnerPoint->id, [
                    'day' => 2,
                    'order' => 2,
                    'notes' => 'Wieczorna integracja na plaży z ogniskiem.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
            } elseif ($template['slug'] === 'zielona-szkola-las') {
                $eventTemplate->tags()->attach([$workshopTag->id]);
                $eventTemplate->programPoints()->attach($workshopPoint->id, [
                    'day' => 1,
                    'order' => 1,
                    'notes' => 'Warsztaty ekologiczne: rozpoznawanie roślin, budowa hoteli dla owadów.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
                $eventTemplate->programPoints()->attach($teamBuildingPoint->id, [
                    'day' => 2,
                    'order' => 1,
                    'notes' => 'Gry terenowe: podchody, orientacja w terenie, praca zespołowa.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
                $eventTemplate->programPoints()->attach($dinnerPoint->id, [
                    'day' => 3,
                    'order' => 1,
                    'notes' => 'Ognisko i nocne obserwacje gwiazd.',
                    'include_in_program' => true,
                    'include_in_calculation' => true,
                    'active' => true,
                ]);
            }
        }
    }
}
