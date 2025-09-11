
## Solution

**1. `DataProtectionOfficer.php` Make Livewire Component with View**

```bash
php artisan make:livewire Company/MasterRecords/DataProtectionOfficer
```

---

**2. `CompanyDataProtectionOfficer.php` Make Eloquent Model**

```bash
php artisan make:model MasterRecords/CompanyDataProtectionOfficer
```

---

**3. `2021_02_08_153929_create_master_records_table.php` Create DB Schema**

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

---

**4. `company.php` Language anpassen**

```php
'data_protection_officer' => [
    'title' => 'Datenschutzbeauftragter',
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
                <h2 class="px-2">{{ __('company.data_protection_officer.title') }}</h2>
            </div>
        </div>
    </x-slot>

    <x-slot name="content" class="divide-y-2 border-gray-400 p-2">
        @livewire('company.master-records.data-protection-officer')
    </x-slot>
</x-blank-collapse>
```

---

**6. `data-protection-officer.blade.php`**

View Elemente hinzufügen

---

**7. `CompanyEuRepresentative.php`**

Model erstellen

```php
class CompanyDataProtectionOfficer extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'company_data_protection_officer';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'address_different',
        'company_name',
        'address_street',
        'address_zip',
        'address_city',
    ];
}
```

---

**8. `Tenant.php`**

```php
/**
 * @return HasMany<CompanyDataProtectionOfficer, $this>
 */
public function dataprotectionofficer(): HasMany
{
    return $this->hasMany(CompanyDataProtectionOfficer::class)->withoutGlobalScope(TenantScope::class);
}
```

---

**9. `DataProtectionOfficer.php`**

Variables and Rules:

```php
public Tenant $tenant;
public string $first_name;
public string $last_name;
public string $phone;
public string $email;
public bool $address_different = true;
public string $company_name;
public string $address_street;
public string $address_zip;
public string $address_city;

/**
 * @var array<string,array<mixed>>
 */
protected $rules = [
    'first_name' => ['required', 'max:255'],
    'last_name' => ['required', 'max:255'],
    'phone' => ['required', 'max:255'],
    'email' => ['required', 'email', 'max:255'],
    'company_name' => ['sometimes', 'max:255'],
    'address_street' => ['sometimes', 'max:255'],
    'address_zip' => ['sometimes', 'max:255'],
    'address_city' => ['sometimes', 'max:255'],
];
```

---

Before Render Function

```php
public function mount(): void
{
    if (auth()->user()?->tenant !== null) {

        // get tenant values from user
        $this->tenant = auth()->user()->tenant;
        $this->company_name = $this->tenant->name;

        // get values from database
        $companyInfo = CompanyInformation::where('tenant_id', $this->tenant->id)->first();
        $dataProtectionOfficer = CompanyDataProtectionOfficer::where('tenant_id', $this->tenant->id)->first();

        try {

            // if available assign first name of responsible person from the 'company_information' table and store it in variables
            $this->first_name = $companyInfo->responsible_person_first_name;
            $this->last_name = $companyInfo->responsible_person_last_name;
            $this->address_street = $companyInfo->address_street;
            $this->address_zip = $companyInfo->address_zip;
            $this->address_city = $companyInfo->address_city;

            // if available assign values from 'company_data_protection_table' table and store it in variables
            $this->first_name = $dataProtectionOfficer->first_name;
            $this->last_name = $dataProtectionOfficer->last_name;
            $this->phone = $dataProtectionOfficer->phone;
            $this->email = $dataProtectionOfficer->email;

            // when the address doesn't vary check if there is one in the 'company_information' table
            if ($dataProtectionOfficer->address_different) {
                $this->company_name = $dataProtectionOfficer->company_name;
                $this->address_street = $dataProtectionOfficer->address_street;
                $this->address_zip = $dataProtectionOfficer->address_zip;
                $this->address_city = $dataProtectionOfficer->address_city;
            }

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
    $this->tenant->dataprotectionofficer()->upsert(
        /* This array contains the data */
        [
            'tenant_id' => $this->tenant,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address_different' => $this->address_different,
            'company_name' => $this->company_name,
            'address_street' => $this->address_street,
            'address_zip' => $this->address_zip,
            'address_city' => $this->address_city,
        ],
        /* This array defines what the row is unique/identified with */
        ['tenant_id'],
        /* This array defines which columns get updated */
        ['first_name', 'last_name', 'phone', 'email','address_different', 'company_name', 'address_street', 'address_zip', 'address_city']
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

