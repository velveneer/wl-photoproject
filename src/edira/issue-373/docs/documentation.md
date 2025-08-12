# Issue 373

[Git Issue 373](https://git.etes.de/edira/edira/-/issues/373)

## Task 1 Stammdaten

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


### Result

## Task 2 Benutzer

### Breakdown

- ausgeschriebenes Bearbeiten soll zu Icon geändert werden 
- Bulk Action soll hinzugefügt werden 
  -> welche Bulkactions sollen hier möglich sein (eine war Benutzer deaktivieren) 
- Rechtschreibung Modal Benutzer erstellen anpassen

![Änderungen Benutzer Reiter](../src/img/3.png)

![Rechtschreibung Modal](../src/img/7.png)

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

### Result

## Task 3 Schadenswerte

### Breakdown

- Condition Zeichen sollen als Dropdown Menu in Input Field integriert werden (Default wie angezeigt) -> Häufig verwendete Symbole anschließend raus
- Inhaltliche Textanpassung
- Geld Werte sollen nach drei stellen durch Punk getrennt werden 

![Änderungen Eintrittswahrscheinlichkeits Reiter](../src/img/4.png)

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

### Result

## Task 4 Mitarbeiter

### Breakdown

- Tooltip Menu um Bulkaction auszuführen, nachdem mehrere Objekte ausgewählt wurden
- Rechtschreibung Modal Mitarbeiter erstellen anpassen
- Error wenn man nach abteilung sortiert

![Bulk Action Mitarbeiter Reiter](../src/img/5.png)
![Rechtschreibung Modal](../src/img/6.png)
![Bug Mitarbeiter](../src/img/10.png)

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

### Result

## Task 5 Abteilungen

### Breakdown

- Objektauswahl und Bulkaction hinzufügen
  -> welche Bulkactions werden noch mehr konkretisiert
- Rechtschreibung Modal Abteilung erstellen anpassen

![Bulk Actions Abteilungen](../src/img/8.png)

![Rechtschreibung Modal](../src/img/9.png)

Hier ist noch Rücksprache für das Verhalten erforderlich:
        
- Checkboxen können ausgewählt werden, entweder einzeln ausgewählt oder gleich alle (select all)
- Sobald ein oder mehrere Kästchen ausgewählt werden, erscheint ein Button/ kleines modal für Aktionen
- Aktionen: Löschen, exportieren (vllt gibt es später mal mehr)
- Löschen: erst möglich, wenn Bestätigung erfolgte

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

### Result

## Task Unklar

### Breakdown

- Reiter Vertraulichkeitsstufen für VVT
- `company.blade.php` translation in der Navbar

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

### Result