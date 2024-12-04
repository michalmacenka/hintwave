# HintWave - Technická Dokumentace

## Přehled Technologií
- **Backend**: PHP 8.1+ - Používá se pro serverovou logiku a zpracování dat.
- **Databáze**: MySQL 8.0+ - Slouží k ukládání a správě dat aplikace.
- **Frontend**: Vanilla JavaScript, HTML5, CSS3 - Zajišťuje interaktivitu a uživatelské rozhraní.
- **Server**: Apache 2.4+ - Webový server pro hostování aplikace.
- **Vývojové nástroje**: Docker & Docker Compose - Používány pro kontejnerizaci.


### Adresářová Struktura
   ```plaintext
www/
├── common/          # Sdílené utility a helpery
├── controllers/     # Kontrolery aplikace
├── models/          # Datové modely
├── repositories/    # Databázová vrstva
├── views/           # Šablony pohledů
└── public/          # Veřejné assety
    ├── scripts/     # JavaScript soubory
    ├── styles/      # CSS styly
    └── uploads/     # Nahrané profilovky uživatelů
   ```


## Architektura a Logika Aplikace

### Základní Princip Fungování
Aplikace HintWave funguje jako platforma pro sdílení a správu uživatelských tipů (hintů). Každý hint obsahuje název, popis a sadu důvodů/vysvětlení. Hinty jsou organizovány do kategorií pro lepší přehlednost.

### Datový Flow
1. **Autentizace**
   - Uživatel se přihlásí/registruje přes formulář
   - AuthController ověří údaje
   - Po úspěšném přihlášení je vytvořena session
   - Informace o uživateli jsou dostupné napříč aplikací

2. **Správa Hintů**
   - Vytvoření/editace (kdy editace má parametr id v url) hintu přes formulář
   - Data jsou validována na frontendu i backendu
   - HintController zpracuje požadavek
   - HintRepository provede databázové operace
   - Související ReasonRepository vytvoří záznamy důvodů

3. **Zobrazení Dat**
   - Požadavek na zobrazení hintů
   - HintController získá data z repository
   - Data jsou předána do view
   - View vykreslí data


### Klíčové Procesy

1. **Registrace Uživatele**
   - Validace vstupních dat
   - Kontrola unikátnosti uživatelského jména
   - Upload profilového obrázku a jeho zpracování pomocí třídy ImageProcessor:
     - Obrázek je dekódován z base64 formátu
     - Dočasně uložen na serveru
     - Zpracován (změna velikosti, konverze formátu na WebP)
     - Uložen do složky s profilovými obrázky
   - Vytvoření uživatelského účtu
   - Automatické přihlášení

2. **Vytvoření Hintu**
   - Kontrola oprávnění uživatele
   - Validace vstupních dat
   - Transakční zpracování (hint + reasons)
   - Aktualizace souvisejících dat


### Zabezpečení a Validace

1. **Víceúrovňová Validace**
   - Frontend: JavaScript validace v reálném čase
   - Backend: PHP validace všech vstupů pomocí Validator třídy:
     - Centralizovaná validační logika v `common/Validator.php`
     - Statické metody pro různé typy validací:
       - `isRequired()`: Kontrola povinných polí
       - `isString()`: Validace řetězců včetně délky
       - `isPassword()`: Komplexní validace hesla
       - `isStringArray()`: Validace polí řetězců
       - `isInt()`: Validace celých čísel s rozsahem
       - `isDate()`: Kontrola platnosti data
       - `isProfileImage()`: Validace nahrávaných obrázků
   - Databáze: Integritní omezení

2. **Bezpečnostní Mechanismy**
   - **CSRF ochrana pro všechny formuláře**
      - Princip: Každý formulář obsahuje unikátní CSRF token generovaný pomocí `random_bytes(32)`, který je uložen v session a odeslán s formulářem.
      - Implementace:
        - `CSRF::generate()`: Vytváří token a vkládá ho do formuláře.
        - `CSRF::validate($csrf_token)`: Ověřuje token při zpracování POST požadavku.
      - Příklad:
        ```php
        if (CSRF::validate($data['csrf_token'])) {
          $authController->login($username, $password);
        } else {
          HTTPException::sendException(400, 'CSRF token is invalid');
        }```
   - Prepared statements pro SQL dotazy
   - Hashování citlivých dat
   - Session management

