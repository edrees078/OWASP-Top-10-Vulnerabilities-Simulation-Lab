# SnowNOVA educational vulnerability lab

SnowNOVA is an educational website with OWASP vulnerability lessons and intentionally vulnerable PHP exercises. It uses plain HTML, CSS, JavaScript, PHP sessions, and MySQL/MariaDB through `mysqli`. Frontend assets include Bootstrap, jQuery, and Swiper; some dependencies load from external CDNs. No Composer or npm installation is required by the inspected project.

> **Local educational use only.** Run the lab on an isolated development machine using disposable, synthetic data. Do not deploy it publicly, expose it to your LAN or the internet, or enter real credentials or personal information. Intentional SQL injection, XSS, and insecure authentication are part of the teaching material. Opening a localhost URL does not itself restrict Apache's network access: ensure the server is restricted to local access before starting it, and keep firewall protections enabled.

## Project layout

- `SnowNoVA/index.html`: application homepage.
- `SnowNoVA/login.*`, `signup.*`, and `subscribe.php`: main account and subscription flows.
- `SnowNoVA/Vulnerabilities/`: lessons and exercises, including SQL injection, XSS, authentication, and logging examples.
- `SnowNoVA/sql databases/`: SQL exports used in the import map below.
- `SnowNoVA/Vulnerabilities/sql databases/`: byte-identical copies of those exports at inspection time; do not import both sets.
- `SnowNoVA/resumes/`: contributor profile pages.
- `SnowNoVA/css/`, `js/`, `fonts/`, `images/`, `before/`, and `assets/`: frontend resources, with additional copies under some subdirectories.

## XAMPP setup on Windows

The inspected installation was `D:\xampp`, with PHP 8.2.12, `mysqli` and `mysqlnd` enabled, and Apache configured for port 80 with document root `D:/xampp/htdocs`. These describe the inspected environment, not a requirement to use that exact PHP version. Adjust paths for your own installation.

