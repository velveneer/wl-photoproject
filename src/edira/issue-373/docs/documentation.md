# Issue 373

[Git Issue 373](https://git.etes.de/edira/edira/-/issues/373)

In diesem Issue werden weitreichenden Änderungen für die `Firmen-Einstellungen` vorgenommen. In dieser Dokumentation sind die Tasks in die Einzelnen Bereiche der `Firmen-Einstellungen` unterteilt:

1. Stammdaten
2. Schadenswerte
3. Eintrittswahrscheinlichkeiten
4. Benutzer
5. Mitarbeiter 
6. Abteilungen

## Task 1 Stammdaten

### Breakdown

Aktuell werden die `Stammdaten` Informationen in den `Firmen Einstellungen` in verschiedene Unterkategorien aufgeteilt und als einzelne Seiten zur Verfügung gestellt. Da die einzelnen Einstellungen keinen großen Umfang haben, sollen sie auf einer Stammdaten Seite zusammengefasst und schneller abrufbar gemacht werden. 

Zudem soll die aktuell vorhandene Kategorie `Firmeninformationen` mit der Unterkategorie `Verantwortlicher` zusammengefasst werden, da hier die gleichen Informationen bearbeitet werden können und dementsprechend redundant sind. Die neue Navigationsleiste soll so strukturiert werden:

```
Stammdaten
2FA
Sprache
---
Schadenswerte
Eintrittswahrscheinlichkeiten
---
User
Mitarbeiter
Abteilungen
```

![Redundante Einstellungen](../src/img/1.png)

Im gleichen Zug soll die Datenbank angepasst werden. Aktuell werden alle Stammdaten Informationen im Table `master_records` in zwei Columns umständlich gespeichert.

![Aktueller master_records table](../src/img/2.png)

Hier soll für jede Unterkategorie einen eigenenr Table angelegt werden:

`company_information`:

| id  | tenant_id | company_name | top_management | address_street | address_zip | address_town | created_at | updated_at |
| --- | --------- | ------------ | -------------- | -------------- | ----------- | ------------ | ---------- | ---------- |
|     |           |              |                |                |             |              |            |            |

`company_authorized_representative`:

| id  | tenant_id | title | first_name | last_name | phone | email | address_street | address_zip | address_town |  created_at | updated_at |
| --- | --------- | ----- | ---------- | --------- | ----- | ----- | -------------- | ----------- | ------------ | ----------- | ---------- |
|     |           |       |            |           |       |       |                |             |              |             |            |            

`company_representive_eu`:

| id  | tenant_id | company_name | title | first_name | last_name | address_street | address_zip | address_town | phone | email | created_at | updated_at |
| --- | --------- | ------------ | ----- | ---------- | --------- | -------------- | ----------- | ------------ | ----- | ----- | ---------- | ---------- |
|     |           |              |       |            |           |                |             |              |       |       |            |            |
    
`company_data_protection_officer`:

| id  | tenant_id | title | first_name | last_name | phone | email | company_name | address_street | address_zip | address_town | created_at | updated_at |
| --- | --------- | ----- | ---------- | --------- | ----- | ----- | ------------ | -------------- | ----------- | ------------ | ---------- | ---------- |
|     |           |       |            |           |       |       |              |                |             |              |            |            |
  
`company_supervisory_authority`:

| id  | tenant_id | company_name | address_street | address_zip | address_town | phone | email | dpo_notified | created_at | updated_at |
| --- | --------- | ------------ | -------------- | ----------- | ------------ | ----- | ----- | ----- | ---------- | ---------- | 
|     |           |              |                |             |              |       |       |       |            |            |

`company_data_third_countries`:

| id  | tenant_id | data_transferred | created_at | updated_at |
| --- | --------- | ---------------- | ---------- | ---------- |
|     |           |                  |            |            |

`company_settings_categories`:

| id  | tenant_id | name | created_at | updated_at |
| --- | --------- | ---- | ---------- | ---------- |
|     |           |      |            |            |

### Modified Files

```
.
├── /
│   └── /                  
│       └── /
│           └── /
│               └── .php  
└── /
    └── /                  
        └── /
            └── /
                └── .php  
```
### Solution

#### Routes and Navigation

1. `app/Http/Livewire/Company/MasterRecords.php` erstellen
   
    Dieser Livewire Component rendert die Master Settings Overview Page auf der Company Settings Page.

2. `resources/views/livewire/company/master-records.blade.php` erstellen
   
    Diese View rendert alle Dropdown Menus für die einzelnen Master Settings.

3. `web.php`: 

    `Route::get('/', App\Http\Livewire\Company\Information::class)->name('information');` -> `Route::get('/', App\Http\Livewire\Company\MasterRecords::class)->name('master-records');`

    `Route::get('/verantwortlicher', App\Http\Livewire\Company\MasterRecords\Verantwortlicher::class)->name('verantwortlicher');` -> `Route::get('/information', App\Http\Livewire\Company\MasterRecords\Information::class)->name('information');`

    Der Router wird optimiert

4. `MasterRecords.php` namespace ändern: 

    `namespace App\Http\Livewire\Company;`

5. `Information.php` 
   
    Namespace ändern:

    `namespace App\Http\Livewire\Company\MasterRecords;`

    View Route ändern:

    ```php
    public function render(): View
    {
        return view('livewire.company.master-records.information')
            ->layout('layouts.settings.company', ['title' => __('company.general.title')]);
    }
    ```

6. `resources/views/components/nav/collections/user-dropdown.blade.php` Landing Route ändern: 

    ```php
    @hasRole('manager')
        <x-nav.links.dropdown route="company.master-records" :mobile="$mobile"
            icon="briefcase">{{ __('Company Settings') }}</x-nav.links.dropdown>
    @endhasRole

    ```

7. `app/Http/Livewire/Company/MasterRecords.php` title und layout hinzufügen:

    ```php
    public function render()
    {
        return view('livewire.company.master-records')
            ->layout('layouts.settings.company', ['title' => __('company.master_records')]);
    }
    ```

8. `resources/views/layouts/settings/company.blade.php`:

    `<x-nav.links.settings route="company.information" icon="adjustments">{{ __('company.general.title') }}</x-nav.links.settings>` -> `<x-nav.links.settings route="company.master-records" icon="adjustments">{{ __('company.master_records') }}</x-nav.links.settings>`

    user nav zu mitarbeiter und abteilungen
    alle altern master records navs löschen

9.  `resources/views/livewire/company/master-records.blade.php`: 

    Livewire Komponenten der Subkategorien als Collabsible einfügen:

10.  `gesetzlicher-vertreter.blade.php`:

    Verantwortlicher Route -> Information Route

11.  `datenschutzbeauftragter.blade.php`:

    Verantwortlicher Route -> Information Route

#### Datenbank

1. `database/migrations/2021_02_08_153929_create_master_records_table.php`: setting up tables in migration:

    ```php
    public function up()
    {
        Schema::create('company_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('company_name')->index();
            $table->string('top_management')->index();
            $table->string('address_street')->index();
            $table->string('address_zip')->index();
            $table->string('address_city')->index();
            $table->string('responsible_person_first_name')->index();
            $table->string('responsible_person_last_name')->index();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique('tenant_id');
        });
    }
    ```

#### Firmeninformationen

1. `CompanyInformation.php` Model für Informationsettings:

    Variablen für Datenbank:

    ```php
    protected $fillable = [
        'company_name',
        'top_management',
        'address_street',
        'address_zip',
        'address_town',
        'responsible_person_first_name',
        'responsible_person_last_name',
    ];
    ```

    Verweis auf Tenant und Factory hinzufügen:

    `use BelongsToTenant;`

2. `Information.php` Funktionalität:
   
    Variablen für User Input:

    ```php
    public Tenant $tenant;

    public ?TemporaryUploadedFile $upload = null;

    public CompanyInformation $info;

    public string $company_name;

    public string $top_management;

    public string $address_street;

    public string $address_zip;

    public string $address_city;

    public string $responsible_person_first_name;

    public string $responsible_person_last_name;
    ```

    Regeln für User Input:

    ```php
    /**
     * @var array<string, string>
     */
    protected $rules = [
        'company_name' => 'required|max:255',
        'top_management' => 'required|max:255',
        'address_street' => 'required|max:255',
        'address_zip' => 'required|max:255',
        'address_city' => 'required|max:255',
        'responsible_person_first_name' => 'required|max:255',
        'responsible_person_last_name' => 'required|max:255',
        'upload' => 'nullable|image|max:3000',
    ];
    ```

    Mount Funktion, die Tenant and Component bindet und checkt, ob bereits Einstellungen in der Datenbank gespeichert wurden:

    ```php
    public function mount(): void
    {
        if (auth()->user()?->tenant !== null) {

            /* Binds tenant to Component and sets up the tenant name as a the company name in the view */
            $this->tenant = auth()->user()->tenant;
            $this->company_name = $this->tenant->name;

            /* Checks if the user already set up a record in the database and fills the form fields with the data to edit them */
            try {
                $companyInfo = CompanyInformation::where('tenant_id', $this->tenant->id)->first();
                $this->company_name = $companyInfo->company_name;
                $this->top_management = $companyInfo->top_management;
                $this->address_street = $companyInfo->address_street;
                $this->address_zip = $companyInfo->address_zip;
                $this->address_city = $companyInfo->address_city;
                $this->responsible_person_first_name = $companyInfo->responsible_person_first_name;
                $this->responsible_person_last_name = $companyInfo->responsible_person_last_name;
            } catch (Exception $e) {

            }
        }
    }
    ```

    Save Funktion, um Änderungen in der Datenbank zu speichern:

    ```php
    public function save(): void
    {
        /* validates the form data based in the $rules */
        $this->validate();

        /* first checks the relationships and then creates or updates the records in the database using the upsert() method */
        $this->tenant->companyinformation()->upsert(
            /* This array contains the data */
            [
                'tenant_id' => $this->tenant,
                'company_name' => $this->company_name,
                'top_management' => $this->top_management,
                'address_street' => $this->address_street,
                'address_zip' => $this->address_zip,
                'address_city' => $this->address_city,
                'responsible_person_first_name' => $this->responsible_person_first_name,
                'responsible_person_last_name' => $this->responsible_person_last_name,
            ],
            /* This array defines what the row is unique/identified with */
            ['tenant_id'],
            /* This array defines which columns get updated */
            ['company_name', 'top_management', 'address_street', 'address_zip', 'address_city', 'responsible_person_first_name', 'responsible_person_last_name']
        );

        /* Updates company logo */
        if ($this->upload) {
            $this->tenant->updateLogo($this->upload);
        }

        /* Shows success notification */
        $this->alert('success', __('alert.saved'));
    }
    ```

3. `Tenant.php` hasMany Funktion hinzufügen:
   
    ```php
    public function companyinformation(): HasMany
    {
        return $this->hasMany(CompanyInformation::class)->withoutGlobalScope(TenantScope::class);
    }  
    ```

    Eloquent Funktion, um zu checken ob Tenant Objekt Relation zu CompanyInformation Objekt hat.

4. `information.blade.php` View für Company Information mit Funktionalität versehen und Input mit Component Variablen verbinden
   
#### Authorized Representative

1. `authorized-representative.blade.php` HTML-Elemente in View überarbeiten
   
2. `CompanyAuthorizedRepresentative.php` Eloquent Model für Datenbank erstellen

    ```php
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'address_different',
        'address_street',
        'address_zip',
        'address_city',
    ];
    ```

3. `2021_02_08_153929_create_master_records_table.php` Schema für eigenen Table erstellen

    ```php
    Schema::create('company_authorized_representative', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('tenant_id');
        $table->string('first_name')->index();
        $table->string('last_name')->index();
        $table->string('phone')->index();
        $table->string('email')->index();
        $table->boolean('address_different')->index()->default(false);
        $table->string('address_street')->index()->nullable();
        $table->string('address_zip')->index()->nullable();
        $table->string('address_city')->index()->nullable();
        $table->timestamps();

        $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        $table->unique('tenant_id');
    });
    ```
4. `AuthorizedRepresentative.php` Component überarbeiten

    Rules erstellen

    ```php
    protected $rules = [
        'first_name' => ['required', 'max:255'],
        'last_name' => ['required', 'max:255'],
        'phone' => ['required', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        'address_street' => ['sometimes', 'max:255'],
        'address_zip' => ['sometimes', 'max:255'],
        'address_city' => ['sometimes', 'max:255'],
    ];
    ```

    Prüfen ob schon Daten in der Datenbank hinterlegt sind:

    ```php
    public function mount(): void
    {
        if (auth()->user()?->tenant !== null) {

            // get tenant values from user
            $this->tenant = auth()->user()->tenant;

            // get values from database
            $companyInfo = CompanyInformation::where('tenant_id', $this->tenant->id)->first();
            $authorizedRepresentative = CompanyAuthorizedRepresentative::where('tenant_id', $this->tenant->id)->first();

            try {

                // if available assign first name of responsible person from the 'company_information' table and store it in variables
                $this->first_name = $companyInfo->responsible_person_first_name;
                $this->last_name = $companyInfo->responsible_person_last_name;
                $this->address_street = $companyInfo->address_street;
                $this->address_zip = $companyInfo->address_zip;
                $this->address_city = $companyInfo->address_city;

                // if available assign values from 'authorized_representative' table and store it in variables
                $this->first_name = $authorizedRepresentative->first_name;
                $this->last_name = $authorizedRepresentative->last_name;
                $this->phone = $authorizedRepresentative->phone;
                $this->email = $authorizedRepresentative->email;

                // when the address doesn't vary check if there is one in the 'company_information' table
                if ($authorizedRepresentative->address_different) {
                    $this->address_street = $authorizedRepresentative->address_street;
                    $this->address_zip = $authorizedRepresentative->address_zip;
                    $this->address_city = $authorizedRepresentative->address_city;
                }

            } catch (Exception $e) {

            }
        }
    }
    ```

    Daten in Datenbank speichern:

    ```php
    public function save(): void
    {
        /* validates the form data based in the $rules */
        $this->validate();

        /* first checks the relationships and then creates or updates the records in the database using the upsert() method */
        $this->tenant->authorizedrepresentative()->upsert(
            /* This array contains the data */
            [
                'tenant_id' => $this->tenant,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'address_different' => $this->address_different,
                'address_street' => $this->address_street,
                'address_zip' => $this->address_zip,
                'address_city' => $this->address_city,
            ],
            /* This array defines what the row is unique/identified with */
            ['tenant_id'],
            /* This array defines which columns get updated */
            ['first_name', 'last_name', 'phone', 'email', 'address_different', 'address_street', 'address_zip', 'address_city']
        );

        /* Shows success notification */
        $this->alert('success', __('alert.saved'));
    }
    ```

5. `Tenant.php` HasMany Funktion hinzufügen

    ```php
    /**
     * @return HasMany<CompanyAuthorizedRepresentative, $this>
     */
    public function authorizedrepresentative(): HasMany
    {
        return $this->hasMany(CompanyAuthorizedRepresentative::class)->withoutGlobalScope(TenantScope::class);
    }
    ```

<!-- TODO -->
<!-- 
- master-records.blade.php Schleife einbauen, nachdem Category Table angelegt wurde
- Verantwortlicher Ressourcen löschen, nachdem diese in Information eingebunden wurden
- gesetzlicher-vertreter.blade.php verantwortlicher.title anpassen
- datenschutzbeauftragter.blade.php verantwortlicher.title anpassen
- masterrecords view action bar einfügen
- Dokumenantation
- master-records table aus migration löschen
- alte Oberstes Organ Files löschen
- Bilingual 
- getMasterRecord Funktion entfernen
- button authorized representative zu same as company information
-->
### Result

## Task 2 Benutzer

### Breakdown

- ausgeschriebenes Bearbeiten soll zu Icon geändert werden 
- Bulk Action soll hinzugefügt werden 
  -> welche Bulkactions sollen hier möglich sein (eine war Benutzer deaktivieren) 
- Rechtschreibung Modal Benutzer erstellen anpassen

![Änderungen Benutzer Reiter](../src/img/3.png)

![Rechtschreibung Modal](../src/img/7.png)

### Modified Files

```
.
├── /
│   └── /                  
│       └── /
│           └── /
│               └── .php  
└── /
    └── /                  
        └── /
            └── /
                └── .php  
```

### Solution

### Result

## Task 3 Schadenswerte

### Breakdown

- Condition Zeichen sollen als Dropdown Menu in Input Field integriert werden (Default wie angezeigt) -> Häufig verwendete Symbole anschließend raus
- Inhaltliche Textanpassung
- Geld Werte sollen nach drei stellen durch Punk getrennt werden 

![Änderungen Eintrittswahrscheinlichkeits Reiter](../src/img/4.png)

### Modified Files

```
.
├── /
│   └── /                  
│       └── /
│           └── /
│               └── .php  
└── /
    └── /                  
        └── /
            └── /
                └── .php  
```

### Solution

### Result

## Task 4 Mitarbeiter

### Breakdown

- Tooltip Menu um Bulkaction auszuführen, nachdem mehrere Objekte ausgewählt wurden
- Rechtschreibung Modal Mitarbeiter erstellen anpassen
- Error wenn man nach abteilung sortiert

![Bulk Action Mitarbeiter Reiter](../src/img/5.png)
![Rechtschreibung Modal](../src/img/6.png)
![Bug Mitarbeiter](../src/img/10.png)

### Modified Files

```
.
├── /
│   └── /                  
│       └── /
│           └── /
│               └── .php  
└── /
    └── /                  
        └── /
            └── /
                └── .php  
```

### Solution

### Result

## Task 5 Abteilungen

### Breakdown

- Objektauswahl und Bulkaction hinzufügen
  -> welche Bulkactions werden noch mehr konkretisiert
- Rechtschreibung Modal Abteilung erstellen anpassen

![Bulk Actions Abteilungen](../src/img/8.png)

![Rechtschreibung Modal](../src/img/9.png)

Hier ist noch Rücksprache für das Verhalten erforderlich:
        
- Checkboxen können ausgewählt werden, entweder einzeln ausgewählt oder gleich alle (select all)
- Sobald ein oder mehrere Kästchen ausgewählt werden, erscheint ein Button/ kleines modal für Aktionen
- Aktionen: Löschen, exportieren (vllt gibt es später mal mehr)
- Löschen: erst möglich, wenn Bestätigung erfolgte

### Modified Files

```
.
├── /
│   └── /                  
│       └── /
│           └── /
│               └── .php  
└── /
    └── /                  
        └── /
            └── /
                └── .php  
```

### Solution

### Result

## Task Unklar

### Breakdown

- Reiter Vertraulichkeitsstufen für VVT
- `company.blade.php` translation in der Navbar

### Modified Files

```
.
├── /
│   └── /                  
│       └── /
│           └── /
│               └── .php  
└── /
    └── /                  
        └── /
            └── /
                └── .php  
```

### Solution

### Result