3. **Chráněné části**
   - Účel: Zajištění, že pouze autorizovaní uživatelé mají přístup k určitým částem aplikace.
   - Implementace:
     - Middleware: AuthController obsahuje metodu `protectedRoute()`, která slouží jako middleware pro kontrolu oprávnění.
     - Přesměrování: Pokud uživatel nesplňuje podmínky, je přesměrován na přihlašovací stránku nebo na hlavní stránku.
     - Parametry:
       - `isAdminRoute`: Určuje, zda je trasa určena pouze pro administrátory.
       - `blockLoggedUsers`: Určuje, zda má být přístup zablokován pro již přihlášené uživatele (např. přihlašovací a registrační stránky).
    
     
4. **Zpracování Chyb**
   - Implementace vlastní HTTPException třídy pro jednotné zpracování chyb
   - Automatická konverze chyb do JSON formátu:
   ```json
   {
     "code": 400,
     "message": "Username must be at least 3 characters long"
   }
   ```
   - Konzistentní formát chybových odpovědí napříč aplikací
   - Automatické nastavení HTTP hlaviček


## Struktura Projektu
   
### Klíčové Komponenty

#### 1. Databázová Vrstva (Repositories)
- **AuthRepository**: Spravuje autentizaci a správu uživatelů. Poskytuje metody pro registraci, přihlášení/odhlášení, správu sessions a manipulaci s uživatelskými účty včetně profilových obrázků. Umožňuje získávání informací o uživatelích a správu uživatelských rolí.
- **HintRepository**: Zajišťuje operace s hinty. Poskytuje metody pro získávání hintů s podporou stránkování, přidávání/úpravu/mazání hintů a doporučování náhodných hintů z každé kategorie. Spolupracuje s ostatními repositories pro kompletní data.
- **CategoryRepository**: Spravuje kategorie pro hinty. Poskytuje základní metody pro získání všech kategorií nebo konkrétní kategorie podle ID. Každá kategorie obsahuje identifikátor a název.
- **ReasonRepository**: Spravuje důvody/vysvětlení k jednotlivým hintům. Umožňuje získávání důvodů pro konkrétní hint, přidávání nových důvodů a hromadnou aktualizaci důvodů v rámci jedné transakce.

#### 2. Systém Pohledů (Views)
- **layout.php**: Hlavní šablona definující základní strukturu stránek
  - Zajišťuje konzistentní strukturu HTML napříč aplikací
  - Spravuje načítání CSS a JavaScript souborů
  - Obsahuje responzivní navigaci s podmíněným zobrazením prvků podle role uživatele
  - Zobrazuje profilový obrázek a jméno přihlášeného uživatele
    - **Komponenty layoutu**:
      - Navigace: Dynamicky generovaná podle stavu přihlášení
        - Pro nepřihlášené: Login a Register odkazy
        - Pro přihlášené: Home, All Hints, Add hint
        - Pro adminy: Navíc Manage Users sekce
      - Profilová sekce: Zobrazuje profilový obrázek, username a případně admin badge
      - Container systém pro konzistentní rozložení

- **add_hint.php**: Formulář pro přidávání a editaci hintů
  - Podpora editace existujících hintů
  - Dynamické přidávání/odebírání důvodů (minimálně 2)
  - Validace všech povinných polí
  - Výběr kategorie z dropdown menu

- **admin_users.php**: Správa uživatelů pro administrátory
  - Seznam uživatelů s profilovými obrázky
  - Možnost změny role (User/Admin)
  - Možnost smazání uživatele
  - Ochrana proti smazání vlastního účtu

- **hint_detail.php**: Detailní zobrazení hintu
  - Zobrazuje titulek, kategorii, popis
  - Seznam důvodů
  - Informace o autorovi a datu vytvoření
  - Navigační tlačítko zpět na seznam

- **hints.php**: Seznam všech hintů
  - Stránkování s navigačními šipkami
  - Zobrazení čísel stránek

- **login.php** a **register.php**: Autentizační formuláře
  - Login: Username a heslo s validací
  - Register: Username, heslo, potvrzení hesla
  - Register navíc: Datum narození (rok/měsíc/den)
  - Register navíc: Upload profilového obrázku s drag & drop podporou

#### 3. Veřejné Assety (Public)

