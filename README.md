# CampusConnect — Information Security Practical Assignment 2 (Part 2)

CampusConnect is a local student social networking portal built using **PHP, MySQL, HTML, CSS and JavaScript**. The current **Vulnerable Version** demonstrates XSS, CSRF and SQL Injection using fictional student and employee records.

> **Local security lab only:** `vulnerable/` intentionally contains insecure code. Do not deploy it publicly, expose it on a LAN, or use real accounts or credentials.

## Current project structure

```text
is_webSecurity_task/
├── attacker-lab/       # Local CSRF demonstration page and test materials
├── node_modules/       # Installed Prettier dependencies (local; Git-ignored)
├── safe/               # Reserved for the Safe Version
├── vulnerable/         # CampusConnect PHP application
│   ├── assets/         # Website CSS and other assets
│   ├── index.php       # Student feed
│   ├── login.php       # Login
│   ├── register.php    # Registration
│   ├── profile.php     # Student profile
│   ├── edit_profile.php
│   ├── profile_update.php
│   ├── friend_add.php
│   ├── search.php       # Reflected XSS demonstration
│   ├── preview.php      # DOM-Based XSS demonstration
│   ├── directory.php   # Student directory / SQL Injection demonstration
│   ├── payroll.php
│   ├── payroll_update.php
│   └── ...             # Remaining PHP routes
├── .gitignore
├── .prettierrc         # Prettier formatting configuration
├── package.json        # Prettier tooling configuration
├── package-lock.json   # Locked npm dependencies
└── README.md
```

> This structure reflects the current VS Code project screenshot. The MySQL database is managed through phpMyAdmin; **a `database/` folder is not currently shown**. The `node_modules/` folder is not uploaded to GitHub.

## Application functionality

- User registration, login, logout and session-based accounts
- Student feed, posts, comments and directory
- Student profiles and Bio editing
- Friend connections and connection counts
- Student search and Live Announcement Preview
- Student lookup/check functionality
- Admin-only Staff Payroll with employee salary correction

## Demonstrated security scenarios

| Vulnerability | Relevant file | Local test outcome |
| --- | --- | --- |
| Stored XSS | `vulnerable/profile.php` | Sam stored JavaScript in his Bio. When Aamir (ID 7) visited, a connection with Sam (ID 3) was added (`friendships` row: `user_id=7`, `friend_id=3`). |
| Reflected XSS | `vulnerable/search.php` | A crafted search URL executed JavaScript that changed the victim browser tab title to `CampusConnect XSS Demonstrated`. |
| DOM-Based XSS | `vulnerable/preview.php` | JavaScript read a malicious `#` fragment and inserted it into the DOM using `innerHTML`. A separate test changed Aamir's profile Bio to `Bio changed by Sam using DOM XSS`. |
| CSRF (POST) | `vulnerable/profile_update.php`, `attacker-lab/csrf-profile.html` | An external local event-invitation form submitted an authenticated update to Bob's profile; his display name and Bio changed without using Edit Profile. |
| SQL Injection — Login bypass | `vulnerable/login.php` | A crafted login input bypassed password validation for the fictional administrator and allowed access to admin-only Payroll. |
| SQL Injection — Blind/boolean | Student lookup/check route | TRUE/FALSE response technique was covered in local testing; capture separate successful-response evidence before claiming final completion. |
| SQL Injection — UNION-based | `vulnerable/directory.php` | The injected query revealed `campusconnect_lab` and its database table names in directory results. Column enumeration and dummy-data extraction need their own verified screenshots. |
| SQL Injection — Salary manipulation | `vulnerable/payroll_update.php` | A manipulated employee-code condition updated all three fictional salary records instead of only one. |

## Running the project locally

1. Start **Apache** and **MySQL** in MAMP.
2. Use phpMyAdmin to confirm that the local database **`campusconnect_lab`** exists with its test records. The current visible folder layout does not include a SQL setup/export file.
3. Verify your actual MySQL connection values in `vulnerable/config.php` (MAMP environments can use different ports).
4. Open: `http://localhost/is_webSecurity_task/vulnerable/`
5. For the separate CSRF page, serve `attacker-lab/` locally on port **8001** and keep CampusConnect running in MAMP.

## Formatting tools

Prettier was used to format project files. The formatting setup consists of `package.json`, `package-lock.json` and `.prettierrc`. These configuration files belong in the repository; `node_modules/` does not. For a fresh checkout, install dependencies with:

```powershell
npm ci
```

Run formatting only on intended supported file types and verify PHP functionality afterwards; formatting is not a security fix.

## Scope

The **Vulnerable Version** has been tested locally for the observed attacks above. The **Safe Version** has not yet been completed or verified. The final assignment evidence should include each working payload and matching screenshot, plus blocked-attack evidence after the Safe Version is implemented.