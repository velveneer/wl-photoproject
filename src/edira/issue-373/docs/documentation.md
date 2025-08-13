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

| id  | tenant_id | title | first_name | last_name | phone | email | address_street | address_zip | address_town | created_at | updated_at |
| --- | --------- | ----- | ---------- | --------- | ----- | ----- | -------------- | ----------- | ------------ | ---------- | ---------- |
|     |           |       |            |           |       |       |                |             |              |            |            |
  
`company_supervisory_authority`:

| id  | tenant_id | company_name | address_street | address_zip | address_town | phone | email | check | created_at | updated_at |
| --- | --------- | ------------ | -------------- | ----------- | ------------ | ----- | ----- | ----- | ---------- | ---------- | 
|     |           |              |                |             |              |       |       |       |            |            |

`company_data_third_countries`:

`company_top_management`:

`company_settings_category`:

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
2. `resources/views/livewire/company/master-records.blade.php` erstellen
3. `web.php`: 

`Route::get('/', App\Http\Livewire\Company\Information::class)->name('information');` -> `Route::get('/', App\Http\Livewire\Company\MasterRecords::class)->name('master-records');`

`Route::get('/verantwortlicher', App\Http\Livewire\Company\MasterRecords\Verantwortlicher::class)->name('verantwortlicher');` -> `Route::get('/information', App\Http\Livewire\Company\MasterRecords\Information::class)->name('information');`

4. `MasterRecords.php`: namespace ändern: 

`namespace App\Http\Livewire\Company;`

5. `Information.php`: 

- Namespace ändern:
`namespace App\Http\Livewire\Company\MasterRecords;`

- View Route ändern:
```php
public function render(): View
{
    return view('livewire.company.master-records.information')
        ->layout('layouts.settings.company', ['title' => __('company.general.title')]);
}
```

6. `resources/views/components/nav/collections/user-dropdown.blade.php`: Landing Route ändern: 

```php
@hasRole('manager')
    <x-nav.links.dropdown route="company.master-records" :mobile="$mobile"
        icon="briefcase">{{ __('Company Settings') }}</x-nav.links.dropdown>
@endhasRole

```

7. `app/Http/Livewire/Company/MasterRecords.php`: title und layout hinzufügen:

```php
public function render()
{
    return view('livewire.company.master-records')
        ->layout('layouts.settings.company', ['title' => __('company.master_records')]);
}
```

8. `resources/views/layouts/settings/company.blade.php`:

`<x-nav.links.settings route="company.information" icon="adjustments">{{ __('company.general.title') }}</x-nav.links.settings>` -> `<x-nav.links.settings route="company.master-records" icon="adjustments">{{ __('company.master_records') }}</x-nav.links.settings>`

- user nav zu mitarbeiter und abteilungen
- alle altern master records navs löschen

9. `resources/views/livewire/company/master-records.blade.php`: 

- Livewire Komponenten der Subkategorien als Collabsible einfügen:

10. `gesetzlicher-vertreter.blade.php`:

- Vertreter Route -> Information Route

11. `datenschutzbeauftragter.blade.php`:

- Vertreter Route -> Information Route

#### Datenbank



1. 

<!-- TODO -->
<!-- 
- master-records.blade.php Schleife einbauen, nachdem Category Table angelegt wurde
- Verantwortlicher Ressourcen löschen, nachdem diese in Information eingebunden wurden
- gesetzlicher-vertreter.blade.php verantwortlicher.title anpassen
- datenschutzbeauftragter.blade.php verantwortlicher.title anpassen
- masterrecords view action bar einfügen
- Dokumenantation
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