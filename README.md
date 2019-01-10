# Commons Booking Post Booking

**Contributors:** poilu  
**Donate link:** https://flotte-berlin.de/mitmachen/unterstuetzt-uns/  
**Tags:** booking, commons, admin  
**Tested:** Wordpress 4.9.x, Commons Booking 0.9.2.3  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

---
## Description

**Commons Booking Post Booking** is a Wordpress Plugin, which extends the [Commons Booking](https://github.com/wielebenwir/commons-booking) Plugin and allows to automatically send additional emails to users before and after a booking period.

The first one can be seen as a reminder to ask the users to cancel the booking if they won't use it actually. It can be set how many days have to be passed since booking and how many days in advantage the email will be sent.

The second email is sent when a booking period ends. This can happen on the last days of the booking period or the day after.

In the email templates the following template tags can be used: {{FIRST_NAME}}, {{LAST_NAME}}, {{DATE_START}}, {{DATE_END}}, {{ITEM_NAME}}, {{LOCATION_NAME}}, {{HASH}}

The emails are sent daily at the adjusted times and can be activated/deactivated separately.

![settings](/screenshots/settings_0.3.0_en.png?raw=true "settings")

## Beschreibung

**Commons Booking Post Booking** ist ein Wordpress Plugin, welches das [Commons Booking](https://github.com/wielebenwir/commons-booking) Plugin um die Möglichkeit ergänzt, vor und nach dem Buchungszeitraum zusätzliche Emails automatisiert an NutzerInnen zu versenden.

Erstere kann als Erinnerungsmail genutzt werden, um z.B. NutzerInnen aufzufordern, ihre Buchung zu stornieren, sofern sie den Gegenstand doch nicht benötigen. Es kann eingestellt werden, wie viele Tage mindestens seit der Buchung vergangen sein müssen und wie viele Tage im Voraus die Email versendet werden soll.

Die zweite Email wird versendet, wenn ein Buchungszeitraum endet. Dies kann am letzten Tag des Zeitraums oder am darauf folgenden erfolgen.

In den Email-Templates stehen folgende Template-Tags zur Verfügung: {{FIRST_NAME}}, {{LAST_NAME}}, {{DATE_START}}, {{DATE_END}}, {{ITEM_NAME}}, {{LOCATION_NAME}}, {{HASH}}

Der Email-Versand erfolgt einmal täglich zu den festgelegten Uhrzeiten und kann separat aktiviert/deaktiviert werden.

![Einstellungen](/screenshots/settings_0.3.0_de.png?raw=true "Einstellungen")
