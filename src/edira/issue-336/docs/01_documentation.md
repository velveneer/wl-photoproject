# 336

[Git Issue 336](https://git.etes.de/edira/edira/-/issues/336)

## Problem 1

### Breakdown

Wenn ein initialer SOA angelegt wird, ist in dem generierten PDF Dokument auf Seite 2 unter der Änderungs-Historie die Tabelle mit Default-Werten gefüllt. Hier kam die Frage auf, ob diese Seite erst ab der ersten Änderung generiert werden soll. 

### Reproduction

Initiale SOA-Version anlegen:

![image info](./img/1.png)

![image info](./img/2.png)

![image info](./img/3.png)

---

PDF Änderungs-Historie mit Default-Werten, da noch keine Änderung vorgenommen wurde:

![image info](./img/4.png)

---

`soa_version` table in Datenbank nachdem initiale SOA Version angelegt wurde:

![image info](./img/5.png)

---

`soa_changelogs` table in Datenbank nachdem initiale SOA Version angelegt wurde:

![image info](./img/6.png)

--- 

erste Änderung der initialen Version:

![image info](./img/7.png)

![image info](./img/8.png)

---

PDF Änderungs-Historie mit Default-Werten, nachdem Änderung vorgenommen wurde:

![image info](./img/9.png)

---

`soa_changelogs` table in Datenbank nach erster Änderung an initiale SOA Version:

![image info](./img/10.png)

### Solution 

Man kann beim anlegen, der SOA-Version checken, ob bereits ein Eintrag im `soa-changelogs` table vorhanden ist. Ist dieser leer, kann man einfach verhindern, dass die Änderungs-Historie Page in der PDF generiert wird.

## Problem 2

### Breakdown

Wenn eine initiale SOA Version erstellt wurde, kann über das Dropdown Menu `Verwalten` -> `+ Version hinzufügen` eine neue Version erstellt werden. Dabei kann nur der Name und die SOA-Vorlage festgelegt werden. Im Gegensatz zur initialen Version, wird hier schon eine Änderung vorgenommen, welche einerseits im Änderungsprotokoll und andererseits in der PDF festgehalten wird. Hier wird automatisch die Beschreibung `Zyklisches Review` hinzugefügt bzw. ein Datenbank Eintrag für eine Änderung der Version hinzugefügt. Es wird wahrscheinlich durch eine ungewollte DB-Query beim anlegen, der SOA-Version, ein neue Changelog Eintrag hinzugefügt, der mit Default-Werten gefüllt wird. 

### Reproduction

Neue SOA-Version nach initial SOA-Version anlegen:

![image info](./img/11.png)

---

Neue SOA-Version und Initialversion verfügbar nach Anlegen:

![image info](./img/12.png)

---

Änderungsprotokoll nach Erstellung, ohne dass eine Änderung vorgenommen wurde:

![image info](./img/13.png)

---

Druckbare Datei nach Erstellung, ohne dass eine Änderung vorgenommen wurde:

![image info](./img/14.png)

--- 

`soa_changelogs` table in Datenbank nach Anlege von neuer SOA-Version , ohne dass eine Änderung vorgenommen wurde:

![image info](./img/15.png)

---

### Solution 

Files that are responsible for the SOA-version creation:

Livewire:
`app/Http/Livewire/Soa/VersionCreate.php`

Actions:
`app/Actions/Soa/VersionCreateAction.php`

View:
`resources/views/livewire/soa/version-create.blade.php`

Models:
`app\Models\Soa\Version`

Following the bug inside the code :

| Step | File                       | Function      | Explenation                                                                                                                                     |
| ---- | -------------------------- | ------------- | ----------------------------------------------------------------------------------------------------------------------------------------------- |
| 1    | `version-create.blade.php` | form submit() | calls `VersionCreate.php` store()                                                                                                               |
| 2    | `VersionCreate.php`        | store()       | calls validate() to validate user input                                                                                                         |
| 3    | `VersionCreate.php`        | store()       | calls save() to save tenant_id, template_id & name to `soa_versions` table                                                                      |
| 4    | `VersionCreate.php`        | store()       | calls `VersionCreateAction` version() and appends VersionCreate object version attribute to the version object inside `VersionCreateAction.php` |
| 5    | `VersionCreate.php`        | store()       | calls `VersionCreateAction` create() method                                                                                                     |
| 6    | `VersionCreateAction.php`  | create()      | gets the previous created version object from the database and stores it inside $previousVersion                                                |
| 7    | `VersionCreateAction.php`  | create()      | checks if is not null -> is not empty                                                                                                           |
| 8    | `VersionCreateAction.php`  | create()      | creates prepared `soa_changelog` array and saves it do the database                                                                             |

The bug happens because of this part of the code inside the create() function from the `app/Actions/Soa/VersionCreateAction.php` file: 

```
// unwanted changelog was created and pushed to the database in this part
if ($previousVersion) {

    $this->version->changelogs()->create([
        'date' => now(),
        'version' => $this->version->name,
        'created_by' => auth()->user()?->fullName,
        'description' => 'Zyklisches Review',
    ]);
}
```
After disabling the part it stopped appending a changelog on SOA-version creation. I've tested the website a bit to see if it affected other functions but havn't found anything yet. 

If someone knows if this part still serves another function please let know. 


## Problem 3

### Breakdown

Für den User ist es meiner Meinung nach verwirrend, dass sowohl für die SOA-Version sowie die Change-Version der SOA-Version die Kennzeichnung `Version` verwendet wird. Es geht nicht klar hervor, wie ich eine neue SOA-Version anlege und wie ich eine neue Änderungsversion einer SOA-Version anlege. Zudem sollte direkt ersichtlich werden, dass man über den `Änderungsprotokoll` - Button seinen aktuellen Fortschritt speichern kann. Ohne direkten Verweis, schließt man sich wahrscheinlich daraus, dass man mit diesem Button nur zu einer Übersicht aller Änderung kommt. 

### Reproduction

Button um neue SOA-Version zu erstellen und Änderungsprotokoll-Button:

![image info](./img/16.png)

---

Form / Button um neue Änderungsversion einer SOA-Version zu erstellen:

![image info](./img/17.png)

### Solution

Es wäre besser, wenn der User leichter erkennt, dass mit Versionen im Änderungsprotokoll nur Änderung an der aktuell ausgewählten SOA-Version gemeint ist. Anders könnte man auch immer `SOA-Version` statt `Version` als Kennzeichnung verwendet werden, wenn es sich auf diese bezieht. 



