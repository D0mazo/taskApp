# Task Calendar — PHP App
## InfinityFree Setup Guide

### Files to upload
Upload all 3 files to your InfinityFree `htdocs` folder:
- index.php
- api.php
- db.php

---

### Step 1 — Create MySQL database on InfinityFree
1. Log in to InfinityFree control panel
2. Go to **MySQL Databases**
3. Create a new database and note down:
   - Host (usually `sql###.infinityfree.com`)
   - Database name
   - Username
   - Password

---

### Step 2 — Edit db.php
Open `db.php` and fill in your credentials:

```php
define('DB_HOST', 'sql###.infinityfree.com');  // your host
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database_name');
```

---

### Step 3 — Upload files
Use InfinityFree's **File Manager** or FTP:
- Upload `index.php`, `api.php`, `db.php` to `htdocs/`
- The database tables are created automatically on first visit

---

### Step 4 — Visit your site
Go to `http://yourdomain.infinityfreeapp.com`
- Register a new account
- Start adding tasks!

---

## Features
- Login / Register with secure password hashing
- Calendar view — click any day to see & add tasks
- Color-coded priorities (green / yellow / red)
- Upcoming tasks panel with days-left indicators:
  - 🟢 Green = 7+ days left
  - 🟡 Yellow = 3–7 days left
  - 🔴 Red = 1–3 days left
  - ⚠️ Dark red = overdue
- Progress bar (0–100%) per task
- Progress notes — write what you've done so far
- Stats bar: overdue count, due today, due this week, avg progress
- Edit modal for full task editing
- Mark tasks as done

---

## Notes
- InfinityFree supports PHP 7.4+ and MySQL — this app works with both
- Sessions are used for authentication (no cookies beyond session)
- All user data is isolated per account
