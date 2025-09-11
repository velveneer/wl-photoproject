
## Solution

**1. `authorized-representative.blade.php` HTML-Elemente in View überarbeiten**

---
   
**2. `CompanyAuthorizedRepresentative.php` Eloquent Model für Datenbank erstellen**

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

---

**3. `2021_02_08_153929_create_master_records_table.php` Schema für eigenen Table erstellen**

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

---

**4. `AuthorizedRepresentative.php` Component überarbeiten**

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

---

**5. `Tenant.php` HasMany Funktion hinzufügen**

```php
/**
 * @return HasMany<CompanyAuthorizedRepresentative, $this>
 */
public function authorizedrepresentative(): HasMany
{
    return $this->hasMany(CompanyAuthorizedRepresentative::class)->withoutGlobalScope(TenantScope::class);
}
```
