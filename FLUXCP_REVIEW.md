# FluxCP Installation Review
**Date:** November 4, 2025  
**Version:** FluxCP 2.0.0  
**Server:** Adornado Ragnarok Online  
**Reviewer:** Development Team

---

## Executive Summary

FluxCP is successfully running on port 8080 with full database connectivity to the rAthena game server. The installation is production-ready with proper security configurations, though several enhancements are recommended for optimal security and performance.

### Quick Status
- ✅ **Operational:** 34+ hours uptime
- ✅ **Database:** Connected to main2025, web2025
- ✅ **Theme:** hurtFreeV1 (Bulma CSS)
- ✅ **Security:** Basic hardening in place
- ⚠️ **HTTPS:** Not enabled
- ⚠️ **CAPTCHA:** Disabled

---

## System Architecture

### Technology Stack
```
┌─────────────────────────────────────┐
│   FluxCP v2.0.0                     │
│   PHP 8.3.27 + Apache 2.4.65        │
│   Docker Container: fluxcp_web      │
└─────────────────────────────────────┘
            │
            ├─→ rathena_db_ubuntu:3306
            │   ├─ main2025 (game data)
            │   └─ web2025 (FluxCP data)
            │
            ├─→ rathena-login-ubuntu:6900
            ├─→ rathena-char-ubuntu:6121
            └─→ rathena-map-ubuntu:5121
```

### Core Components

#### 1. Entry Point
- **File:** `/opt/fluxcp/index.php`
- **Function:** Bootstrap application, route requests
- **Requirements:** PHP 7.3.0+ (running 8.3.27)

#### 2. Core Library
```
/opt/fluxcp/lib/
├── Flux.php                    # Main framework class
├── Flux/
│   ├── Dispatcher.php          # URL routing
│   ├── SessionData.php         # Session management
│   ├── Authorization.php       # Permission system
│   ├── Connection.php          # Database abstraction
│   ├── LoginServer.php         # Login server interface
│   ├── CharServer.php          # Char server interface
│   ├── MapServer.php           # Map server interface
│   ├── Athena.php              # rAthena integration
│   └── LoginAthenaGroup.php    # Server group management
└── functions/                  # Helper functions
```

#### 3. Module System
- **Total Modules:** 30+
- **Location:** `/opt/fluxcp/modules/`
- **Architecture:** MVC pattern
- **Security:** All modules protected with `FLUX_ROOT` check

---

## Configuration Details

### Application Configuration
**File:** `config/application.php`

```php
// Server Identity
ServerAddress: 194.233.71.190:8080
ServerName: Adornado
SiteTitle: Flux Control Panel

// Theme & Language
ThemeName: hurtFreeV1 (Bulma CSS)
DefaultLanguage: en_us
Available: en_us, es_es, id_id, pt_br

// Security Settings
SessionCookieExpire: 48 hours
SessionKey: fluxSessionData
RequireOwnership: true
ForceHTTPS: false ⚠️

// Authentication
UsernameAllowedChars: a-zA-Z0-9_
MinUsernameLength: 4
MaxUsernameLength: 23
MinPasswordLength: 8
MaxPasswordLength: 31
PasswordMinUpper: 1
PasswordMinLower: 1
PasswordMinNumber: 1
PasswordMinSymbol: 0

// CAPTCHA
UseCaptcha: false ⚠️
UseLoginCaptcha: false ⚠️
EnableReCaptcha: false ⚠️

// Features
UseCleanUrls: false ⚠️
DebugMode: false ✅
ShowRenderDetails: true
```

### Server Configuration
**File:** `config/servers.php`

