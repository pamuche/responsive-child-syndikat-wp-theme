= Theme =
* Responsive Child Syndikat WP-Theme aktiviert

Verfügbare columns:
.col-60, 
.col-140, 
.col-220, 
.col-300, 
.col-380, 
.col-460, 
.col-540, 
.col-620, 
.col-700, 
.col-780, 
.col-860

== Anpassen ==
* Seitentitel und Untertitel
* Farben
** Hintergrundfarbe: #ffb800
* Hintergrundbild: images/syndikat-background_800.jpg
** Horizontal wiederholen
** Position: Links
** Scrollverhalten: Fixiert
* Statische Startseite
** Auswahl: Eine statische Seite
** Startseite: "Das Syndikat"
** Beitragsseite: "Aktuelles"

== Widgets == 
#TODO document

== Menüs
#TODO document

== Theme-Einstellungen ==
* Homepage
** Enable Custom Front Page: true
*** Titel etc. wie gewünscht
*** Vorgestellter Inhalt: image tag mit images/Sphare.jpg
*** Individuelles Stylesheet: nach syndikat.css kopiert

== Kopfzeile ==
* Kopfzeile anpassen
** Bild wählen: images/syndikat-header-logo_560.png
** nicht beschneiden (nicht die Empfehlung des themes)

== Hintergrund ==
-> scheinbar doppelte Einstellunge (über Wordpress eigenen mechanismus und über diesen Theme spezifischen!)
-> Hier nichts einstellen. Wurde schon oben eingestellt.


= Plugins =

== aktivierte ==
* Advanced Custom Fields
  benötigt für die Daten Felder an Projekten. Hat im Vergleich zum standard WP den Vorteil die Felder
  in Gruppen zu strukturieren. Beim deaktiven Plugin sind die Daten immer noch über standard custom-fields
  funktionen erreichbar
* CMS Tree Page View - leichteres ordnen der pages. Evtl. nach initialen Erstellen der Struktur deinstallieren
* Codeschnipsel - modal_login_button ist angelegt. Brauchts eigentlich nicht wo es jetzt ein child-theme gibt
* CodeStyling Localization - in themes/interfaces enthaltene strings übersetzen
* Events - fügt eigene event tabellen in WP ein. Vermutlich nicht abwärtskompatibel. Wird benötigt um
  Aktuelle Veranstaltungen zu verwalten.
* Fast Secure Contact Form - Stellt E-Mail Formular auf Kontakt Seite bereit
* Polylang - Mehrsprachigkeit. Verknüpft Sprachversionen von Posts über custom felder? Scripting um
  user-sprache (browser) zu erkennen. url-namespacing (/en/, /de/)
* Slideshow - Stellt slideshow für Sidebar bereit. Admin-Interface um slideshows zu erstellen.
* WordPress Access Control - Member bereich - evtl. kann man damit User darauf beschränken nur ihre
  Projektseite editieren zu können. -> Nur aktiv lassen wenn wirklich schon eingesetzt.
* WP Modal Login - schöneres Login Feld. Funktional keine Änderung. Derzeit auch nicht korrekt eingebunden (siehe code schnipsel) 
* Custom Post Type UI 0.8 - zum erstellen der Projekte Post types. Kann wenn die Struktur fest steht gelöscht werden.
 
== deaktiviert ==
Waren aktiviert. Konnte keine Verwendung finden / Vorteil nicht erkennen den das Plugin bietet sollte / Sind so nicht nutzbar

* Advanced iFrame (iframes sind echt evil) - war wohl zur Einbindung der bisher erstellten Projekte Karte
* Breadcrumb NavXT (war nirgends benutzt - die Seitenstruktur ist ja auch eher hierarchisch)
* Content Aware Sidebars - nicht genutzt
* One-Click Child Theme - nach initialem Erstellen des Child-Themes nicht mehr benötigt
* Ultimate TinyMCE - Editor mit vielen Zusatzfunktionen. Der WP-Editor ist für Leute ohne Erfahrung
  eigentlich völlig ausreichend.
* Leaflet Maps Marker ® - Js-Karte mit vielen Einstellmöglichkeiten. Ermöglicht karsten aus hochgeladenen
  tabellen zu erstellen -> Nicht kompatibel mit Datenbank (Projekte).
  ACHTUNG: Lizenz unklar - vermutlich nicht OpenSource -> muss gekauft werden
* Tablepress - Tabellen aus csv Dateien erstellen. Wird für Projekte Übersicht genutzt. Nicht kompatibel mit
  Datenbank(Projekte).
  ACHTUNG: Lizenz unklar - vermutlich nicht OpenSource -> muss gekauft werden
* WP Ultimate CSV Importer - Daten aus csv Dateien importieren.
  Kann nicht für den import der Projekte genutzt werden da ACF nicht unterstützt wird.
  ACHTUNG: Lizenz unklar - vermutlich nicht OpenSource -> muss gekauft werden
  
== noch offen ==
* ein sinnvolles caching plugin






