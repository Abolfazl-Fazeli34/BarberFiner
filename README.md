# BarberFiner - سامانه هوشمند آرایشگاه 💈

سیستم نوبت‌دهی آنلاین، مدیریت آرایشگران و خدمات آرایشگاه.

## ویژگی‌ها

- **نوبت‌دهی آنلاین**: رزرو نوبت برای خدمات مختلف
- **مدیریت آرایشگران**: پروفایل و برنامه کاری هر آرایشگر
- **مدیریت خدمات**: دسته‌بندی، قیمت و مشخصات خدمات
- **پنل مدیریت**: داشبورد کامل مدیریت نوبت‌ها، کارکنان و تنظیمات
- **سیستم تخفیف**: مدیریت کدهای تخفیف
- **تنظیمات پرداخت**: پشتیبانی از روش‌های مختلف پرداخت
- **حالت تعمیر**: قابلیت Maintenance Mode

## راه‌اندازی با Docker

### پیش‌نیازها
- Docker Engine 20.10+
- Docker Compose v2.0+

### نصب سریع

```bash
git clone https://github.com/Abolfazl-Fazeli34/BarberFiner.git
cd BarberFiner
cp .env.example .env
./barberfiner.sh start
```

**آدرس سایت:** http://localhost:8680  
**پنل مدیریت:** http://localhost:8680/admin/login.php

### پورت‌ها (فقط localhost)

| سرویس      | پورت | آدرس                              |
|------------|------|-----------------------------------|
| برنامه اصلی | 8680 | http://localhost:8680             |
| MySQL      | 8681 | localhost:8681                    |
| phpMyAdmin | 8682 | http://localhost:8682             |

## ساختار پروژه

```
BarberFiner/
├── docker/
│   └── php/
│       ├── Dockerfile
│       ├── php.ini
│       └── apache.conf
├── barber-finer/
│   ├── admin/           # پنل مدیریت
│   │   ├── login.php
│   │   ├── dashboard.php
│   │   ├── appointments.php
│   │   ├── employees.php
│   │   ├── discounts.php
│   │   ├── payment_settings.php
│   │   ├── settings.php
│   │   └── suppliers.php
│   ├── assets/
│   │   ├── css/         # استایل‌ها
│   │   └── images/      # تصاویر
│   ├── config/
│   │   └── db.php       # اتصال دیتابیس (Docker-ready)
│   ├── database/
│   │   └── schema.sql   # طرح پایگاه داده + داده‌های نمونه
│   ├── includes/
│   │   ├── header.php
│   │   └── footer.php
│   ├── pages/
│   │   ├── about.php
│   │   └── contact.php
│   ├── uploads/         # آپلودهای سایت
│   ├── .htaccess
│   ├── index.php
│   ├── services.php
│   ├── search.php
│   └── maintenance.php
├── docker-compose.yml
├── .env.example
├── .gitignore
├── .dockerignore
└── barberfiner.sh       # اسکریپت مدیریت
```

## دستورات مدیریتی

```bash
./barberfiner.sh start          # راه‌اندازی
./barberfiner.sh stop           # توقف
./barberfiner.sh status         # وضعیت کانتینرها
./barberfiner.sh logs           # مشاهده لاگ‌ها
./barberfiner.sh shell          # ورود به shell
./barberfiner.sh db-backup      # پشتیبان‌گیری
```

## اطلاعات ورود (پیش‌فرض)

| نقش  | نام کاربری | رمز عبور  |
|------|-----------|-----------|
| مدیر | admin     | admin1234 |

> **توجه:** رمز عبور موجود در دیتابیس hash شده است. برای ورود از اطلاعات داده‌های اولیه استفاده کنید.

## استقرار روی سرور

تنظیم Nginx برای ساب‌دامین `barberfiner.ip3.ir`:

```nginx
server {
    listen 80;
    server_name barberfiner.ip3.ir;

    location / {
        proxy_pass http://127.0.0.1:8680;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

## تکنولوژی‌ها

- **Backend:** PHP 8.3
- **وب‌سرور:** Apache 2.4
- **پایگاه داده:** MySQL 8.0
- **مدیریت دیتابیس:** phpMyAdmin 5
- **کانتینرسازی:** Docker + Docker Compose