```php
Array(
    'ServerName' => 'Adornado',
    
    // Main Database (Login, Char, Map data)
    'DbConfig' => [
        'Hostname'   => 'rathena_db_ubuntu',
        'Username'   => 'ragnarok',
        'Password'   => 'ragnarok',
        'Database'   => 'main2025',
        'Port'       => 3306,
        'Persistent' => true,
        'Convert'    => 'utf8'
    ],
    
    // Logs Database
    'LogsDbConfig' => [
        'Hostname'   => 'rathena_db_ubuntu',
        'Username'   => 'ragnarok',
        'Password'   => 'ragnarok',
        'Database'   => 'main2025',
        'Persistent' => true
    ],
    
    // Web Database (FluxCP tables)
    'WebDbConfig' => [
        'Hostname'   => 'rathena_db_ubuntu',
        'Username'   => 'ragnarok',
        'Password'   => 'ragnarok',
        'Database'   => 'web2025',
        'Persistent' => true
    ],
    
    // Login Server
    'LoginServer' => [
        'Address'  => 'rathena-login-ubuntu',
        'Port'     => 6900,
        'UseMD5'   => true,
        'NoCase'   => true,
        'GroupID'  => 0
    ],
    
    // Char/Map Servers
    'CharMapServers' => [[
        'ServerName'   => 'Adornado',
        'CharServer'   => ['Address' => 'rathena-char-ubuntu', 'Port' => 6121],
        'MapServer'    => ['Address' => 'rathena-map-ubuntu', 'Port' => 5121],
        'Renewal'      => true,
        'MaxCharSlots' => 9,
        
        // Rates
        'ExpRates'  => ['Base' => 100, 'Job' => 100, 'Mvp' => 100],
        'DropRates' => ['Common' => 100, 'Heal' => 100, 'Equip' => 100, ...]
    ]]
)
```

### Access Control
**File:** `config/access.php`

Defines permission levels for all modules/actions:
- `AccountLevel::ANYONE` - Public access
- `AccountLevel::UNAUTH` - Unauthenticated only
- `AccountLevel::NORMAL` - Logged in users
- `AccountLevel::LOWGM` - Low-level GMs
- `AccountLevel::ADMIN` - Administrators

---

## Database Schema

### Web Database (web2025)
FluxCP-specific tables with `cp_` prefix:

| Table Name | Purpose |
|------------|---------|
| `cp_credits` | User credit balance |
| `cp_xferlog` | Credit transfer history |
| `cp_itemshop` | Shop item definitions |
| `cp_txnlog` | Payment transactions |
| `cp_redeemlog` | Item redemptions |
| `cp_createlog` | Account creation log |
| `cp_banlog` | Account ban log |
| `cp_ipbanlog` | IP ban log |
| `cp_loginlog` | CP login attempts |
| `cp_pwchange` | Password change log |
| `cp_emailchange` | Email change log |
| `cp_resetpass` | Password reset tokens |
| `cp_trusted` | Trusted PayPal emails |
| `cp_loginprefs` | Account preferences |
| `cp_charprefs` | Character preferences |
| `cp_onlinepeak` | Peak online tracking |
| `cp_cmsnews` | News articles |
| `cp_cmspages` | Static pages |
| `cp_cmssettings` | CMS settings |
| `cp_servicedesk` | Support tickets |
| `cp_servicedeska` | Ticket replies |
| `cp_servicedeskcat` | Ticket categories |
| `cp_servicedesksettings` | Support settings |
| `cp_commands` | Web-based commands |
| `cp_itemdesc` | Item descriptions |

### Main Database (main2025)
rAthena standard tables used by FluxCP:
- `login` - Account authentication
- `char` - Character data
- `guild` - Guild information
- `storage`, `guild_storage` - Storage data
- `picklog`, `zenylog`, `mvplog`, etc. - Game logs
- `vending`, `buyingstore` - Market data

---

## Feature Modules

### Account Management
**Module:** `account`

**Actions:**
- `index` - List accounts (GM only)
- `view` - View account details
- `create` - Registration
- `login` - Authentication
- `logout` - Session termination
- `changepass` - Password change
- `changemail` - Email change
- `changesex` - Gender change
- `resetpass` - Password recovery
- `transfer` - Credit transfers
- `edit` - Admin account editing

**Features:**
- ✅ MD5 password hashing
- ✅ Session security (HttpOnly, SameSite)
- ✅ Login attempt logging
- ✅ Email change confirmation (optional)
- ✅ Password reset system
- ❌ Email verification (disabled)
- ❌ CAPTCHA protection (disabled)

### Character Management
**Module:** `character`

**Actions:**
- `index` - List characters (GM)
- `view` - Character details
- `online` - Who's online
- `mapstats` - Map statistics
- `prefs` - Character preferences
- `changeslot` - Slot management
- `resetlook` - Reset appearance
- `resetpos` - Reset position
- `divorce` - Marriage dissolution

