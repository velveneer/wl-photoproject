## Solution

**1. `database/migrations/2021_02_08_153929_create_master_records_table.php` setting up table in migration**

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
!!! Result

    ```
    `company_information`:

    | id  | tenant_id | company_name | top_management | address_street | address_zip | address_town | responsible_person_first_name | responsible_person_last_name | created_at | updated_at | 
| --- | --------- | ------------ | -------------- | -------------- | ----------- | ------------ | ---------- | ---------- | ---------- | ---------- |
|     |           |              |                |                |             |              |            |            |            |            |
    ```

---

**2. `CompanyInformation.php` Model für Informationsettings**

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

---

**3. `Information.php` Funktionalität**
   
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

---

**4. `Tenant.php` hasMany Funktion hinzufügen**
      
```php
public function companyinformation(): HasMany
{
    return $this->hasMany(CompanyInformation::class)->withoutGlobalScope(TenantScope::class);
}  
```
!!! Info

    Eloquent Funktion, um zu checken ob Tenant Objekt Relation zu CompanyInformation Objekt hat.

---

**5. `information.blade.php` View für Company Information mit Funktionalität versehen und Input mit Component Variablen verbinden**