##### JavaScript (/public/scripts/)
- AJAX komunikace se serverem: Umožňuje asynchronní interakci s backendem.
- Validace na straně klienta: Zajišťuje kontrolu vstupních dat před jejich odesláním.

##### CSS (/public/styles/)
- **global/**: Základní styly, reset, utility třídy. Zajišťují konzistentní vzhled napříč celou aplikací.
- **scoped/**: Specifické styly pro jednotlivé stránky/komponenty. 

## Další části aplikace

### Systém Rolí
- Běžný uživatel: Základní operace s hinty, jako je jejich prohlížení a přidávání. Dále editace a mazání pouze u svých hintů.
- Administrátor: Správa uživatelů a rozšířené možnosti, jako je úprava a mazání všech hintů.

### Správa Souborů
- Zpracování a optimalizace nahrávaných obrázků: Zajišťuje efektivní ukládání a zobrazení obrázků.
- Bezpečné ukládání profilových fotografií: Zajišťuje ochranu a správu uživatelských obrázků.
- Automatické mazání souvisejících souborů: Zajišťuje údržbu a správu souborového systému.

1. **ImageProcessor**
   - **Konstanty**:
     - `MAX_WIDTH`, `MAX_HEIGHT`: 150px - Maximální rozměry profilových obrázků
     - `UPLOAD_DIR`: Cesta k adresáři pro ukládání profilových fotek (default - /../public/uploads/profiles/)
     - `WEBP_QUALITY`: 90 - Kvalita WebP komprese (0-100)

   - **Proces Zpracování Obrázku**:

     1. **Validace a Příprava**:
        - Podporované formáty: JPEG, PNG, WebP

     2. **Zpracování**:
        - Výpočet nových rozměrů se zachováním poměru stran
        - generování názvů souborů (user ID + .webp)

     3. **Resize a Konverze**:
        - Použití `imagecopyresampled` pro změnu velikosti
        - Konverze do WebP formátu pomocí `imagewebp`
        - Automatické čištění paměti pomocí `imagedestroy`


### Databázové Schéma
1. **Users**
   - Ukládá informace o uživatelích
   - Role: 0 = běžný uživatel, 1 = admin
   - Hesla jsou hashována pomocí BCRYPT
   - Unikátní username pro identifikaci
   ```json
   {
     "id": "int NOT NULL AUTO_INCREMENT",
     "username": "varchar(50) NOT NULL UNIQUE",
     "password": "varchar(255) NOT NULL",
     "birth": "date NOT NULL",
     "role": "int NOT NULL DEFAULT '0'",
     "created_at": "datetime NOT NULL DEFAULT CURRENT_TIMESTAMP"
   }
   ```

2. **Hints**
   - Hlavní tabulka pro ukládání tipů
   - Kaskádové mazání při smazání uživatele
   - Vazba na kategorii a autora
   ```json
   {
     "id": "int NOT NULL AUTO_INCREMENT",
     "user_id": "int NOT NULL",
     "title": "varchar(255) NOT NULL",
     "description": "text NOT NULL",
     "created_at": "timestamp NULL DEFAULT CURRENT_TIMESTAMP",
     "category_id": "int NOT NULL",
     "foreign_keys": {
       "user_id": "REFERENCES users(id) ON DELETE CASCADE",
       "category_id": "REFERENCES categories(id)"
     }
   }
   ```


3. **Categories**
   - Základní kategorie pro třídění tipů
   - Předem definované hodnoty (Food, Film, Furniture - tyto jsou pouze pro testování)

   ```json
   {
     "id": "int NOT NULL AUTO_INCREMENT",
     "name": "varchar(100) NOT NULL"
   }
   ```

4. **Reasons**
   - Ukládá důvody/vysvětlení k jednotlivým tipům
   - Kaskádové mazání při smazání nadřazeného tipu
   - Minimálně dva důvody pro každý tip
   ```json
   {
     "id": "int NOT NULL AUTO_INCREMENT",
     "hint_id": "int NOT NULL",
     "value": "varchar(255) NOT NULL",
     "foreign_keys": {
       "hint_id": "REFERENCES hints(id) ON DELETE CASCADE"
     }
   }
   ```

### Relační Vazby
- User (1:N) Hints: Jeden uživatel může mít více tipů
- Category (1:N) Hints: Jedna kategorie může obsahovat více tipů
- Hint (1:N) Reasons: Jeden tip má více důvodů/vysvětlení