**Features:**
- Real-time online player tracking
- Character equipment display
- Skill point viewing
- Storage viewing
- WoE map restrictions

### Database Browsers
**Modules:** `item`, `monster`

**Features:**
- Search by ID, name, type
- View item/monster stats
- Image display (Divine Pride integration)
- Pagination (20 results/page)
- Single-match redirect (configurable)

### Guild System
**Module:** `guild`

**Features:**
- Guild roster viewing
- Emblem display (BMP support)
- Guild castle ownership
- Member statistics
- Emblem export (admin)

### Ranking System
**Module:** `ranking`

**Available Rankings:**
- Character level
- Guild level
- Zeny amount
- Death count
- Alchemist ranking
- Blacksmith ranking
- Homunculus level
- MVP kills

**Features:**
- Configurable limits (default: 20)
- Ban filtering
- Activity threshold (days)
- GM hiding (configurable level)

### Donation System
**Module:** `donate`

**Integration:** PayPal IPN (Instant Payment Notification)

**Features:**
- Automatic credit deposit
- Transaction logging
- Trusted email management
- Donation history
- Hold untrusted accounts (configurable)
- IP verification whitelist

**Configuration:**
```php
PayPalIpnUrl: www.paypal.com
PayPalBusinessEmail: admin@localhost
AcceptDonations: true
CreditExchangeRate: 1.0
MinDonationAmount: 2.0
DonationCurrency: USD
```

### Item Shop
**Module:** `purchase`

**Features:**
- Shopping cart system
- Credit-based purchases
- Item redemption queue
- Purchase history
- Image upload (admin)
- Category organization
- Quantity limits

**Workflow:**
1. Browse shop → Add to cart
2. Checkout → Deduct credits
3. Items added to redemption queue
4. GM processes redemptions in-game

### News System
**Module:** `news`

**Two Modes:**
1. **Database Mode** (`CMSNewsType = 1`)
   - Store news in `cp_cmsnews`
   - Admin can add/edit/delete
   - Track created/modified dates
   
2. **RSS Import** (`CMSNewsType = 2`)
   - Fetch from external RSS feed
   - Auto-parse and display
   - Configurable item limit

**Current:** RSS mode (rAthena forums)

### Service Desk
**Module:** `servicedesk`

**Features:**
- Ticket submission system
- Category organization
- Staff assignment
- Status tracking (pending/resolved/closed)
- Reply system with color coding
- Credit rewards for bug reports
- Email notifications

**Statuses:**
- Pending (orange)
- Resolved (green)
- Closed (dark grey)
- Staff replies (brown)

### Web Commands
**Module:** `webcommands`

**Purpose:** Execute in-game GM commands via web interface

**Security:**
- Restricted to admin accounts
- Logged in `cp_commands` table
- Discord webhook notifications (optional)

### Logs Viewer
**Module:** `logdata`

**Available Logs:**
- `branch` - Branch usage
- `char` - Character creation/deletion
- `cashpoints` - Cash point transactions
- `chat` - Chat messages
- `command` - GM commands
- `feeding` - Pet/homun feeding
- `inter` - Inter-server events
- `pick` - Item pick/drop
- `login` - Game login attempts
- `mvp` - MVP kills
- `npc` - NPC interactions
- `zeny` - Zeny transactions

### Control Panel Logs
**Module:** `cplog`

**Tracks:**
- PayPal transactions
- Account registrations
- CP login attempts
- Password resets
- Password changes
- Email changes
- Account bans
- IP bans

### Additional Modules

**WoE Schedule** (`woe`)
- Configure WoE times
- Disable modules during WoE

**Castle Viewer** (`castle`)
- Castle ownership
- Economy info
- Defense stats

**Vending Tracker** (`vending`)
- Active vending shops
- Item listings
- Map locations

**Buying Store Tracker** (`buyingstore`)
- Active buying stores
- Wanted items
- Locations

**Static Pages** (`pages`)
- Custom content pages
- Rules, downloads, guides
- Admin editable

**IP Ban** (`ipban`)
- Ban IP addresses/ranges
- Ban reason tracking
- Expiration dates

