# [Git Issue 373](https://git.etes.de/edira/edira/-/issues/373)

---

# 1. Breakdown

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

# 2. Preparations

## 2.1 Master Settings Component & View creation

The master setting component and view will be used to display the overview page mit all master settings listed.

```bash
php artisan make:livewire Company/MasterRecords
```

!!! Result
    ```
    app/Http/Livewire/Company/MasterRecords.php
    resources/views/livewire/company/master-records.blade.php
    ```

!!! Note
    The content of the view file is explained under 3.4 Nav Link Update

---

## 2.2 Router Update

`routes/web.php`

```php
/*
|--------------------------------------------------------------------------
| Company Settings Routes
|--------------------------------------------------------------------------
    */
Route::middleware(['role:manager'])->prefix('company')->name('company.')->group(function () {
    // Company Settings Landing Page
    Route::get('/', MasterRecords::class)->name('master-records');

    // Master Setting Group
    Route::prefix('master-records')->name('master-records.')->group(function () {
        // Master Record Settings
        Route::get('/information', Information::class)->name('information');
        Route::get('/authorized-representative', AuthorizedRepresentative::class)->name('authorized-representative');
        Route::get('/eu-representative', EuRepresentative::class)->name('eu-representative');
        Route::get('/data-protection-officer', DataProtectionOfficer::class)->name('data-protection-officer');
        Route::get('/responsible-supervisory-authority', ResponsibleSupervisoryAuthority::class)->name('responsible-supervisory-authority');
        Route::get('/data-third-country', DataThirdCountry::class)->name('data-third-country');
    });
    
    // User, Employees & Departments Setting Routes
    Route::get('users', Users::class)->name('users');
    Route::get('employees', Employees::class)->name('employees');
    Route::get('departments', Departments::class)->name('departments');

    // Damage & Probability Setting Routes
    Route::get('damage', Damage::class)->name('damage');
    Route::get('probability', Probability::class)->name('probability');
    
    // 2FA & Language Setting Routes
    Route::get('/2fa', App\Http\Livewire\Company\TwoFA::class)->name('2fa');
    Route::get('/module-languages', [ModuleLanguagesOverviewController::class, 'index'])->name('module-languages');
});
```

!!! Note
    The middleware for the company settings get structured in their new hierarchy and order. 

    The route names are transferred to english. 

    Redundant or old routes gets deleted.

---

## 2.3 Nav Link Updates

`resources/views/components/nav/collections/user-dropdown.blade.php`

```php
{{-- User has to be manager to access this page --}}
@hasRole('manager')
    <x-nav.links.dropdown route="company.master-records" :mobile="$mobile"
        icon="briefcase">{{ __('Company Settings') }}</x-nav.links.dropdown>
@endhasRole
```

!!! Note
    This is the dropdown menu on the top right corner where you can access the company settings. 

    The link got for the menu element got updated to direct to the defined landing route for the master records settings.

---

`resources/views/layouts/settings/company.blade.php`

