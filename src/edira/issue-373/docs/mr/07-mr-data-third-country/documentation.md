
## Solution

**1. `DataThirdCountry.php` Make Livewire Component with View**

```bash
php artisan make:livewire Company/MasterRecords/DataThirdCountry
```

---

**2. `CompanyDataThirdCountry.php` Make Eloquent Model**

```bash
php artisan make:model MasterRecords/CompanyDataThirdCountry
```

---

**3. `2021_02_08_153929_create_master_records_table.php` Create DB Schema**

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

---

**4. `company.php` Language anpassen**

```php
'data_third_countries' => [
    'title' => 'Situations Regarding Transfers to Third Countries',
    'question' => 'Is Data Transferred to Third Countries?',
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
                <h2 class="px-2">{{ __('company.data_third_country.title') }}</h2>
            </div>
        </div>
    </x-slot>

    <x-slot name="content" class="divide-y-2 border-gray-400 p-2">
        @livewire('company.master-records.data-third-country')
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
class CompanyDataThirdCountry extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $table = 'company_data_third_country';

    protected $fillable = [
        'question',
    ];
}
```

---

**8. `Tenant.php`**

```php
/**
 * @return HasMany<CompanyDataThirdCountry, $this>
 */
public function datathirdcountry(): HasMany
{
    return $this->hasMany(CompanyDataThirdCountry::class)->withoutGlobalScope(TenantScope::class);
}
```

---

**9. `DataThirdCountry.php`**

Variables and Rules:

```php
public Tenant $tenant;

public bool $question;

/**
 * @var array<string,array<mixed>>
 */
protected $rules = [
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
            $companyDataThirdCountry = CompanyDataThirdCountry::where('tenant_id', $this->tenant->id)->first();
            $this->question = $companyDataThirdCountry->question;
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
    $this->tenant->datathirdcountry()->upsert(
        /* This array contains the data */
        [
            'question' => $this->question,
        ],
        /* This array defines what the row is unique/identified with */
        ['tenant_id'],
        /* This array defines which columns get updated */
        ['question']
    );

    /* Shows success notification */
    $this->alert('success', __('alert.saved'));
}
```

---

**10. `web.php`**

Router anpassen

```php
Route::get('/data-third-country', DataThirdCountry::class)->name('data-third-country');
```

---