---

## Theme System

### Active Theme: hurtFreeV1

**Framework:** Bulma CSS (modern, responsive)

**Structure:**
```
themes/hurtFreeV1/
├── config/
│   └── manifest.php        # Theme configuration
├── css/
│   └── custom.css          # Theme styles
├── js/
│   └── custom.js           # Theme scripts
├── img/                    # Theme images
├── fonts/                  # Custom fonts
├── header.php              # Global header
├── footer.php              # Global footer
└── [module]/               # Module-specific views
    ├── index.php
    ├── view.php
    └── ...
```

**Features:**
- Responsive design (mobile-friendly)
- Modern card-based layout
- Clean navigation
- Bulma component library
- Font Awesome icons

### Available Themes
1. **hurtFreeV1** (active) - Modern Bulma theme
2. **bootstrap** - Bootstrap-based theme
3. **default** - Original FluxCP theme
4. **installer** - Installation wizard theme

### Theme Switching
Users can select themes via account preferences (if enabled).

---

## Addon System

### Structure
```
addons/
└── helloworld/             # Example addon
    ├── config/
    │   └── access.php      # Addon permissions
    ├── lang/
    │   └── en_us.php       # Addon translations
    ├── modules/
    │   └── helloworld/     # Addon module
    │       └── index.php
    └── themes/
        └── default/        # Addon theme files
```

### How Addons Work
1. Placed in `/opt/fluxcp/addons/[addonname]/`
2. Auto-loaded by Flux on initialization
3. Can add modules, configs, languages, themes
4. Extend core functionality without modifying core files

### Creating an Addon
```php
addons/myaddon/
├── config/
│   └── addon.php           # return ['AddonName' => 'My Addon'];
├── lang/
│   └── en_us.php           # Translations
├── modules/
│   └── myaddon/
│       └── index.php       # Module logic
└── themes/
    └── default/
        └── myaddon/
            └── index.php   # View template
```

---

## Security Analysis

### ✅ Implemented Security Measures

1. **Session Security**
   - HttpOnly cookies (prevent XSS)
   - SameSite=Strict (prevent CSRF)
   - 48-hour session timeout
   - Secure session key rotation

2. **Password Security**
   - MD5 hashing (matches rAthena)
   - Minimum 8 characters
   - Complexity requirements (upper, lower, number)
   - Enhanced GM password requirements
   - Username not allowed in password

3. **Database Security**
   - PDO prepared statements
   - SQL injection protection
   - Database abstraction layer
   - Connection persistence

4. **Code Security**
   - `FLUX_ROOT` constant checks (prevent direct access)
   - Input validation
   - Permission-based access control
   - Error logging (not displayed to users)

5. **File Security**
   - Restricted directory permissions (0700)
   - File ownership enforcement
   - Upload validation (image extensions)
   - Protected config directory

### ⚠️ Security Concerns

1. **HTTPS Not Enabled**
   - Risk: Credentials transmitted in plaintext
   - Impact: Man-in-the-middle attacks possible
   - **Fix:** Enable SSL/TLS, obtain Let's Encrypt certificate

2. **CAPTCHA Disabled**
   - Risk: Automated bot registrations
   - Impact: Spam accounts, database bloat
   - **Fix:** Enable reCAPTCHA or built-in CAPTCHA

3. **Email Confirmation Disabled**
   - Risk: Fake email addresses
   - Impact: Cannot contact users, account recovery issues
   - **Fix:** Enable RequireEmailConfirm

4. **Clean URLs Disabled**
   - Risk: Information disclosure in URLs
   - Impact: SEO penalty, parameter tampering easier
   - **Fix:** Enable mod_rewrite, uncomment .htaccess

5. **Debug Credentials in Config**
   - Risk: Default installer password visible
   - Impact: Unauthorized access to installer
   - **Fix:** Change InstallerPassword immediately

6. **No Rate Limiting**
   - Risk: Brute force attacks
   - Impact: Account compromise
   - **Fix:** Implement login attempt limits

### Recommended Security Hardening

#### Priority 1 (Critical)
```bash
# 1. Enable HTTPS
# Install certbot and obtain certificate
sudo certbot --apache -d yourdomain.com

# Update application.php
ForceHTTPS: true
```