```php
<x-layouts.app title="{{ $title }}">
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <aside class="space-y-3 px-2 py-6 sm:px-6 lg:col-span-3 lg:px-0 lg:py-0">

            {{-- Master Records --}}
            <nav class="space-y-1">
                <x-nav.dropdown.toggle base-route="company.master-records" icon="clipboard-list">
                    {{ __('company.master_records') }}
                    <x-slot name="links">
                        <x-nav.dropdown.link
                            route="company.master-records">{{ __('company.general.all') }}</x-nav.dropdown.link>
                        <x-nav.dropdown.link
                            route="company.master-records.information">{{ __('company.general.title') }}</x-nav.dropdown.link>
                        <x-nav.dropdown.link
                            route="company.master-records.authorized-representative">{{ __('company.authorized_representative.title') }}</x-nav.dropdown.link>
                        <x-nav.dropdown.link
                            route="company.master-records.data-protection-officer">{{ __('company.data_protection_officer.title') }}</x-nav.dropdown.link>
                        <x-nav.dropdown.link
                            route="company.master-records.responsible-supervisory-authority">{{ __('company.responsible_supervisory_authority.title') }}</x-nav.dropdown.link>
                        <x-nav.dropdown.link
                            route="company.master-records.data-third-country">{{ __('company.data_third_country.title') }}</x-nav.dropdown.link>
                    </x-slot>
                </x-nav.dropdown.toggle>
            </nav>

            {{-- Nav Divider --}}
            <div class="flex">
                <div class="w-full border-t border-gray-300"></div>
            </div>

            {{-- User, Employee & Departments Navs --}}
            <nav class="space-y-1">
                <x-nav.links.settings route="company.users"
                    icon="user-circle">{{ __('forms.labels.user') }}</x-nav.links.settings>
                <x-nav.links.settings route="company.employees"
                    icon="briefcase">{{ __('company.employees.title') }}</x-nav.links.settings>
                <x-nav.links.settings route="company.departments"
                    icon="briefcase">{{ __('forms.labels.departments') }}</x-nav.links.settings>
            </nav>

            {{-- Nav Divider --}}
            <div class="flex">
                <div class="w-full border-t border-gray-300"></div>
            </div>

            {{-- Classification, Damage Probability Navs --}}
            <nav class="space-y-1">
                <x-nav.links.settings route="company.damage"
                    icon="fire">{{ __('company.damage_scores') }}</x-nav.links.settings>
                <x-nav.links.settings route="company.probability"
                    icon="refresh">{{ __('company.probability_scores') }}</x-nav.links.settings>
            </nav>

            {{-- Nav Divider --}}
            <div class="flex">
                <div class="w-full border-t border-gray-300"></div>
            </div>

            {{-- Language, 2FA --}}
            <nav class="space-y-1">
                @if (Auth::user()->role == 'admin' || Auth::user()->role == 'manager')
                    <x-nav.links.settings route="company.2fa"
                        icon="device-phone-mobile">{{ __('company.2fa.title') }}</x-nav.links.settings>
                @endif
                <x-nav.links.settings route="company.module-languages" icon="globe-europe-africa">
                    {{ __('company.languages.title') }}
                </x-nav.links.settings>
            </nav>
        </aside>

        {{-- The Livewire Component and View for each Setting Category is loaded through the $slot variable --}}
        <div class="space-y-6 sm:px-6 lg:col-span-9 lg:px-0">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>
```

!!! Note 
    This is the sidebar navigation component that gets used inside other components.

    It got updated to include the new structure of the company settings.

--- 

## 2.4 Gui Group Action Master Record Overview Page

# 3. Solution

Individual Master Records Settings Rework

### 3.1 Component / View / Model Creation
 
```bash
php artisan make:livewire Company/MasterRecords/Information
php artisan make:livewire Company/MasterRecords/AuthorizedRepresentative
php artisan make:livewire Company/MasterRecords/EuRepresentative
php artisan make:livewire Company/MasterRecords/DataSecurityOfficer
php artisan make:livewire Company/MasterRecords/ResponsibleSupervisoryAuthority
php artisan make:livewire Company/MasterRecords/DataThirdCountry
```

!!! Note
    Generates the component and blade view .php file

---

```bash
php artisan make:model MasterRecords/CompanyInformation
php artisan make:model MasterRecords/CompanyAuthorizedRepresentative
php artisan make:model MasterRecords/CompanyEuRepresentative
php artisan make:model MasterRecords/CompanyDataSecurityOfficer
php artisan make:model MasterRecords/CompanyResponsibleSupervisoryAuthority
php artisan make:model MasterRecords/CompanyDataThirdCountry
```

!!! Note 
    Generates the eloquent model .php file

---

### 3.2 Database Schema Creation

`database/migrations/2021_02_08_153929_create_master_records_table.php`:

#### 3.2.1 Company Information

```php
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
```

!!! Note 
    This creates the table 'company_information' when the edira database is seeded.

##### 3.2.1 Result

`company_information:`

| id  | tenant_id | company_name | top_management | address_street | address_zip | address_town | responsible_person_first_name | responsible_person_last_name | created_at | updated_at |
| | | | | | | | | | | |
| | | | | | | | | | | |

---

#### 3.3.2 Authorized Representative

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

!!! Note 
    This creates the table 'company_authorized_representative' when the edira database is seeded.

##### 3.2.2 Result

`company_authorized_representative:`