1. Ensure XAMPP's Apache and database services are restricted to your isolated local environment. Do not open firewall ports, enable port forwarding, or create a public tunnel for this lab.
2. Check whether `D:\xampp\htdocs\SnowNoVA` already exists. Do not overwrite an existing installation or its data.
3. Copy the **inner `SnowNoVA` application folder**, not the whole repository, into `D:\xampp\htdocs\SnowNoVA`. The resulting homepage path should be `D:\xampp\htdocs\SnowNoVA\index.html`. Keep Git metadata outside the web document root.
4. Open `D:\xampp\xampp-control.exe` and start Apache and MySQL (the database service in XAMPP uses MariaDB).
5. Prepare the databases using the import map below. Connection settings are embedded in individual PHP files rather than a shared configuration file. They must match your isolated local database setup; do not copy private settings into this repository or weaken an existing database installation to match the lab.
6. Open [the homepage](http://localhost/SnowNoVA/index.html) through Apache. Opening the HTML directly from disk will not execute PHP handlers.

Some frontend resources require internet access to their CDNs. That does not require making the local application accessible to other machines. If Apache cannot start, check the XAMPP control panel and port conflicts rather than disabling firewall protections. If you use a different HTTP port, adjust the local URLs accordingly.

This setup gets the application into the expected subfolder, but known path inconsistencies mean some exercises will still need fixes. A copied application is separate from this Git checkout: future code changes will not automatically appear in the copy served by XAMPP.

## Database import map

The supplied exports contain seed account/subscriber data, including plaintext passwords. Treat them as sensitive, do not publish or reproduce their contents, and prefer reviewed synthetic fixtures for local exercises. The steps below describe a manual setup only; they are not an instruction to import into an existing or shared database.

| Export under `SnowNoVA/sql databases/` | Database to select | Tables | Consumers |
| --- | --- | --- | --- |
| `user_accounts.sql` | `user_accounts` | `users`, `profiles`, `event_logs` | Main login/signup and several login exercises |
| `user_management1.sql` | `user_management1` | `users` | Identification registration and `loginSami.php` |
| `mywebsite.sql` | `mywebsite` | `subscribers` | `subscribe.php`, which currently requests `myWebsite` |

The same three filenames also exist under `SnowNoVA/Vulnerabilities/sql databases/`. Use just one set; the first directory is the reference location for this guide.

### Manual phpMyAdmin import

1. Open [local phpMyAdmin](http://localhost/phpmyadmin/).
2. For each row in the map, create a separate **empty** database with the indicated name and `utf8mb4_general_ci` collation. If the name already exists, stop and inspect your setup rather than dropping or overwriting it.
3. Select that database in the sidebar, open **Import**, choose the matching reviewed SQL file, and select **Go/Import**.
4. Check the import result and confirm that the expected tables appear. Repeat for the other two databases.

The exports do not include `CREATE DATABASE` or `USE` statements: selecting the correct database first is essential. They include table definitions and seed records and are not designed to be re-imported into populated databases. Their headers record MariaDB 10.4.32 and PHP 8.2.12 at export time; import compatibility was not runtime-tested during inspection.

The subscription code uses `myWebsite`, while the dump identifies `mywebsite`. This casing difference can cause problems on systems with case-sensitive database names.

`SnowNoVA/Vulnerabilities/SecuritySum.php` also expects a **fourth database, `security_logs`**, containing `event_logs`. No export for that database was found. The `event_logs` table in `user_accounts` does not satisfy this separate connection. The logging exercise needs a deliberate schema/configuration decision; importing the three supplied databases alone does not resolve it.

SQL exports and the encrypted account artifact are inside the application tree. Prevent HTTP access to those data artifacts before serving the lab; do not assume that an unlinked file is inaccessible. No database or Apache configuration changes are supplied by this README.

## Known limitations

- **Mixed application paths:** links mix `/SnowNoVA/...`, `/Vulnerabilities/...`, and relative URLs. Missing targets include `index1.html`, `signin.html`, and `blog-single.html`. No single deployment location fixes all paths.
- **Identification login wiring:** `loginSami.php` submits an email field to a handler expecting a username and a different database. Its hash verification also disagrees with the registration exercise's plaintext storage. Session/redirect handling follows HTML output and can depend on output buffering.
- **Duplicate usernames:** main login expects exactly one matching user, while the supplied schema/data permits duplicates. A valid password alone may therefore be insufficient to log in.
- **Simulated email:** password reset displays a success message without sending a reset email; subscription stores an address and claims confirmation delivery without an email-sending implementation.
- **Logging exercise:** besides its missing database, it records success before checking credentials and displays logs without an authorization gate. Do not treat it as production logging or administration.
- **Intentional vulnerabilities:** exercise handlers include SQL injection, reflected/stored XSS, plaintext password handling, and credential disclosure. Preserve their teaching purpose when fixing accidental navigation or wiring defects. Static administration pages are not protected server-side administration.
- **Dependency and asset maintenance:** bundled and CDN dependencies coexist, some CDN URLs are unversioned, and assets are duplicated. Full offline operation and browser compatibility have not been established.

## Read-only validation

Run the [syntax validation script](scripts/validate-syntax.ps1) from PowerShell. From the repository root, use the command below; adjust the PHP path for your installation. Node.js must be available on `PATH`, or supplied with `-NodePath`. If PHP is on `PATH`, you can omit `-PhpPath`.

```powershell
.\scripts\validate-syntax.ps1 -PhpPath 'D:\xampp\php\php.exe'
$LASTEXITCODE

git diff --check
git status --short --branch
```

The script finds the application relative to its own location, so invoking it by its full path also works from another directory. It runs PHP with `-n -l` (no `php.ini`) and Node.js with `--check`, temporarily clearing `NODE_OPTIONS` to prevent preloaded code. It writes no reports or application files, starts no services, and does not run request handlers or connect to databases.

Exit codes are **0** when all checks pass, **1** when a file fails validation, and **2** for missing tools or an unreadable/incomplete application tree. The summary gives file counts and failures. Failed files are listed by relative path; native parser diagnostics are suppressed because they can include sensitive source excerpts.

The script skips contributor `resumes` directories, dependency directories (`node_modules`, `vendor`), Git directories, and symbolic links/junctions. It checks external `.php` and `.js` files only; SQL exports and other data artifacts are not read. Syntax checks do not establish correct authentication, working database connections, valid links, or safe runtime behavior, and do not check inline JavaScript in HTML/PHP.

At inspection time, all 15 PHP files and 19 external JavaScript files passed syntax checks. No automated test suite or CI configuration was found; the `test.html` pages are content/demo pages. The inspection did not import databases or exercise application requests.

For manual review after an isolated setup, open the homepage, inspect the browser Console and Network panels, and follow navigation links. Test forms only with disposable data in a disposable database: signup, subscription, and logging requests can write records. Expect the limitations above rather than assuming all exercises work after import.