```php
// 2. Change installer password
'InstallerPassword' => 'CHANGE_THIS_TO_STRONG_PASSWORD'

// 3. Enable CAPTCHA
'UseCaptcha' => true,
'UseLoginCaptcha' => true,

// OR use reCAPTCHA
'EnableReCaptcha' => true,
'ReCaptchaPublicKey' => 'your_public_key',
'ReCaptchaPrivateKey' => 'your_private_key',
```

#### Priority 2 (High)
```php
// 4. Enable email confirmation
'RequireEmailConfirm' => true,
'RequireChangeConfirm' => true,

// 5. Configure email
'MailerFromAddress' => 'noreply@yourdomain.com',
'MailerFromName' => 'Adornado RO',
'MailerUseSMTP' => true,
'MailerSMTPHosts' => 'smtp.gmail.com', // or your SMTP
'MailerSMTPUsername' => 'your@email.com',
'MailerSMTPPassword' => 'your_password',
'MailerSMTPPort' => 587,
'MailerSMTPUseTLS' => true,
```

#### Priority 3 (Medium)
```bash
# 6. Enable clean URLs
# Uncomment .htaccess rules
sed -i 's/^#RewriteEngine/RewriteEngine/' /opt/fluxcp/.htaccess
sed -i 's/^#RewriteBase/RewriteBase/' /opt/fluxcp/.htaccess
sed -i 's/^#RewriteCond/RewriteCond/g' /opt/fluxcp/.htaccess
sed -i 's/^#RewriteRule/RewriteRule/' /opt/fluxcp/.htaccess
```

```php
// Update config
'UseCleanUrls' => true
```

#### Priority 4 (Low)
```php
// 7. Enhance password requirements
'PasswordMinSymbol' => 1,
'MinPasswordLength' => 10,

// 8. Disable duplicate emails
'AllowDuplicateEmails' => false,

// 9. Auto-prune expired accounts
'AutoPruneAccounts' => true,
```

---

## Performance Optimization

### Current Settings
```php
ServerStatusCache: 2 minutes
ScriptTimeLimit: 0 (unlimited)
ResultsPerPage: 20
GzipCompressOutput: false
OutputCleanHTML: true (uses Tidy if available)
```

### Recommendations

#### 1. Enable Output Compression
```php
'GzipCompressOutput' => true,
'GzipCompressionLevel' => 6, // Balance of speed vs compression
```

#### 2. Increase Status Cache
```php
'ServerStatusCache' => 5, // 5 minutes instead of 2
'ServerStatusTimeout' => 1, // Reduce timeout to 1 second
```

#### 3. Optimize Database Queries
```sql
-- Add indexes to FluxCP tables
ALTER TABLE cp_loginlog ADD INDEX idx_account_id (account_id);
ALTER TABLE cp_loginlog ADD INDEX idx_login_date (login_date);
ALTER TABLE cp_credits ADD INDEX idx_account_id (account_id);
```

#### 4. Enable Persistent Connections
Already enabled:
```php
'Persistent' => true // All database configs
```

#### 5. Consider Caching Layer
For high-traffic servers, implement Redis/Memcached:
```php
// Future enhancement
'CacheDriver' => 'redis',
'CacheHost' => 'localhost',
'CachePort' => 6379,
```

#### 6. CDN for Static Assets
Move images, CSS, JS to CDN for faster loading:
```
- data/items/*.png → CDN
- data/monsters/*.gif → CDN
- themes/*/img/* → CDN
- themes/*/css/* → CDN (minified)
- themes/*/js/* → CDN (minified)
```

---

## Docker Configuration

### Container Details
```yaml
services:
  fluxcp:
    build: .
    container_name: fluxcp_web
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    networks:
      - docker_default
    environment:
      - TZ=UTC
    restart: unless-stopped
```

### Dockerfile
```dockerfile
FROM php:8.3-apache

# PHP Extensions
- pdo, pdo_mysql, mysqli
- gd (with FreeType, JPEG)
- zip, xml

# Apache Modules
- mod_rewrite (enabled)

# Permissions
- www-data:www-data ownership
- 755 for files, 777 for data/
```

