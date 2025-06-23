# Church Management System (ChMS) – Kenya Edition

A modular, web-based church management system built with Laravel.  
Tailored for churches in Kenya to manage members, giving, events, communication, and media.

---

## ✨ Features

- 🧍 Member Registration, Groups & Attendance
- 💰 Donations via M-PESA (Daraja API)
- 📅 Event Management + RSVPs
- 📢 Bulk SMS & Email Communication
- 🎙️ Sermon & Media Archive
- 📊 Finance & Participation Reports
- 🔐 Role-Based Access Control
- 📁 PDF/Excel Export for Reports

---

## 🛠️ Tech Stack

- **Backend:** Laravel 11
- **Frontend:** Blade / Inertia.js + Vue 3
- **Database:** MySQL
- **API Integration:** M-PESA Daraja, Mailchimp, Africa’s Talking
- **Optional Hosting:** CPanel, DigitalOcean, or Cloudways

---

## 🚀 Installation Guide

### Prerequisites
- PHP 8.2+
- Composer
- MySQL
- Node.js & npm
- Laravel CLI: `composer global require laravel/installer`

### Setup Steps

1. **Clone the repository**

```bash
git clone https://github.com/yourusername/chms.git
cd chms
```

### Install PHP and JavaScript dependencies
```bash
composer install
npm install && npm run dev
```

### Install PHP and JavaScript dependencies.
```bash
cp .env.example .env
php artisan key:generate
```


###  Update the .env file with your settings:
```env
APP_NAME="ChMS Kenya"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chms
DB_USERNAME=root
DB_PASSWORD=yourpassword

# Optional API Configurations (Set if using)
MPESA_CONSUMER_KEY=
MPESA_CONSUMER_SECRET=
MPESA_SHORTCODE=
MPESA_PASSKEY=
AT_API_KEY=
AT_USERNAME=
MAILCHIMP_APIKEY=
```

### Run migrations and seed the database
```bash
php artisan migrate --seed
```

### Start the development server
```bash
php artisan serve
```

--- The system should now be available at http://localhost:8000

##  🔐 Admin Login Details (for seed setup)
- Check at the AdminSeeder

##  ✅ Next Steps
-   Set up SMS and M-PESA credentials in .env

-   Upload your church logo and set branding options

-   Add roles, groups, and test a donation

##  📞 Need Help?
-   For any issues, reach out to:

    Nik – Lead Developer
    📧 nickforbizz@gmail.com
    📱 +254 707722247