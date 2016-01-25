<?php
$changelog = <<<'EOT'

= 2.3.2 =
* Updated security rules.

= 2.3.1 =
* If you want to upgrade your server from PHP 5 to PHP 7, please
  check our blog for potential issues: http://nin.link/php7/
* During the installation process, NinjaFirewall will add the correct
  PHP 5 or PHP 7 directive to the .htaccess if the server is running
  Apache with mod_php.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.3 =
* Added a new option: File Check. It lets you perform file integrity
  monitoring upon request.
* The Strict-Transport-Security response header in the Firewall
  Policies page has a new option to send an empty "max-age"
  to signal the user-agent to cease regarding the host as a known
  HSTS Host, while disabling the HSTS option will not return any
  header at all.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Updated security rules.
* Minor fixes and improvements.

= 2.2.1 =
* Fixed a bug in the "Summary > Statistics" page that could
  display an empty value in the benchmarks "Fastest request" field.
* Fixed a bug in the firewall double-encoding detection routine.
* Updated security rules.
* Minor fixes and improvements.
* [Pro+ Edition] Added the server IP(s) in the "Access Control >
  Source IP" section.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.2 =
* Improved firewall engine to handle double-encoding used
  in query strings to perform advanced SQLi and XSS attempts.
* Improved Base64-encoded injection detection.
* Updated links and doc.
* Removed green 'OK' icons from the Overview page. Only warning
  and error icons will be used.
* Updated security rules.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.1.3 =
* Updated security rules.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.1.2 =
* Fixed a bug in the firewall that could corrupt the content
  of a POST or GET array.
* [Pro+ Edition] Whitelisted and blacklisted IPs are sorted using
  "natural order" algorithm in the "Access Control" page.
* Updated security rules.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.1.1 =
* The firewall will no longer sanitise user input when running
  in "Debugging Mode", but will only write the event to its log.
* Fixed PHP warning on systems that do not support exclusive locks.
* Loosened Base64 decoder rules to reduce the risk of false-positives.
* Updated security rules.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.1 =
* [Pro+ Edition] The Live Log "Refresh Rate" and "Autoscrolling"
  options will be remembered when changed.
* [Pro+ Edition] Live Log will now use the timezone defined in
  the "Account > Options > Regional Settings" menu.
* The firewall will always ensure that "REMOTE_ADDR" contains
  only one IP or will remove any extra IP.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.
* It is possible to set the Strict-Transport-Security (HSTS)
  header if the client has the `HTTP_X_FORWARDED_PROTO` set to
  'https' (Firewall Policies > HTTP response headers).
* [Pro+ Edition] "File Guard" email alert will contain the date/time
  the file was last changed, rather than the date/time the detection
  occurred.
* Minor fixes and improvements.

= 2.0.9 =
* The firewall engine can now chain two different security rules
  in order to provide a better/more accurate filtering mechanism.
* Updated security rules.
* Session handling was modified for sites running PHP 5.4+.
* [Pro+ Edition] Added a new option to Live Log that allows you
  to select which traffic you want to view (HTTP and/or HTTPS).
* Minor fixes and improvements.

= 2.0.8 =
* Updated security rules.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.0.7 =
* Added a new HTTP response header: "Strict-Transport-Security",
  to defend against cookie hijacking and Man-in-the-middle attacks
  (see "Firewall > Policies > HTTP response headers").
* [Pro+ Edition] Added an option to customize the log format in
  Live Log (see "Live Log > Options > Log format").
* Fixed an "Undefined index: php_ini_type" PHP notice during the
  installation process.
* Fixed a bug in the firewall log display. When filtering the log
  results using the checkboxes, special characters were converted
  to HTML entities.
* Fixed some minor typos and bugs.

= 2.0.6 =
* [Pro Edition] Fixed a bug in the "Block suspicious bots/scanners"
  option: it was not possible to disable it.
* [Pro+ Edition] Added a new feature: "Live Log". It lets you watch
  your website traffic in real time.
* The firewall "Log" menu was renamed to "Security Log".
* Increased the line height in all textarea elements for better
  readability.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.
* It is now possible to create the ".htninja" optional configuration
  file in either the document root or its parent directory (see
  http://nintechnet.com/ninjafirewall/pro-edition/help/?htninja).

= 2.0.5 =
* Updated security rules.
* Added an option to select HHVM (HipHop Virtual Machine) during the
  installation process. See our blog about installing NinjaFirewall
  on HHVM (http://nin.link/hhvm).
* If the 'auto_prepend_file' PHP directive is already in use, the
  installer will not stop but will attempt to override it instead.
* [Pro+ Edition] Added an option to exclude a folder from being
  monitored by File Guard (see "Firewall > File Guard" menu).
* [Pro+ Edition] On new installations, File Guard will be enabled
  by default.
* [Pro+ Edition] Added an option to whitelist the administrator(see
  "Firewall > Access Control > Administrator" and its contextual help).
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.0.4 =
* Added an option to view and flush all banned IPs (see "Firewall >
  Options > Ban offending IP" menu).
* Added an option to select Apache/suPHP SAPI during the installation
  process.
* Loosened cookies sanitizing rules to reduce the risk of false
  positives.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.0.3 =
* Added a new set of options that can hook the HTTP response headers,
  including cookies, and modify them on-the-fly to help mitigate
  threats such as XSS, phishing and clickjacking attacks (see "Firewall
  > Policies > HTTP response headers").
* Updated security rules.
* Fixed a bug in the Firewall Stats page that could return erroneous
  results.
* Added a link to the changelog in the "Account > Update" page.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.

= 2.0.2 =
* Updated security rules.
* [Pro+ Edition] Updated IPv4/IPv6 GeoIP databases.
* Fixed the "Login Security" checkbox alert that was unnecessarily
  displayed when the site was already in HTTPS mode.
* Added specific headers to prevent the firewall blocked page from
  being cached.
* Fixed various small bugs, typos in the code and contextual help
  screens.

= 2.0.1 =
* Rules #151 and #152 (HTTP header injection) were removed to prevent
  false positives from occurring.
* Cookies and GET variable sanitizing will be disabled by default in
  the Firewall Policies page.
* Added a rule to protect against the "shellshock" bash code injection
  vulnerability (CVE-2014-6271).
* Fixed a potential division by zero error in the firewall statistics
  page.

= 2.0 =
* Improved performance: NinjaFirewall no longer uses MySQL, but plain
  text files to stores its configuration into the "conf/" folder.
* [Pro+ Edition] Added "Access Control".
* [Pro+ Edition] Added "Web Filter".
* [Pro+ Edition] Added "File Guard".
* Changed "Firewall Policies" to better suit most sites; new features
  were added and deprecated ones were removed.
* Added possibility to edit the message to display to blocked users
  (see "Firewall > Options").
* Added options to disable, delete, rotate the firewall log and
  checkboxes for easy filtering (see "Firewall > Log").
* Added one-click updater: files and rules can be updated from the
  admin console (see "Account > Updates").
* Added contextual help: click on the Help link located in the upper
  right hand corner of each page to get help.
* Added "Regional Settings" with timezone and English/French language
  packs (see "Account > Options" menu).
* Added a new and intuitive installer.
* Added full IPv6 compatibility.
* Added ".htninja" optional configuration file to let users prepend
  their own code to the firewall (see included ".htninja.sample" file).


EOT;