### Network Connections
```
docker_default network (172.21.0.0/16)
├─ fluxcp_web: 172.21.0.6
├─ rathena_db_ubuntu: 172.21.0.3
├─ rathena-login-ubuntu: 172.21.0.4
├─ rathena-char-ubuntu: 172.21.0.2
└─ rathena-map-ubuntu: 172.21.0.5
```

### Volume Mounts
```
Host: /opt/fluxcp → Container: /var/www/html
```
This allows live editing of files without rebuilding container.

---

## Logging & Monitoring

### Log Locations

**Exception Logs:**
```
data/logs/errors/exceptions/YYYYMMDD.log
```

**MySQL Error Logs:**
```
data/logs/mysql/errors/YYYYMMDD.log
```

**Email Logs:**
```
data/logs/mail/YYYYMMDD.log
```

**Transaction Logs:**
```
data/logs/transactions/YYYYMMDD.log
```

**Schema Logs:**
```
data/logs/schemas/logindb/[server]/YYYYMMDD.log
data/logs/schemas/charmapdb/[server]/[charserver]/YYYYMMDD.log
```

### Discord Webhook Integration
```php
'DiscordUseWebhook' => false, // Enable for notifications
'DiscordWebhookURL' => 'your_webhook_url',
'DiscordSendOnRegister' => true,
'DiscordSendOnNewTicket' => true,
'DiscordSendOnWebCommand' => true,
'DiscordSendOnErrorException' => true,
```

When enabled, sends notifications to Discord for:
- New account registrations
- Service desk tickets
- Web commands executed
- PHP exceptions

### Monitoring Commands
```bash
# View recent exceptions
docker exec -it fluxcp_web tail -f /var/www/html/data/logs/errors/exceptions/*.log

# Check Apache access log
docker exec -it fluxcp_web tail -f /var/log/apache2/access.log

# Check Apache error log
docker exec -it fluxcp_web tail -f /var/log/apache2/error.log

# Monitor live traffic
docker logs -f fluxcp_web
```

---

## Maintenance Procedures

### Regular Backups

#### Database Backup
```bash
# Backup web database
docker exec rathena_db_ubuntu mysqldump -u ragnarok -pragnarok web2025 > backup_web_$(date +%Y%m%d).sql

# Backup main database (be careful, large file)
docker exec rathena_db_ubuntu mysqldump -u ragnarok -pragnarok main2025 > backup_main_$(date +%Y%m%d).sql
```

#### File Backup
```bash
# Backup entire FluxCP directory
tar -czf fluxcp_backup_$(date +%Y%m%d).tar.gz /opt/fluxcp/

# Backup only config and data
tar -czf fluxcp_data_$(date +%Y%m%d).tar.gz /opt/fluxcp/config/ /opt/fluxcp/data/
```

### Log Rotation
```bash
# Compress old logs (older than 30 days)
find /opt/fluxcp/data/logs/ -name "*.log" -mtime +30 -exec gzip {} \;

# Delete very old logs (older than 90 days)
find /opt/fluxcp/data/logs/ -name "*.log.gz" -mtime +90 -delete
```

### Update Procedures

#### Update FluxCP Core
```bash
# 1. Backup first!
tar -czf fluxcp_pre_update_$(date +%Y%m%d).tar.gz /opt/fluxcp/

# 2. Pull updates (if using git)
cd /opt/fluxcp
git pull origin main

# 3. Check for schema updates
# Visit: http://your-server:8080/install

# 4. Restart container
docker-compose restart fluxcp
```

#### Update Theme
```bash
# Replace theme files
cd /opt/fluxcp/themes/
# ... copy new theme files ...

# Clear any theme cache
rm -rf /opt/fluxcp/data/tmp/*

# Restart
docker-compose restart fluxcp
```

### Troubleshooting

#### FluxCP Not Loading
```bash
# Check container status
docker ps -a | grep fluxcp

# Check logs
docker logs fluxcp_web

# Restart container
docker-compose restart fluxcp

# Check permissions
docker exec -it fluxcp_web ls -la /var/www/html/data/
```

