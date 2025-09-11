---

### [Git Issue 373](https://git.etes.de/edira/edira/-/issues/373)

---

## Breakdown

Aktuell werden die `Stammdaten` Informationen in den `Firmen Einstellungen` in verschiedene Unterkategorien aufgeteilt und als einzelne Seiten zur Verfügung gestellt. Da die einzelnen Einstellungen keinen großen Umfang haben, sollen sie auf einer Stammdaten Seite zusammengefasst und schneller abrufbar gemacht werden. 

Zudem soll die aktuell vorhandene Kategorie `Firmeninformationen` mit der Unterkategorie `Verantwortlicher` zusammengefasst werden, da hier die gleichen Informationen bearbeitet werden können und dementsprechend redundant sind. Die neue Navigationsleiste soll so strukturiert werden:

```
Stammdaten
---
User
Mitarbeiter
Abteilungen
---
Schadenswerte
Eintrittswahrscheinlichkeiten
---
2FA
Sprache
```

---

![Redundante Einstellungen](../src/img/1.png)

---

Im gleichen Zug soll die Datenbank angepasst werden. Aktuell werden alle Stammdaten Informationen im Table `master_records` in zwei Columns umständlich gespeichert.


![Aktueller master_records table](../src/img/2.png)

---

Hier soll für jede Unterkategorie einen eigenenr Table angelegt werden:

`company_information`:

| id  | tenant_id | first_name | last_name | phone | email | address_different | address_street | address_zip | address_town | created_at | updated_at |
| | | | | | | | | | | | |
| | | | | | | | | | | | |

---

`company_authorized_representative`:

| id  | tenant_id | first_name | last_name | phone | email | address_different | address_street | address_zip | address_town | created_at | updated_at |
| | | | | | | | | | | | |
| | | | | | | | | | | | |          

---

`company_representive_eu`:

| id  | tenant_id | company_name | first_name | last_name | phone | email | address_street | address_zip | address_town | created_at | updated_at |
| | | | | | | | | | | | |
| | | | | | | | | | | | |

---

`company_data_protection_officer`:

| id  | tenant_id | first_name | last_name | phone | address_different | email | company_name | address_street | address_zip | address_town | created_at | updated_at |
| | | | | | | | | | | | | |
| | | | | | | | | | | | | |

--- 

`company_supervisory_authority`:

| id  | tenant_id | company_name | address_street | address_zip | address_town | phone | email | website | question | created_at | updated_at |
| | | | | | | | | | | | | 
| | | | | | | | | | | | | 

---

`company_data_third_countries`:

| id  | tenant_id |  question | created_at | updated_at |
| | | | | |
| | | | | | 

---

## Solution

**1. `app/Http/Livewire/Company/MasterRecords.php` erstellen**
   
Dieser Livewire Component rendert die Master Settings Overview Page auf der Company Settings Page.

---

**2. `resources/views/livewire/company/master-records.blade.php` erstellen**
   
Diese View rendert alle Dropdown Menus für die einzelnen Master Settings.

---

**3. `web.php` anpassen und Router optmieren**

```php
Route::get('/', App\Http\Livewire\Company\Information::class)->name('information');
->
Route::get('/', App\Http\Livewire\Company\MasterRecords::class)->name('master-records');
```

```php
Route::get('/verantwortlicher', App\Http\Livewire\Company\MasterRecords\Verantwortlicher::class)->name('verantwortlicher'); 
-> 
Route::get('/information', App\Http\Livewire\Company\MasterRecords\Information::class)->name('information');
```

---

**4. `MasterRecords.php` namespace ändern**

`namespace App\Http\Livewire\Company;`

---

**5. `Information.php`**
   
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

---

**6. `resources/views/components/nav/collections/user-dropdown.blade.php` Landing Route ändern**

```php
@hasRole('manager')
    <x-nav.links.dropdown route="company.master-records" :mobile="$mobile"
        icon="briefcase">{{ __('Company Settings') }}</x-nav.links.dropdown>
@endhasRole
```

---

**7. `app/Http/Livewire/Company/MasterRecords.php` title und layout hinzufügen**

```php
public function render()
{
    return view('livewire.company.master-records')
        ->layout('layouts.settings.company', ['title' => __('company.master_records')]);
}
```

---

**8. `resources/views/layouts/settings/company.blade.php`**

```
<x-nav.links.settings route="company.information" icon="adjustments">{{ __('company.general.title') }}</x-nav.links.settings> 
->
<x-nav.links.settings route="company.master-records" icon="adjustments">{{ __('company.master_records') }}</x-nav.links.settings>
```
user nav zu mitarbeiter und abteilungen

alle altern master records navs löschen

---

**9.  `resources/views/livewire/company/master-records.blade.php`**

Livewire Komponenten der Subkategorien als Collabsible einfügen.

---

**10.   `gesetzlicher-vertreter.blade.php`**

Verantwortlicher Route -> Information Route

---

**11.   `datenschutzbeauftragter.blade.php`**

Verantwortlicher Route -> Information Route