| id  | tenant_id | first_name | last_name | phone | email | address_different | address_street | address_zip | address_town | created_at | updated_at |
| | | | | | | | | | | | |
| | | | | | | | | | | | |

---

#### 3.2.3 EU Representative

```php
Schema::create('company_eu_representative', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('company_name')->index();
    $table->string('first_name')->index();
    $table->string('last_name')->index();
    $table->string('phone')->index();
    $table->string('email')->index();
    $table->string('address_street')->index();
    $table->string('address_zip')->index();
    $table->string('address_city')->index();
    $table->timestamps();

    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->unique('tenant_id');
});
```

!!! Note 
    This creates the table 'company_eu_representative' when the edira database is seeded.

##### 3.2.3 Result

`company_eu_representative:`

| id  | tenant_id | company_name | first_name | last_name | phone | email | address_street | address_zip | address_town | created_at | updated_at |
| | | | | | | | | | | | | 
| | | | | | | | | | | | |

---

#### 3.2.4 Data Protection Officer

```php
Schema::create('company_data_protection_officer', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('first_name')->index();
    $table->string('last_name')->index();
    $table->string('phone')->index();
    $table->string('email')->index();
    $table->boolean('address_different')->index()->default(false);
    $table->string('company_name')->index()->nullable();
    $table->string('address_street')->index()->nullable();
    $table->string('address_zip')->index()->nullable();
    $table->string('address_city')->index()->nullable();
    $table->timestamps();

    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->unique('tenant_id');
});
```

!!! Note 
    This creates the table 'company_data_protection_officer' when the edira database is seeded.

##### 3.2.4 Result

`company_data_protection_officer:`

| id  | tenant_id | first_name | last_name | phone | address_different | email | company_name | address_street | address_zip | address_town | created_at | updated_at |
| | | | | | | | | | | | | |
| | | | | | | | | | | | | |

---

#### 3.2.5 Responsible Supervisory Authority

```php
Schema::create('company_responsible_supervisory_authority', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('company_name')->index();
    $table->string('address_street')->index();
    $table->string('address_zip')->index();
    $table->string('address_city')->index();
    $table->string('phone')->index();
    $table->string('email')->index();
    $table->string('website')->index();
    $table->boolean('question')->nullable();
    $table->timestamps();

    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->unique('tenant_id');
});
```

!!! Note 
    This creates the table 'company_responsible_supervisory_authority' when the edira database is seeded.

##### 3.2.5 Result

`company_responsible_supervisory_authority:`

| id  | tenant_id | company_name | address_street | address_zip | address_town | phone | email | website | question | created_at | updated_at |
| | | | | | | | | | | | | 
| | | | | | | | | | | | | 

---

#### 3.2.6 Data Third Country

```php
Schema::create('company_data_third_country', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->boolean('question')->nullable();
    $table->timestamps();

    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
    $table->unique('tenant_id');
});
```

!!! Note 
    This creates the table 'company_data_third_country' when the edira database is seeded.

##### 3.2.6 Result

`company_data_third_country:`

| id  | tenant_id |  question | created_at | updated_at |
| | | | | |
| | | | | | 

---

### 3.3 Bilingual Updates

`resources/lang/en*de/company.php`

```php
'master_records' => 'Master Records',
'general' => [
    'title' => 'Company Information',
    'description' => 'General Company Information',
    'company_name' => 'Company Name',
    'responsible_person' => 'Responsible Person',
    'address' => 'Address',
    'top_management' => 'Highest Organ of Corporate Management',
    'company_logo' => 'Company Logo',
    'auditor_logo' => 'Auditor Logo',
    'no_logo' => 'No logo uploaded yet',
    'all' => 'Overview',
],
'authorized_representative' => [
    'title' => 'Authorized Representative',
    'if_different_from' => 'If Different From',
],
'eu_representative' => [
    'title' => 'Representative in the EU',
],
'data_protection_officer' => [
    'title' => 'Data Protection Officer',
],
'responsible_supervisory_authority' => [
    'title' => 'Responsible Supervisory Authority',
    'question' => 'Notification of the/their Data Protection Officer has been made?',
],
'data_third_country' => [
    'title' => 'Situations Regarding Transfers to Third Countries',
    'question' => 'Is Data Transferred to Third Countries?',
],
```

---

`resources/lang/de*en/forms.php`

