# Issue 169

## Tasks

Aktuell werden VVT in der Übersicht alle gleich angezeigt. Um neu erstellte VVTs besser erkennen zu können, sollen diese nach dem erstellen hellgrün aufleuchten. 

## Modified Files

```
.
├── app/
│   └── Http/                  
│       └── Livewire/
│           └── Vvt/
│               └── Index.php  
└── resources/
    └── views/                  
        └── livewire/
            └── vvt/
                └── index.blade.php  
```

## Solution

Aktuell wird im Table `vvts` ein Timestamp bei Erstellung hinterlegt. Beim rendern der HTML-Elemente kann damit überprüft werden, wie alt jedes VVT ist. Wenn das VVT in dem festgelegte Zeitfenster, in dem sie farblich markiert werden sollen, erstellt wurde, werden die benötigten Tailwind Klassen hinzugefügt:

`index.blade.php`:

```php
@forelse ($vvts as $vvt)

<x-table.row            
    class="
        cursor-pointer hover:bg-gray-100 
        {{ $this->createdInTimeframe($vvt->created_at, 5) ? '
            !bg-green-300 transition-colors delay-150 duration-500 animate-[pulse_2s_ease-in-out_3] 
        ' : '' 
        }}" 
    >
```

Um zu Überprüfen, ob das VVT neu ist, wird die Funktion `createdInTimeframe()`, welche in `Index.php` erstellt wurde:

```php
public function createdInTimeframe($timestamp, $lifetime) {
    // nutzt die PHP built-in Klasse Carbon, um aktuelle Zeit festzustellen
    $currentTime = Carbon::now();
    // formattiert den Zeitstempel des VVTs aus der Datenbank das gleiche Format wie die aktuelle Zeit
    $formattedTimestamp = Carbon::parse($timestamp);
    // berechnet den Zeitunterschied zwischen der aktuellen Zeit und der Creation Zeit des VVTs in Sekunden
    $diff = $formattedTimestamp ->diffInSeconds($currentTime);

    // vergleicht die Differenz mit dem festgelegten Zeitfenster
    if($diff <= $lifetime ) 
    {
        return true;
    }
    
    else 
    {
        return false;
    }
}
```

## Result

<video width="640" height="360" controls>
    <source src="../src/img/1.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>