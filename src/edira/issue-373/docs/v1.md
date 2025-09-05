## Task 1 Stammdaten Demo

### Breakdown

- Reiter Firmeninformationen in Reiter Stammdaten integrieren
- Anstatt `Verantwortlicher` -> `Unternehmen`
- Aktuelle Stammdaten Unterreiter sollen alles auf einer Page sein
- Aktuell wird alles in Table `master_records` gespeichert, die einzelnen Punkte sollen in jeweilige Tables aufgesplittet werden

![Firmeninformationen mergen](../src/img/1.png)

![Aktueller master_records table](../src/img/2.png)

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

- Main Page mit Nav `company.blade.php`
- jewilige Subpage wird über dieses tag eingebunden:

```php
<div class="space-y-6 sm:px-6 lg:col-span-9 lg:px-0">
    {{ $slot }}
</div>
```
- in `$slot` variable steckt jeweilige form/page
- In router wird mit der Middelware auf der base `/` route für company der Livewire Component Information mitgegeben welcher in der View und `$slot` eingefügt wird:
  
```php
Route::middleware(['role:manager'])->prefix('company')->name('company.')->group(function () {
    Route::get('/', App\Http\Livewire\Company\Information::class)->name('information');
}
```

- neue route für master-records-combined zu company prefix hinzufügen und zu `/` route machen
- `/informations` route aus master records prefix entfernen und als normale company prefiy route
- 
- neuen livewire component für master-records-combined erstellen
- neue view für master-records-combined


- `Information.php` in `Verantwortlichkeiten` reinmergen
- Logo Upload in Verantwortlichkeiten View reinmergen
- Main Nav Link zu Firmeneinstellung von Informations zu Master Records
- Alte Master Record Nav Elemente aus `company.view.php` rausnehmen und neue Master Records Main Page verlinken
  
- in view können bereits erstellte views für einzelne punkte mit @livewire alle auf einer page eingebunden werden
- collapsible für jeden Unterpunkt erstellen 
- Dropdown Button einfügen, Titel in Header Slot einfügen 
- Livewire in Content Slot einfügen
- paddings und margins anpassen für optik
- 



- soll master records zu firmeninformationen umbenannt werden
- soll wirklich jeder subpunkt eigene tabelle werden
- was passiert bei 2FA und Language
- Wo wird firmenlogo gespeichert
- soll gesetzlicher vertreter noch aufgesplittet werden
- würde es sinn machen Adress Table zu machen:

| id  | tenant_id | category                | title           | name                  | surname   | email               | website                  | phone       | street        | zip_code | city      | check | created_at | updated_at |
| --- | --------- | ----------------------- | --------------- | --------------------- | --------- | ------------------- | ------------------------ | ----------- | ------------- | -------- | --------- | ----- | ---------- | ---------- |
| 1   | 1         | Firma                   |                 | ETES GmbH             |           |                     |                          |             | Talstraße 106 | 70188    | Stuttgart |
| 2   | 1         | Unternehmensleitung     |                 | CEO                   |           |                     |                          |             |               |          |           |
| 3   | 1         | Gesetzlicher Vertreter  | Geschäftsführer | Espenhain             | Markus    | info@etes.de        |                          | 07114890830 | Talstraße 106 | 70188    | Stuttgart |
| 4   | 1         | EU Vertreter            | MD              | EU                    | Vertreter | eu@etes.de          |                          | 07114890830 | Talstraße 106 | 70188    | Stuttgart |
| 5   | 1         | Datenschutzbeauftragter | DPO             | Nußbaum               | Chantal   | datenschutz@etes.de |                          | 07114890830 | Talstraße 106 | 70188    | Stuttgart |
| 6   | 1         | Aufsichtsbehörde        |                 | Amtsgericht Stuttgart |           | amtsgericht@mail.de | amtsgericht-stuttgart.de | 07119210    | Hauffstraße 5 | 70190    | Stuttgart |

1. Master Records aufsplitten
2. Damage Scores
3. Probability Scores

- alle aufklappen button
- 