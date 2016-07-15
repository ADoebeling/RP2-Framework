<?php

defined(RPF_URL) ?: define('RPF_URL', 'https://XXXX.premium-admin.eu');

defined(DB_HOST) ?: define('DB_HOST', '127.0.0.3');
defined(DB_USER) ?: define('DB_USER', 'xxx');
defined(DB_NAME) ?: define('DB_NAME', 'xxx');
defined(DB_PWD) ?: define('DB_PWD', 'xxx');

defined(RPF_MAIL_SUBJECT) ?: define('RPF_MAIL_SUBJECT', '[%s] Aktualisierung des E-Mail-Accounts');

defined(RPF_MAIL_CC) ?: define('RPF_MAIL_CC', '1601.com dig Admin-Team <admin@1601.com> ');

defined(RPF_MAIL_FROM) ?: define('RPF_MAIL_FROM', '1601.com Admin-Team <admin@1601.com>');

defined(RPF_MAIL_DEBUG) ?: define('RPF_MAIL_DEBUG', 'dev@1601.com');

defined(RPF_MAIL_REPLY) ?: define('RPF_MAIL_REPLY', '1601.com Support-Team <support@1601.com>');

defined(RPF_MAIL_TEXT) ?: define('RPF_MAIL_TEXT', '
Sehr geehrte Damen und Herren,

mit dieser automatischen E-Mail möchten wir Sie über eine Aktualisierung Ihres E-Mail-Accounts und ggf. dessen Passwort durch unser Support-Team informieren.
Diese E-Mail dient nur der Dokumentation, auf Ihrer Seite besteht in der Regel kein Handlungsanlass.

%s
Größe: %s MB
Passwort: %s

Für Rückfragen stehen wir Ihnen jederzeit gerne zur Verfügung.

Viele Grüße

Support-Team');