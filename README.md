# ~ Urban Bites - Restaurant Ordering System

Urban Bites is a PHP and MySQL based restaurant ordering system.

## Requirements

* PHP 8+
* MySQL
* Web Browser

---

# ~ Setup

Urban Bites can be run using either **XAMPP** or **Local (LocalWP)**.

---

# ~ Option 1: XAMPP Setup

### Requirements

* XAMPP
* PHP 8+
* MySQL
* Web Browser

### Steps

**1. Download or clone this repository.**

**2. Move the project folder to:**

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\Restaurant-Ordering-System\
```

**3. Open XAMPP Control Panel and start:**

* Apache
* MySQL

**4. Open phpMyAdmin:**

```text
http://localhost/phpmyadmin
```

**5. Create a new database.**

For example:

```text
restaurant_ordering_system
```

**6. Select the newly created database and import:**

```text
database.sql
```

The `database.sql` file is included with this project.

**7. Open:**

```text
includes/config.php
```

Update the database details according to your XAMPP setup:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "restaurant_ordering_system";
```

**8. Open the project in your browser:**

```text
http://localhost/Restaurant-Ordering-System/
```

---

# ~ Option 2: Local (LocalWP) Setup

This project is a **Core PHP + MySQL project**. It does not need to be installed as a WordPress theme or plugin.

Local is only being used as the local PHP/MySQL server environment.

### Requirements

* Local (LocalWP)
* PHP 8+
* MySQL
* Web Browser

### Steps

**1. Install and open Local.**

Create or open your Local site.

**2. Open your Local site's folder.**

Go to the site's:

```text
app/public/
```

Copy the complete project folder there.

For example:

```text
app/public/Restaurant-Ordering-System/
```

**3. Start your Local site.**

Make sure the site is running.

**4. Open the Local site's Database section.**

Click the **Database** tab and open **Adminer**.

**5. Open the database used by your Local site.**

Local normally provides the database connection details in the site's **Database** section.

You may see details such as:

* Database name
* Username
* Password
* Host
* Port

**6. Import the project's database.**

In Adminer, select the database and import:

```text
database.sql
```

The `database.sql` file is included with this project.

**7. Open:**

```text
includes/config.php
```

Enter the database details shown in your Local site's **Database** section.

Example:

```php
$host = "localhost";
$user = "root";
$password = "YOUR_LOCAL_DATABASE_PASSWORD";
$database = "YOUR_LOCAL_DATABASE_NAME";
```

Use the **actual values shown by Local**. Do not copy the example values unless they match your Local installation.

**8. Open the project through your Local site's URL.**

For example:

```text
http://your-local-site.local/Restaurant-Ordering-System/
```

Use the URL shown by Local for your site.

---

# ~ Admin Email & Password

**Email:**

admin@urbanbites.com

**Password:** 

Admin123


---

# ~ Main Features

* Customer registration and login
* Menu and food details
* Shopping cart
* Checkout and order placement
* Customer profile management
* Password change
* Admin panel
* CSRF protection
* Prepared statements
* Password hashing

---

# ~ Author

**Talha Imran**

GitHub:

https://github.com/talha-imran-0/Restaurant-Ordering-System
