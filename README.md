# Famous Quotes — Admin System
### PHP + MySQL Admin Portal | Bootstrap 5

A full-featured quotes administration system built for the Famous Quotes assignment rubric.

---

## ✅ Rubric Coverage

| # | Requirement | Implementation |
|---|-------------|----------------|
| 1 | Add new authors (all fields) | `add_author.php` — name, birth/death year, nationality, bio all required |
| 2 | Add new quotes with author & category lists from DB | `add_quote.php` — dropdowns populated from `authors` and `quotes` tables |
| 3 | Edit authors & quotes with pre-filled values | `edit_author.php`, `edit_quote.php` — all fields pre-populated from DB |
| 4 | Delete authors and quotes | `authors.php`, `quotes.php` — delete with confirmation dialogs |
| 5 | Login with admin/s3cr3t | `login.php` + `includes/auth.php` using `password_verify()` |
| 6 | Credentials stored in database table | `admin_users` table with bcrypt hashed password |
| 7 | All admin pages protected by sessions | `requireLogin()` called at top of every admin page |
| 8 | Navigation menu across all pages | `includes/nav.php` included in every page |
| 9 | Logout button destroys session | Logout link in nav → `logout.php` calls `session_destroy()` |
| 10 | Nice design with Bootstrap | Bootstrap 5 + custom CSS with Playfair Display typography |

---

## 🚀 Setup Instructions

### Prerequisites
- PHP 7.4+ with PDO and PDO_MySQL extensions
- MySQL 5.7+ or MariaDB 10.3+
- A web server (Apache/Nginx) or PHP's built-in server

### Option A — PHP Setup Script (Recommended)
1. Upload all files to your web server
2. Edit `includes/db.php` with your database credentials
3. Create a blank MySQL database called `famous_quotes`
4. Visit `http://yourserver/famous-quotes/run_setup.php` in your browser
5. **Delete `run_setup.php` after setup!**

### Option B — Manual SQL Import
1. Create database: `CREATE DATABASE famous_quotes;`
2. Import: `mysql -u root -p famous_quotes < setup.sql`
   - ⚠️ Update the password hash in `setup.sql` by running:
     `php -r "echo password_hash('s3cr3t', PASSWORD_DEFAULT);"`
   - Replace the hash value in the INSERT statement
3. Edit `includes/db.php` with your credentials

### PHP Built-in Server (local dev)
```bash
cd famous-quotes
php -S localhost:8000
# Visit http://localhost:8000
```

---

## 🔑 Login Credentials
- **Username:** `admin`
- **Password:** `s3cr3t`

---

## 📁 File Structure
```
famous-quotes/
├── index.php          — Dashboard (protected)
├── login.php          — Login page
├── logout.php         — Destroys session
├── authors.php        — List/delete authors
├── add_author.php     — Add new author
├── edit_author.php    — Edit existing author
├── quotes.php         — List/delete quotes
├── add_quote.php      — Add new quote
├── edit_quote.php     — Edit existing quote
├── run_setup.php      — One-time DB initializer
├── setup.sql          — Manual SQL alternative
├── css/
│   └── style.css      — Custom stylesheet
└── includes/
    ├── db.php         — Database connection
    ├── auth.php       — Session & login helpers
    └── nav.php        — Navigation menu partial
```
