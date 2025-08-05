# 383

[Git Issue 383](https://work-documentation.jroedel-work.gitlab.io/)

## Breakdown 

Auf der Übersichtsseite eines spezifischens VVT sind nur die generischen Überschriften der zu bearbeitenen Punkte sichtbar. Da diese gleich bei unterschiedlichen VVTs sind, ist gewünscht die bereits bearbeiteten und eingefügten spezifischen Daten als Vorschau mit auf der Übersichtsseite mit anzuzeigen. Dies soll zu einer leichteren Suche, Prüfung und Vervollständigung der Einträge führen. 

![image info](../src/img/1.png)

!!! Info
    Alte Version, rote Markierung zeigt an, an welchen Stellen Vorschau eingebaut werden soll

![image info](../src/img/2.png)

!!! Info
    Der Inhalt der Seite für einen spezifischen Bearbeitungspunkt eines VVT, der auf der Übersichtsseite angezeigt werden soll.

![image info](../src/img/3.png)

!!! Info
    Wie der Inhalt platziert werden soll.

## Related Files

```
.
├── app/
│   ├── Http/                  
│   │   ├── Livewire/
│   │   │   ├── Vvt/
│   │   │   │   └── View.php  
├── resources/
│   ├── views/                  
│   │   ├── livewire/
│   │   │   ├── vvt/
│   │   │   │   └── view.blade.php  

```




## Solution

1. Component:

- vvt objekt wird bereits in Livewire Component created 
- datenbank einträge aus table 'vvt_values' ist verfügbar zu jeweiligen vvt:
- diese enthalten row_id, welches auf die zugehörige vvt row verweist

`$this -> vvt -> values`

- aktuell wird nur rows object an view weitergegeben, welches nur information über die aktuell dargestellten rows besitzt 
- values von vvt als eigenes array an view weitergeben:

```php
public function render(): \Illuminate\Contracts\View\View
{ 
    return view('livewire.vvt.view', ['rows' => $this->rows], ['values' => $this -> vvt -> values])
        ->layout('layouts.app', ['title' => 'VVT Bearbeiten']);
}
```

2. View:

- aktuell gibt es for each schleife die durch row array loopt und für jeden eintrag ein html element einfügt 
- dabei muss gecheckt werden, ob das jeweilige row element einen bereits bestehenden eintrag besitzt und anschließened dargestellt werden:

```php
@foreach ($rows as $row)
    <a href="{{ route('dsms.vvt.rows.edit', [$vvt, $row]) }}" class="flex divide-x hover:bg-gray-100">
        <p class="flex w-20 items-center justify-center py-4 font-mono font-semibold">{{ $row->number }}</p>

        <h2 class="px-6 py-4">{{ $row->name }}</h2>
    </a>

    @foreach ($values as $value)
        @if ($value->row_id === $row->id)
            <div class="pl-16">
                {{ $value->body }}
            </div>
        @endif
    @endforeach
@endforeach
```
## Problems

