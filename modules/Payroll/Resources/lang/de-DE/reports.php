<?php

return [
    'general' => 'Lohn- und Gehaltsabrechnung',
    'employee' => 'Mitarbeiter/in|Mitarbeiter/innen',
    'positions' => 'Position|Positionen',
    'pay_calendar' => 'Gehaltsauszahlungskalender|Gehaltsauszahlungskalender',
    'variables' => 'Variable Eingabe',
    'pay_slips' => 'Gehaltsabrechnungen',
    'approval' => 'Freigeben',
    'active_employee' => 'Aktive Mitarbeiter/in',
    'advanced' => 'Erweiterte',
    'setting' => 'Einstellungen',

    'next' => 'Weiter',
    'approved' => 'Freigegeben',
    'add_benefit' => 'Zulage hinzufügen',
    'add_deduction' => 'Abzug hinzufügen',
    'employee_profile_information' => 'Mitarbeiterinformation',
    'additional_allowance' => 'Zusätzliche Zuschläge oder Abzüge nur für diesen Zahlungslauf',
    'pay_slip_title' => 'Gehaltsabrechnung',
    'ready_approve' => 'Bereit für Freigabe',

    'status' => [
        'not_approved' => 'Nicht freigegeben',
        'approved' => 'Freigegeben',
    ],

    'employees' => [
        'name' => 'Zahlungslauf - Titel',
        'birth_day' => 'Geburtstag',
        'position_name' => 'Position',
        'email' => 'E-Mail',
        'deduction' => 'Total Abzüge',
        'benefit' => 'Total Zulagen',
        'payment' => 'Total Zahlungen',
        'histories' => 'Gehaltszahlungsverlauf',
        'date' => 'Zahlungsdatum',
    ],

    'menu' => [
        'payroll' => 'Lohn- und Gehaltsabrechnung',
        'employee' => 'Mitarbeiter/innen',
        'position' => 'Positionen',
        'run_payroll' => 'Gehaltszahlungslauf',
        'pay_calendar' => 'Gehaltszahlungskalender',
        'dashboard' => 'Dashboard',
        'setting' => 'Einstellungen',
        'report_run_payroll_summary' => 'Zusammenfassung Berichte',
        'report_run-payroll_employee' => 'Mitarbeiterberichte',
    ],

    'pay_calendars' => [
        'name' => 'Name',
        'type' => 'Typ',
        'pay_day_mode' => 'Zahltag-Modus',
        'pay_day' => 'Zahltag (1-31)',
        'error' => 'Fehler!',
        'success' => 'Erfolgreich!',
        'update' => 'Der Gehaltsauszahlungskalender wurde aktualisiert',
        'run_payroll' => 'Gehaltszahlung speichern/ausführen',

    ],

    'run_payroll' => [
        'select_employee' => 'Mitarbeiter/innen auswählen',
        'run_payroll' => 'Gehaltszahlungslauf',
        'name' => 'Name',
        'payment_date' => 'Zahlungsdatum',
        'run' => 'Gehaltszahlungslauf',
        'employee_name' => 'Name des Mitarbeiters',
        'benefit' => 'Zulage Total',
        'deduction' => 'Abzug Total',
        'salary' => 'Gehalt Total',
        'total' => 'Total',
        'email' => 'E-Mail',
        'gender' => 'Geschlecht',
        'from_date' => 'ab Datum',
        'to_date' => 'bis Datum',
        'run_payroll_name' => 'Name',
        'male' => 'Männlich',
        'other' => 'Andere',
        'female' => 'Weiblich',
    ],

    'payroll' => [
        'name' => 'Vorname',
        'employee' => 'Mitarbeiter/in',
        'last_name' => 'Nachname',
        'street' => 'Strasse',
        'city' => 'Ort',
        'state' => 'Bundesland/Kanton',
        'zip_number' => 'PLZ',
        'birth_day' => 'Geburtstag',
        'gender' => 'Geschlecht',
        'email' => 'E-Mail',
        'social_number' => 'Sozialversicherungsnummer',
        'position' => 'Position',
        'status' => 'Status',
        'personal_information' => 'Mitarbeiter/in Information',
        'salary' => 'Gehalt',
        'amount' => 'Gehalt - Brutto',
        'salary_type' => 'Gehaltsart',
        'effective_date' => 'Beginn Gehaltszahlung',
        'benefit' => 'Zulage',
        'benefit_type' => 'Leistungsart (Zulage)',
        'deduction' => 'Abzug',
        'deduction_type' => 'Leistungsart (Abzug)',
        'recurring' => 'Wiederkehrend',
        'description' => 'Beschreibung',
        'work_information' => 'Sonstige Informationen',
        'benefit_save' => 'Zulage erfolgreich gespeichert',
        'deduction_save' => 'Abzug erfolgreich gespeichert',
        'payment_date' => 'Zahlungsdatum',
        'payroll_name' => 'Name',
    ],

    'benefit' => [
        'bonus' => 'Bonus',
        'commission' => 'Kommission',
        'allowance' => 'Vergütung',
        'benefit' => 'Zulage',
        'reimbursement' => 'Kostenerstattung',
        'dismissal' => 'Abfindung'
    ],

    'benefit_recurring' => [
        'onlyonce' => 'Nur einmal',
        'everycheck' => 'Jede Prüfung',
        'everymonth' => 'Jeden Monat',
    ],

    'deduction_recurring' => [
        'onlyonce' => 'Nur einmal',
        'everycheck' => 'Jede Prüfung',
        'everymonth' => 'Jeden Monat',
    ],

    'deduction' => [
        'provident' => 'Altersvorsorgekasse',
        'loan' => 'Darlehen',
        'advancepay' => 'Vorauszahlung',
        'advance' => 'Vorschuss',
        'miscelleneous' => 'Verschiedener Abzug'
    ],

    'salary_type' => [
        'hourly' => 'Stündlich',
        'annual' => 'Jährlich',
    ],

    'position' => [
        'name' => 'Name',
    ],

    'messages' => [
        'position' => 'Position aktualisiert',
        'position_delete' => 'Position gelöscht',
        'employee_success' => 'Mitarbeiter/in aktualisiert',
        'employee_error' => 'Mitarbeiter/in konnten nicht aktualisiert werden',
        'employee_delete' => 'Mitarbeiter/in gelöscht',
        'deduction_delete' => 'Abzug wurde gelöscht!',
        'benefit_delete' => 'Zulage wurde gelöscht!',
        'run_payroll' => 'Gehaltszahlungslauf wurde erstellt',
        'run_payroll_error' => 'Bitte überprüfen Sie die Informationen',
        'pay_calendar_delete' => 'Gehaltsauszahlungskalender wurde gelöscht'
    ],

    'wizard' => [
        'run_payroll' => 'Gehaltszahlungslauf',
        'employees' => 'Mitarbeiter/innen',
        'variables' => 'Variable Eingaben',
        'pay_slips' => 'Gehaltsabrechnungen',
        'approval' => 'Freigegeben',
    ],

    'dashboard' => [
        'dashboard'=>'Dashboard',
        'total_expenses' => 'Total Ausgaben',
        'total_pay_calendars' => 'Total - Gehaltsauszahlungskalender',
        'total_employees' => 'Anzahl der Mitarbeiter/innen',
        'pay_period' => 'Gehalts-Periode',
        'run_payroll_name' => 'Gehaltszahlungslauf - Name',
        'payment_date' => 'Gehaltszahlungsdatum',
        'net_pay' => 'Zahlung - Netto',
        'from_date' => 'ab Datum',
        'to_date' => 'bis Datum',
        'name' => 'Name',
        'amount' => 'Total Gehaltszahlungen',
        'description' => 'Letzte 5 Gehaltszahlungsläufe',
        'status' => 'Status',
        'employee' => 'Mitarbeiter/in',
        'chart' => 'Gehaltsverlauf des aktuellen Monats',
    ],

    'summary_report' => [
        'employee' => 'Mitarbeiter/in',
        'salary' => 'Gehaltsliste',
        'benefit' => 'Zulage',
        'deduction' => 'Abzüge',
        'amount' => 'Gehalt - Brutto',
        'summary_reports' => 'Zusammenfassung der Mitarbeiter',
        'total' => 'Total',

    ],

    'employee_report' => [
        'employee' => 'Mitarbeiter/in',
        'salary' => 'Gehaltsliste',
        'name' => 'Mitarbeiterbericht',
        'benefit_name' => 'Zulage',
        'benefit_total' => 'Zulage Total',
        'deduction_name' => 'Name des Abzuges',
        'deduction_total' => 'Abzug Total',
        'amount_total' => 'Gehalt - Netto',
    ]
];
