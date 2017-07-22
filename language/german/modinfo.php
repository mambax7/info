<?php
if (!defined('_MI_INFO_NAME')) {
    define('_MI_INFO_NAME', 'DH-INFO');
    define('_MI_INFO_DESC', 'Erstellen von Content mit eigener Navigation.');
    define('_MI_INFO_PRINTER', 'Druckerfreundliche Version aufrufen');

    define('_MI_INFO_BLOCK1', 'DH-INFO Menüblock');
    define('_MI_INFO_BLOCK1_DESC', 'erstellt einen Menüblock zur Navigation');
    define('_MI_INFO_BLOCK2', 'DH-INFO Freier Block');
    define('_MI_INFO_BLOCK2_DESC', 'Zeigt einen beliebigen Beitrag in einem Block an');

    define('_MI_INFO_CONF1', 'Editorauswahl zulassen');
    define('_MI_INFO_CONF1_DESC', 'Ja für Editorauswahl im Formular / Nein für den Defaulteditor des Systems');
    define('_MI_INFO_CONF2', "Link 'Seite anlegen' anzeigen");
    define('_MI_INFO_CONF2_DESC', 'Wenn man die Berechtigung für Seite anlegen hat kann ein Link im Hauptmenü mit angezeigt werden.');
    define('_MI_INFO_CONF3', 'Druckerfreundliche Seiten generieren');
    define('_MI_INFO_CONF3_DESC', 'Diese Einstellungen erstellt auf den Seiten ein Iconlink, bei dem dann eine druckerfreundliche Seite aufgerufen wird.');
    define('_MI_INFO_CONF4', 'Anzeige letzter Änderung');
    define('_MI_INFO_CONF4_DESC', '');
    define('_MI_INFO_CONF5', 'Anzeige der Blöcke unterbinden beim Schreiben');
    define('_MI_INFO_CONF5_DESC', 'Einstellungen der linken und rechten Blöcke beim Aufruf der submit.php');
    define('_MI_INFO_TEMPL1', 'SeitenLayout');
    define('_MI_INFO_LASTD1', 'keine Anzeige');
    define('_MI_INFO_LASTD2', 'kurze Anzeige (=> ' . formatTimestamp(time(), 's') . ')');
    define('_MI_INFO_LASTD3', 'normale Anzeige (=> ' . formatTimestamp(time(), 'm') . ')');
    define('_MI_INFO_LASTD4', 'lange Anzeige (=> ' . formatTimestamp(time(), 'l') . ')');

    //Added in 1.04
    define('_MI_INFO_BLOCKADMIN', 'Blockverwaltung');
    define('_MI_INFO_ADMENU2', 'Kategorien');
    define('_MI_INFO_ADMENU3', 'Seiten verwalten');
    define('_MI_INFO_ADMENU4', 'Zugriffsrechte');

    //Added in 2.0
    define('_INFO_TOOLTIP', 'Tooltip');
    define('_MI_INFO_CONF6', 'Anzeige der SeitenNavigation');
    define('_MI_INFO_CONF6_DESC', '');
    define('_MI_INFO_CONF7', 'Anzeige der Links im Profil');
    define('_MI_INFO_CONF7_DESC', 'Bei ja werden die Links im Profil angezeigt');
    define('_MI_INFO_PAGESNAV', 'als Seitenzahlen');
    define('_MI_INFO_PAGESELECT', 'als Auswahlbox');
    define('_MI_INFO_PAGESIMG', 'als Bilder');
    define('_MI_INFO_SENDEMAIL', 'Per E-Mail versenden');
    define('_MI_INFO_ARTICLE', 'Interessanter Artikel auf %s');
    define('_MI_INNFO_ARTFOUND', 'Hier ist ein interessanter Artikel den ich auf %s gefunden habe');
    define('_MI_INFO_GUEST', 'Gastschreiber');
    define('_INFO_FREIGABEART', 'Freigabemodus');
    define('_INFO_FREIGABEART_YES', 'Freigeben');
    define('_INFO_FREIGABEART_NO', 'Sperren');
    define('_MI_INFO_ADMENU5', 'Beiträge warten');
    define('_MI_INFO_ADMENU6', 'gesperrte Beiträge');
    define('_MI_INFO_GESPERRT', '[GESPERRT]');
    define('_AM_INFO_NOFRAMEOREDITOR', "<div style='font-style:bold;color:red;'>keine Editoren gefunden!</div>");
    define('_INFO_NEW', 'NEU');
    define('_INFO_UPDATE', 'UPDATE');
    define('_MI_INFO_CONF8', 'SEO-Optimierung');
    define('_MI_INFO_CONF8_DESC', 'Umschreibung der Urls in Suchmaschinenfreundliche. Rewriting setzt mod_rewrite vorraus!');
    define('_MI_INFO_CONF9', 'Trennzeichen für Untermenüs');
    define('_MI_INFO_CONF9_DESC', 'gibt das führende Trennzeichen für Untermenüs am Anfang an.');

    //Added in 2.5
    define('_MI_INFO_ADMENU_ABOUT', 'Über das Modul');
    define('_MI_INFO_INDEX', 'Index');
    define('_MI_INFO_CREATESITE', 'Seite anlegen');

    //Added in 2.6
    define('_MI_INFO_VIEWSITE', 'alle Seiten in dieser Kategorie anzeigen');
    define('_MI_INFO_CONF_COLS', 'Anzahl der Spalten des Editors (mind. 10)');
    define('_MI_INFO_CONF_COLS_DESC', 'Gibt die Spalten (Höhe) des Editors an (kein HTML-Editor)');
    define('_MI_INFO_CONF_ROWS', 'gibt die Reihen des Editors an (mind.10)');
    define('_MI_INFO_CONF_ROWS_DESC', 'Gibt die Reihen (Breite) des Editors an (kein HTML-Editor)');
    define('_MI_INFO_CONF_WIDTH', 'Breite HTML-Editor in Prozent (10-100)');
    define('_MI_INFO_CONF_WIDTH_DESC', 'legt die prozentuale Breite des Editors fest (nur für HTML-Editoren)');
    define('_MI_INFO_CONF_HEIGHT', 'Höhe HTML-Editor in Pixel (mind. 100)');
    define('_MI_INFO_CONF_HEIGHT_DESC', 'Die Höhe des Eingabefeldes des Editors in Pixeln.');

    define('_MI_INFO_ADMENU_HELP', 'Hilfe');
    define('_MI_INFO_NONE', 'keine Blöcke ausblenden');
    define('_MI_INFO_RECHTS', 'Rechts ausblenden');
    define('_MI_INFO_LINKS', 'Links ausblenden');
    define('_MI_INFO_BEIDE', 'Rechts und Links ausblenden');
}
