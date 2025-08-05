# 338

[Git Issue 338](https://git.etes.de/edira/edira/-/issues/338)

## Breakdown 

Wenn die erweiterte Suche auf der `admin/documents` Page geöffnet wird, wird unter Status, schon angezeigt, dass nach `Aktiv` gefiltert wird. Dieser Filter wird aber noch nicht angewendet, sondern erst, wenn man die Auswahl ändert. 

Aktuell wird der angezeigte Text über ein Array, mit `[ 0 = "Inactive", 1 = "Active"]` bestimmt. Da der Default Wert beim Öffnen `[ 1 ]` ist, wird direkt Aktiv angezeigt. 


## Solution

`resources/views/livewire/admin/document-templates.blade.php`:

Die `@foreach` Schleife überschreibt wahrscheinlich das `<option>` Element, welches vorher für den Defaulttext verwendet wird:

```
<x-input.select wire:model.live="filters.active" id="filter-active">

    // wird überschrieben
    <option value="" disabled class="hidden">
        {{ __('forms.select', ['item' => __('forms.labels.status')]) }}</option>

    //überschreibt
    @foreach ([1, 0] as $boolean)
        <option value="{{ $boolean }}">{{ $boolean ? __('Active') : __('Inactive') }}
        </option>
    @endforeach
</x-input.select>
```

Wenn man das Default <option> Element mit in die `@foreach` Schleife nimmt und das Array um `null` erweitert, wird es mitgerendert.

```
@foreach ([null, 1, 0] as $boolean)
    @if ($boolean === null)
        <option disable value="" class="hidden">{{ __('forms.select', ['item' => __('forms.labels.status')]) }}</option>
    @else
        <option value="{{ $boolean }}">{{ $boolean ? __('Active') : __('Inactive') }}</option>
    @endif
@endforeach
```

---

## Verbesserungen

`ressources/lang/en/forms.php`:

In der englischen Version wurde die `'select'` Variable noch nicht dynamisch gemacht. Um dies zu ermöglichen habe ich folgendes verändert:

`'select' => 'Please select...',`  -> `'select' => 'Please select a :item',`

---
