
## Solution

**1. `ResponsibleSupervisoryAuthority.php` Make Livewire Component with View**

```bash
php artisan make:livewire Company/MasterRecords/ResponsibleSupervisoryAuthority
```

---

**2. `CompanyResponsibleSupervisoryAuthority.php` Make Eloquent Model**

```bash
php artisan make:model MasterRecords/CompanyResponsibleSupervisoryAuthority
```

---

**3. `2021_02_08_153929_create_master_records_table.php` Create DB Schema**

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

---

**4. `company.php` Language anpassen**

```php
'responsible_supervisory_authority' => [
    'title' => 'Zuständige Aufsichtsbehörde',
    'question' => 'Meldung des/der Datenschutzbeauftragten erfolgt?',
],
```

---

**5. `master-records.blade.php`**

```php
<x-blank-collapse class="mt-2 cursor-pointer bg-white shadow hover:bg-gray-50">
    <x-slot name="heading" class="flex justify-between px-5 py-4 font-semibold">
        <div class="flex">
            <div class="flex items-center pr-2">
                <div x-show="!open">
                    <x-icon.chevron-right size="4" />
                </div>
                <div x-show="open" x-cloak>
                    <x-icon.chevron-down size="4" />
                </div>
                <h2 class="px-2">{{ __('company.responsible_supervisory_authority.title') }}</h2>
            </div>
        </div>
    </x-slot>

    <x-slot name="content" class="divide-y-2 border-gray-400 p-2">
        @livewire('company.master-records.responsible-supervisory-authority')
    </x-slot>
</x-blank-collapse>
```

---

**6. `responsible-supervisory-authority.blade.php`**

View Elemente hinzufügen

---

**7. `CompanyResponsibleSupervisoryAuthority.php`**

Model erstellen

```php
class CompanyResponsibleSupervisoryAuthority extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'company_responsible_supervisory_authority';

    protected $fillable = [
        'company_name',
        'address_street',
        'address_zip',
        'address_city',
        'phone',
        'email',
        'website',
        'question',
    ];
}
```

---

**8. `Tenant.php`**

```php
/**
 * @return HasMany<CompanyResponsibleSupervisoryAuthority, $this>
 */
public function responsiblesupervisoryauthority(): HasMany
{
    return $this->hasMany(CompanyResponsibleSupervisoryAuthority::class)->withoutGlobalScope(TenantScope::class);
}
```

---

**9. `ResponsibleSupervisoryAuthority.php`**

Variables and Rules:

```php
public Tenant $tenant;
public string $company_name;
public string $address_street;
public string $address_zip;
public string $address_city;
public string $phone;
public string $email;
public string $website;
public string $question;

/**
 * @var array<string,array<mixed>>
 */
protected $rules = [
    'company_name' => ['required', 'max:255'],
    'address_street' => ['required', 'max:255'],
    'address_zip' => ['required', 'email', 'max:255'],
    'address_city' => ['required', 'max:255'],
    'phone' => ['required', 'max:255'],
    'email' => ['required', 'email', 'max:255'],
    'website' => ['required'],
    'question' => ['required', 'boolean'],
];
```

---

Before Render Function

```php
public function mount(): void
{

    if (auth()->user()?->tenant !== null) {

        /* Binds tenant to component and sets up the tenant name as a the company name in the view */
        $this->tenant = auth()->user()->tenant;

        /* Checks if the user already set up a record in the database and fills the form fields with the data to edit them */
        try {
            $responsibleSupervisoryAuthority = CompanyResponsibleSupervisoryAuthority::where('tenant_id', $this->tenant->id)->first();
            $this->company_name = $responsibleSupervisoryAuthority->company_name;
            $this->address_street = $responsibleSupervisoryAuthority->address_street;
            $this->address_zip = $responsibleSupervisoryAuthority->address_zip;
            $this->address_city = $responsibleSupervisoryAuthority->address_city;
            $this->phone = $responsibleSupervisoryAuthority->phone;
            $this->email = $responsibleSupervisoryAuthority->email;
            $this->website = $responsibleSupervisoryAuthority->website;
            $this->question = $responsibleSupervisoryAuthority->question;
        } catch (Exception $e) {

        }
    }
}
```

---

Save Function

```php
/*
Creates or updates the data from the form in the view to the database.
*/
public function save(): void
{
    /* validates the form data based in the $rules */
    $this->validate();

    /* first checks the relationships and then creates or updates the records in the database using the upsert() method */
    $this->tenant->responsiblesupervisoryauthority()->upsert(
        /* This array contains the data */
        [
            'tenant_id' => $this->tenant,
            'company_name' => $this->company_name,
            'address_street' => $this->address_street,
            'address_zip' => $this->address_zip,
            'address_city' => $this->address_city,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'question' => $this->question,
        ],
        /* This array defines what the row is unique/identified with */
        ['tenant_id'],
        /* This array defines which columns get updated */
        ['company_name', 'address_street', 'address_zip', 'address_city', 'phone', 'email', 'website', 'question']
    );

    /* Shows success notification */
    $this->alert('success', __('alert.saved'));
}
```

---

**10. `web.php`**

Router anpassen

```php
Route::get('/data-protection-officer', DataProtectionOfficer::class)->name('data-protection-officer');
```

---

