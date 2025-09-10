
#### EU Representative

**1. `EuRepresentative.php` Make Livewire Component with View**

```bash
php artisan make:livewire Company/MasterRecords/EuRepresentative
```

---

**2. `CompanyEuRepresentative.php` Make Eloquent Model**

```bash
php artisan make:model Company/MasterRecords/CompanyEuRepresentative
```

---

**3. `2021_02_08_153929_create_master_records_table.php` Create DB Schema**

```php
Schema::create('company_eu_representative', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('tenant_id');
    $table->string('company_name')->index();
    $table->string('title')->index();
    $table->string('first_name')->index();
    $table->string('last_name')->index();
    $table->string('phone')->index();
    $table->string('email')->index();
    $table->string('address_street')->index();
    $table->string('address_zip')->index();
    $table->string('address_town')->index();
    $table->timestamps();

    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
});
```

---