#### Database Connection Errors
```bash
# Test database connectivity
docker exec fluxcp_web php -r "new PDO('mysql:host=rathena_db_ubuntu;dbname=web2025', 'ragnarok', 'ragnarok');"

# Check database server
docker exec rathena_db_ubuntu mysql -u ragnarok -pragnarok -e "SHOW DATABASES;"

# Verify network
docker network inspect docker_default
```

#### Permission Errors
```bash
# Fix permissions
docker exec -it fluxcp_web chown -R www-data:www-data /var/www/html/data/
docker exec -it fluxcp_web chmod -R 755 /var/www/html/
docker exec -it fluxcp_web chmod -R 777 /var/www/html/data/
```

#### Session Issues
```bash
# Clear sessions
docker exec -it fluxcp_web rm -f /tmp/sess_*

# Check session directory
docker exec -it fluxcp_web php -r "echo session_save_path();"
```

---

## Development Guidelines

### Adding a New Module

#### 1. Create Module Structure
```bash
mkdir -p modules/mymodule
touch modules/mymodule/index.php
touch modules/mymodule/view.php
```

#### 2. Module Template
```php
<?php
// modules/mymodule/index.php
if (!defined('FLUX_ROOT')) exit;

$title = 'My Module';

// Your logic here
$data = $server->connection->getStatement("SELECT * FROM my_table")->execute();

// Variables are available in view
```

#### 3. Create View
```php
<?php
// themes/hurtFreeV1/mymodule/index.php
if (!defined('FLUX_ROOT')) exit;
?>
<h1><?php echo htmlspecialchars($title) ?></h1>
<div class="content">
    <!-- Your HTML here -->
</div>
```

#### 4. Add Permissions
```php
// config/access.php
'mymodule' => array(
    'index' => AccountLevel::NORMAL,
    'view' => AccountLevel::NORMAL,
    'admin' => AccountLevel::ADMIN
),
```

#### 5. Add to Menu
```php
// config/application.php - MenuItems
'MyModuleLabel' => array('module' => 'mymodule'),
```

#### 6. Add Language Strings
```php
// lang/en_us.php
'MyModuleLabel' => 'My Module',
'MyModuleTitle' => 'My Module Title',
```

### Creating an Addon

#### 1. Addon Structure
```
addons/myaddon/
├── config/
│   ├── addon.php       # Required
│   └── access.php      # Optional
├── lang/
│   └── en_us.php       # Optional
├── modules/
│   └── myaddon/
│       └── index.php
└── themes/
    └── default/
        └── myaddon/
            └── index.php
```

#### 2. Addon Manifest
```php
<?php
// addons/myaddon/config/addon.php
return array(
    'AddonName' => 'My Addon',
    'AddonVersion' => '1.0.0',
    'AddonAuthor' => 'Your Name',
    'AddonDescription' => 'Description of my addon'
);
?>
```

### Best Practices

1. **Always check FLUX_ROOT**
   ```php
   if (!defined('FLUX_ROOT')) exit;
   ```

2. **Use prepared statements**
   ```php
   $sql = "SELECT * FROM table WHERE id = ?";
   $sth = $server->connection->getStatement($sql);
   $sth->execute(array($id));
   ```

3. **Escape output**
   ```php
   echo htmlspecialchars($userInput);
   ```

4. **Use Flux::message() for text**
   ```php
   echo Flux::message('MessageKey');
   ```

5. **Follow naming conventions**
   - Files: lowercase, underscores
   - Classes: PascalCase
   - Functions: camelCase
   - Constants: UPPER_CASE

6. **Error handling**
   ```php
   try {
       // Code
   } catch (Exception $e) {
       $errorMessage = $e->getMessage();
   }
   ```

---

## API Reference

### Flux Class

#### Configuration
```php
Flux::config('ConfigKey');              // Get config value
Flux::config('ConfigKey', $default);    // With default
```

#### Messages
```php
Flux::message('MessageKey');            // Get language string
```

#### Server Access
```php
Flux::$servers                          // All server groups
Flux::$loginAthenaGroupRegistry        // Login server groups
Flux::$sessionData                     // Current session
```

#### Database
```php
$server->connection->getStatement($sql) // Get prepared statement
$sth->execute(array($param1, $param2))  // Execute with params
$sth->fetch()                           // Fetch one row
$sth->fetchAll()                        // Fetch all rows
```