```php
'address_street' => 'Straße',
'address_zip' => 'PLZ',
'address_city' => 'Stadt',
'contact' => 'Kontaktdaten',
```

!!! Note
    These are important for the bilingual integration.

### 3.4 Nav Link Update

`resources/views/livewire/master-records.php`;

```php
<div>
    
    /* Collabsible row for individual setting  */
    <x-blank-collapse class="mt-4 cursor-pointer bg-white shadow hover:bg-gray-50">
        <x-slot name="heading" class="flex justify-between px-5 py-4 font-semibold">
            <div class="flex">
                <div class="flex items-center pr-2">
                    <div x-show="!open">
                        <x-icon.chevron-right size="4" />
                    </div>
                    <div x-show="open" x-cloak>
                        <x-icon.chevron-down size="4" />
                    </div>
                    <h2 class="px-2">{{ __('company.settingname.title') }}</h2>
                </div>
            </div>
        </x-slot>

        <x-slot name="content" class="divide-y-2 border-gray-400 p-2">
            @livewire('company.master-records.settingname')
        </x-slot>
    </x-blank-collapse>
</div>
```

!!! Note
    This view lists all master records settings on one page. It integrates each setting with a `@livewire` element in the view.

### 3.5 View Content

You can find the content of the blade view .php files here:

`resources/views/livewire/company/master-records/*.blade.php`

---

### 3.6 DB Model

You can find the individual models for each master records setting under:

`app/Models/Company/MasterRecords/*.php`

The files all look like this:

```php
namespace App\Models\Company\MasterRecords;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
This model is used to create and update the authorized representative in the masterrecord settings.
*/

class CompanySettingsName extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'company_settigs_name';

    protected $fillable = [
        'variable_1',
        'variable_2',
        'variable_3',
        'variable_4',
        'variable_5',
        'variable_6',
        'variable_7',
        'variable_...',
    ];
}
```

!!! Note
    $table:  used because sometimes the automatic table name recognotion from laravel doesn't work.

    BelongsToTenant:  used to append tenant id

### 3.7 Tenant Function

`app/Models/Tenant.php`:

```php
/**
 * @return HasMany<CompanySettingName, $this>
 */
public function companysettingname(): HasMany
{
    return $this->hasMany(CompanySettingName::class)->withoutGlobalScope(TenantScope::class);
}

```

!!! Note
    These functions are used in the `save()` method in each component to check the tenant relationship.

---

### 3.8 Component Logic

Each component file that handles the logic is located in:

`app/Http/Livewire/Company/MasterRecords/*.php`

!!! Note
    The files all have the same structure.

#### 3.8.1 Class Declaration

```php
namespace App\Http\Livewire\Company\MasterRecords;

// Other master settings components
use App\Models\Company\MasterRecords\*.php

// Each settings uses the Tenant class
use App\Models\Tenant;

// Laravel classes
use Exception;
use Illuminate\Contracts\View\View;
use Livewire\Component;
```

!!! Note
    This part defines external dependencies.

---

```php
class SettingsName extends Component
{
// Tenant object declaration
public Tenant $tenant;

// Other settings model class object declaration
public CompanySettingName $info;

// Variables that will be used to store the data/information in the database declaration
public string $variable_1;
public string $variable_2;
public string $variable_3;
public string $variable_...;

```

!!! Note
    This part defines class variables.

---

#### 3.8.2 Input Rules

```php
// Input rules for the validation of the user input
/**
 * @var array<string, string>
 */
protected $rules = [
    'variable_1' => 'required|max:255',
    'variable_2' => 'required|max:255',
    'variable_3' => 'required|max:255',
    'variable_...' => 'required|max:255',
    'upload' => 'nullable|image|max:3000',
];
```

!!! Note
    This part defines the rules that later get used by the validate() function to validate the user input before applying the data to the database.

---

#### 3.8.3 On Mount Function

```php
// mount() function to bind already existing data from the database to frontend / variables
public function mount(): void
{
    if (auth()->user()?->tenant !== null) {

        /* Binds tenant to component */
        $this->tenant = auth()->user()->tenant;

        /* Checks if the user already set up a record in the database and fills the form fields with the data to edit them */
        try {
            $companySettingName = CompanySettingName::where('tenant_id', $this->tenant->id)->first();
            $this->variable_1 = $companySettingName->variable_1;
            $this->variable_2 = $companySettingName->variable_2;
            $this->variable_3 = $companySettingName->variable_3;
            $this->variable_... = $companySettingName->variable_...;
        } catch (Exception $e) {

        }
    }
}
```

