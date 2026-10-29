# WaermemengenZaehler
Berechnet die Leistung und Energie eines Wärmemengen Zähler

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)

### 1. Funktionsumfang

* Berechnung der Energie
* Berechnung der Leistung 

### 2. Voraussetzungen

- IP-Symcon ab Version 6.0

### 3. Software-Installation

* Über den Module Store das 'WaermemengenZaehler'-Modul installieren.
* Alternativ über das Module Control folgende URL hinzufügen

### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'WaermemengenZaehler'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)

__Konfigurationsseite__:

Name               | Beschreibung
------------------ | ------------------
Temperatur Eingang | Variable der oberen Temperatur
Temperatur Ausgang | Variable der unteren Temperatur
Wasseruhr          | Variable für die Wasseruhr

### 5. Statusvariablen

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

#### Statusvariablen

Name     | Typ     | Beschreibung
-------- | ------- | -----------
Leistung | float   | Berechnete Leistung aus der Formel: P = (m \* c \* ( T1 - T2 )) / t
Energie  | float   | Berechnete Energie aus der Formel: E = (P * ( T1 - T2 )) / t

### 7. PHP-Befehlsreferenz

`boolean WMZ_UpdatePower(integer $InstanzID);`
Berechnet die Leistung anhand der im Konfigurationsformular eingestellten Variablen

Beispiel:
`WMZ_UpdatePower(12345);`


`boolean WMZ_UpdateEnergy(integer $InstanzID);`
Berechnet die Energie anhand der im Konfigurationsformular eingestellten Variablen mit der

Beispiel:
`WMZ_UpdateEnergy(12345);`