### Session Data

```php
$session->isLoggedIn()                  // Check if logged in
$session->account                       // Account object
$session->account->account_id           // Account ID
$session->account->userid               // Username
$session->loginAthenaGroup             // Server group
```

### Authorization

```php
$auth->allowedToAccess($module, $action) // Check permission
$auth->getGroupLevel()                   // Get user group level
```

---

## Known Issues & Limitations

### Current Limitations

1. **Single Server Group**
   - FluxCP configured for one server group only
   - Multi-server setup possible but not configured

2. **No Mobile App**
   - No REST API for mobile applications
   - Web interface only (though responsive)

3. **Limited Language Support**
   - Only 4 languages included
   - Community contributions for more languages

4. **PayPal Only**
   - No alternative payment gateways
   - PayPal-specific implementation

5. **No Auto-Update**
   - Manual update process required
   - No version check system

### Potential Issues

1. **Character Slots**
   - Hardcoded max 9 slots
   - Changing requires code modification

2. **Item Database**
   - Relies on Divine Pride API for images
   - If API down, images won't load

3. **Guild Emblems**
   - Requires GD2 library with BMP support
   - Some PHP builds lack this

4. **Email System**
   - Requires external SMTP server
   - No built-in email server

---

## Roadmap & Future Enhancements

### Planned Improvements

#### Short Term (1-3 months)
- [ ] Enable HTTPS with Let's Encrypt
- [ ] Implement reCAPTCHA
- [ ] Enable email confirmation
- [ ] Add rate limiting to login
- [ ] Create staging environment
- [ ] Implement config import system

#### Medium Term (3-6 months)
- [ ] REST API for mobile apps
- [ ] Enhanced admin dashboard
- [ ] Real-time notifications (WebSocket)
- [ ] Advanced statistics
- [ ] Multi-factor authentication
- [ ] Social login (Discord, Facebook)

#### Long Term (6-12 months)
- [ ] Mobile app (React Native)
- [ ] Advanced shop system (auctions, trades)
- [ ] Forum integration
- [ ] Wiki system
- [ ] Automated backups
- [ ] CI/CD pipeline
- [ ] Performance monitoring dashboard

### Community Contributions Welcome
- New themes
- Language translations
- Addon development
- Bug reports/fixes
- Documentation improvements

---

## Support & Resources

### Official Resources
- **FluxCP GitHub:** https://github.com/rathena/FluxCP
- **rAthena GitHub:** https://github.com/rathena/rathena
- **rAthena Forums:** https://rathena.org/board/

### Documentation
- FluxCP Wiki (community-maintained)
- rAthena Documentation
- This review document

### Getting Help

1. **Check Logs First**
   ```bash
   docker logs fluxcp_web
   cat /opt/fluxcp/data/logs/errors/exceptions/*.log
   ```

2. **Search Forums**
   - rAthena forums
   - FluxCP GitHub issues

3. **Ask Community**
   - Discord channels
   - Forum threads

4. **Report Bugs**
   - GitHub issues (with logs)
   - Detailed reproduction steps

---

## Conclusion

FluxCP is a mature, feature-rich control panel that integrates seamlessly with rAthena. The current installation is functional and ready for production use with some security hardening.

### Current Status: ✅ Production Ready (with recommendations)

**Strengths:**
- Comprehensive feature set
- Clean, modular architecture
- Active development community
- Extensive customization options
- Docker deployment ready

**Action Items:**
1. ✅ Complete this review
2. ⚠️ Implement Priority 1 security fixes
3. ⚠️ Configure email system
4. ⚠️ Set up regular backups
5. ⚠️ Monitor logs regularly

### Final Recommendations

**Before Public Launch:**
1. Enable HTTPS (critical)
2. Enable CAPTCHA (critical)
3. Change installer password (critical)
4. Configure email system (high)
5. Enable email confirmation (high)
6. Set up automated backups (medium)

**Post-Launch:**
1. Monitor logs daily (first week)
2. Gather user feedback
3. Optimize performance based on traffic
4. Plan feature enhancements
5. Keep system updated

---

**Document Version:** 1.0  
**Last Updated:** November 4, 2025  
**Next Review:** December 4, 2025
