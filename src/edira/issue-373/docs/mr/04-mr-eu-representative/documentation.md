
## Solution

**1. `EuRepresentative.php` Make Livewire Component with View**

```bash
php artisan make:livewire Company/MasterRecords/EuRepresentative
```

---

**2. `CompanyEuRepresentative.php` Make Eloquent Model**

```bash
php artisan make:model MasterRecords/CompanyEuRepresentative
```

---

**3. `2021_02_08_153929_create_master_records_table.php` Create DB Schema**

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
    });
```

---

**4. `master-records.blade.php`**

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
                <h2 class="px-2">{{ __('company.eu_representative.title') }}</h2>
            </div>
        </div>
    </x-slot>

    <x-slot name="content" class="divide-y-2 border-gray-400 p-2">
        @livewire('company.master-records.eu-representative')
    </x-slot>
</x-blank-collapse>
```

---

**5. `eu-representative.blade.php`**

View Elemente hinzufügen

---

**6. `CompanyEuRepresentative.php`**

Model erstellen

```php
class CompanyEuRepresentative extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'company_eu_representative';

    protected $fillable = [
        'company_name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'address_street',
        'address_zip',
        'address_city',
    ];
}
```

---

**7. `Tenant.php`**

```php
/**
 * @return HasMany<CompanyEuRepresentative, $this>
 */
public function eurepresentative(): HasMany
{
    return $this->hasMany(CompanyEuRepresentative::class)->withoutGlobalScope(TenantScope::class);
}
```

---

**8. `EuRepresentative.php`**

Variables and Rules:

```php
public Tenant $tenant;
public string $company_name;
public string $first_name;
public string $last_name;
public string $phone;
public string $email;
public string $address_street;
public string $address_zip;
public string $address_city;

/**
 * @var array<string,array<mixed>>
 */
protected $rules = [
    'company_name' => ['required', 'max:255'],
    'first_name' => ['required', 'max:255'],
    'last_name' => ['required', 'max:255'],
    'phone' => ['required', 'max:255'],
    'email' => ['required', 'email', 'max:255'],
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

        // Binds tenant to component
        $this->tenant = auth()->user()->tenant;
        $this->company_name = $this->tenant->name;

        // get values from database and sets up the tenant name as a the company name in the view
        $companyInfo = CompanyInformation::where('tenant_id', $this->tenant->id)->first();
        $euRepresentative = CompanyEuRepresentative::where('tenant_id', $this->tenant->id)->first();

        /* Checks if the user already set up a record in the database and fills the form fields with the data to edit them */
        try {
            if ($euRepresentative !== null) {
                $this->company_name = $euRepresentative->company_name;
                $this->first_name = $euRepresentative->first_name;
                $this->last_name = $euRepresentative->last_name;
                $this->phone = $euRepresentative->phone;
                $this->email = $euRepresentative->email;
                $this->address_street = $euRepresentative->address_street;
                $this->address_zip = $euRepresentative->address_zip;
                $this->address_city = $euRepresentative->address_city;
            } else {
                $this->company_name = $companyInfo->company_name;
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
    $this->tenant->eurepresentative()->upsert(
        /* This array contains the data */
        [
            'tenant_id' => $this->tenant,
            'company_name' => $this->company_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address_street' => $this->address_street,
            'address_zip' => $this->address_zip,
            'address_city' => $this->address_city,
        ],
        /* This array defines what the row is unique/identified with */
        ['tenant_id'],
        /* This array defines which columns get updated */
        ['company_name', 'first_name', 'last_name', 'phone', 'email', 'address_street', 'address_zip', 'address_city']
    );

    /* Shows success notification */
    $this->alert('success', __('alert.saved'));
}
```

---

**9. `web.php`**

Router anpassen

```php
Route::get('/eu-representative', EuRepresentative::class)->name('eu-representative');
```

---

