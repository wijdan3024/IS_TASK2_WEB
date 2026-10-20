# Safe Version — Retest the Vulnerable Attack Payloads

Run only against `http://localhost/is_webSecurity_task/safe/`, after importing `safe/database/campusconnect_safe.sql` and starting MAMP. Use fictional test accounts.

| Attack | What to repeat | Expected safe outcome |
|---|---|---|
| Easy SQLi | On `safe/login.php`, email `admin@example.test' #` and an incorrect password | Login denied; no admin session or payroll access |
| Intermediate blind SQLi | On `safe/lookup.php`, try `1 AND 1=1` and `1 AND 1=2` | Both treated as invalid IDs; neither becomes a TRUE/FALSE SQL predicate |
| Advanced SQLi | On `safe/directory.php`, paste the same `UNION SELECT` payload previously used | No injected student cards, database/table names, or data; no query error leakage |
| Payroll SQLi | As valid admin, try employee code `EMP-001' OR '1'='1` and a demo salary | HTTP 422 and **zero** salaries updated |
| Stored XSS | As Sam, save the prior `<img ... onerror=...>` payload in Bio. View Sam's profile as Alice | It is shown as text; no friend-add or profile modification |
| Reflected XSS | Paste the previous search `q=<img...>` payload into `safe/search.php` | Raw characters displayed as text; page title does not change |
| DOM-based XSS | Open the previous `safe/preview.php#...` payload | Literal text displayed in preview; JavaScript does not run; Bio unchanged |
| CSRF | With Alice/Bob logged into `safe/`, serve `safe/test-pages/` on localhost port 8001 (`py -m http.server 8001` in that directory) and open `http://localhost:8001/csrf-safe-retest.html` | HTTP 403 Invalid CSRF token; profile remains unchanged |
| Access control | Student account visits `safe/payroll.php`; guest visits it | Student: 403; guest: login redirect |
| Positive test | As Alice, update Bio via Safe Edit Profile; add friend; publish post; comment | All normal functions work with valid tokens |
| Positive payroll | As Admin, update one valid employee ID | Only that employee changes; access still restricted to admin |

**Evidence guidance:** Screenshot both the payload/submission and the blocked outcome for each requirement. For database-writing attacks, query safe database before/after. Safe and vulnerable use separate databases for independent comparisons.

**Do not treat `SameSite=Strict` as your only CSRF defense:** requests between localhost ports are not necessarily cross-site. The synchronizer token is the primary block for this exercise.