!!! Note
    The mount() function gets used to check if there is already data stored in the database that needs to be displayed in the frontend. 

---

#### 3.8.4 Save Function


```php
// save() function that creates or updates the database with the user input
public function save(): void
{
    /* validates the form data based in the $rules */
    $this->validate();

    /* first checks the relationships and then creates or updates the records in the database using the upsert() method. The 'companysettingname' is used to check the tenant relationship */
    $this->tenant->companysettingname()->upsert(
        /* This array contains the data */
        [
            'tenant_id' => $this->tenant,
            'variable_1' => $this->variable_1,
            'variable_2' => $this->variable_2,
            'variable_3' => $this->variable_3,
            'variable_...' => $this->variable_...,
        ],
        /* This array defines what the row is unique/identified with */
        ['tenant_id'],
        /* This array defines which columns get updated */
        ['variable_1', 'variable_2', 'variable_3', 'variable_...']
    );

    /* Shows success notification */
    $this->alert('success', __('alert.saved'));
}
```

!!! Note 
    The save() function is used to create or update the records for the individual table in the database.

---

#### 3.8.5 Render Function


```php
public function render(): View
{
    return view('livewire.company.master-records.setting-name')
        ->layout('layouts.settings.company', ['title' => __('company.setting-name.title')]);
}
```

!!! Note
    The render() function renders the view for the individual setting. It uses the View class to use the predefined layout.

---

### 3.9 Router Update

`routes/web.php`

```php
// Company Settings Route
Route::middleware(['role:manager'])->prefix('company')->name('company.')->group(function () {
        // Master Settings Prefix
        Route::prefix('master-records')->name('master-records.')->group(function () {
            // Setting Route
            Route::get('/information', Information::class)->name('information');
            Route::get('/authorized-representative', AuthorizedRepresentative::class)->name('authorized-representative');
            Route::get('/eu-representative', EuRepresentative::class)->name('eu-representative');
            Route::get('/data-protection-officer', DataProtectionOfficer::class)->name('data-protection-officer');
            Route::get('/responsible-supervisory-authority', ResponsibleSupervisoryAuthority::class)->name('responsible-supervisory-authority');
            Route::get('/data-third-country', DataThirdCountry::class)->name('data-third-country');
        });
```

!!! Note
    The individual master settings route are nested in a group with the prefix 'master-settings.'. The group is nested in the middleware for the company settings with the prefix 'company.'.

!!! Example
    The route for the information setting is called 'company.master-records.information'

---

# 4. Clean Up

## 4.1 Deleted Files

Components:

OberstesOrganDerUnternehmensleitung.php
Verantwortlicher.php

Views:

oberstes-organ-der-unternehmensleitung.blade.php
verantwortlicher.blade.php


## 4.1 Seeder Update

AdminUserSeeder.php

UserSeeder.php

## 4.2 Usage in other Parts of edira

### 4.2.1 Tenant

getMasterRecord()

scopeWithName()

getNameAttribute()

masterrecords()

getAssessmentScores()


### 4.2.2 Actions

CreateDocumentFromTemplate.php
    replaceMasterRecords()

CreateTenant.php
    create()

SuggestionTranslator.php
    translate()

Damage.php

Probability.php

TenantFactory.php


### 4.2.3 Helpers 

MasterRecordHelper.php

EditRisk
    MasterRecordsHelper::for()

WithAssessmentScores.php

Score.php

### 4.2.2 Views

edit-document-template.blade.php

cover-header.blade.php

export.blade.php

cover-header.blade.php


### 4.2.5 Tests

WithMasterRecords.php

DamageTest.php

DatenschutzbeauftragterTest.php

GesetzlicherVertreterTest.php

OberstesOrganTest.php

ProbabilityTest.php

SachverhalteTest.php

VerantwortlicherTest.php

VertretetEUTest.php

ZuständigerTest.php

## 4.4 CI Pipeline

