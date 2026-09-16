# 🐟 Royal Fish Store - Fresh Fish & Seafood E-Commerce Platform

<div align="center">

![Royal Fish Store Logo](https://img.shields.io/badge/Royal_Fish_Store-Fresh_%26_Hygienic-fc490f?style=for-the-badge&logo=fish&logoColor=white)
![React](https://img.shields.io/badge/React_18-20232A?style=for-the-badge&logo=react&logoColor=61DAFB)
![TypeScript](https://img.shields.io/badge/TypeScript-007ACC?style=for-the-badge&logo=typescript&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel_10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![WhatsApp Cloud API](https://img.shields.io/badge/WhatsApp_API-25D366?style=for-the-badge&logo=whatsapp&logoColor=white)

**An ultra-fast, modern hyper-local e-commerce web platform for fresh fish, river catches, seafood, and tender meats with WhatsApp Cloud API automation and real-time order tracking.**

[Live Demo](https://royalfishstore.com) • [Report Bug](https://github.com/Imikkhan/royalfishstore/issues) • [Request Feature](https://github.com/Imikkhan/royalfishstore/issues)

</div>

---

## 🌟 Key Features

### 🛍️ Customer Experience
- **Fresh Catch Catalog:** Daily fresh Hilsa (পদ্মার ইলিশ), Rohu, Katla, Jumbo Prawns, Bhetki fillets, Crabs, Country Chicken, and Mutton.
- **Smart Pincode Verification:** Instant delivery coverage checker for Kolkata, Newtown, Salt Lake, and suburban sectors.
- **Delivery Time Slots:** Flexible slot selection at checkout (`⚡ Express Delivery (30-45 mins)`, `🌅 Morning Slot (07:00 AM - 12:00 PM)`, `🌆 Evening Slot (04:00 PM - 08:30 PM)`).
- **Passwordless WhatsApp OTP Login:** Instant 4-digit OTP delivered straight to the customer's WhatsApp mobile number.
- **Real-Time Order Tracking:** Visual live order progress tracker (`Placed` ➔ `Processing` ➔ `Dispatched / Out for Delivery` ➔ `Delivered` / `Cancelled`) with automated background polling.
- **Rider-Customer Live Chat:** Built-in chat channel between the assigned delivery rider and the customer.
- **High-Converting Landing OnePager:** Tailored viral promotional page for Facebook & Instagram ad campaigns featuring special Hilsa offers and direct checkout.

### ⚙️ Admin & Operations Portal
- **Real-Time Order Management:** Filter, process, invoice, dispatch, deliver, or cancel customer orders with real-time stock adjustments.
- **WhatsApp Cloud API Integration:** Automated order placement notifications, status update alerts, and payment receipts sent directly to customers via WhatsApp.
- **Comprehensive Product & Inventory System:** Stock counters, out-of-stock toggles, cutting style options, gross/net weight specifications, and media gallery.
- **Category & Banner Manager:** Drag-and-drop sort orders, custom promotional hero banners, and highlight ribbons.
- **OnePager & Facebook Ad Builder:** Visual editor for the landing page hero, customer review cards, delivery policy FAQs, and ad analytics.
- **Role-Based Access Control (RBAC):** Granular permissions for Admin, Manager, and Rider roles.
- **Printable Invoices:** Clean thermal / A4 printable order receipts with item breakdown, delivery slot, and payment mode.

---

## 🛠️ Tech Stack

### Frontend
- **Framework:** [React 18](https://react.dev/) + [Vite](https://vitejs.dev/)
- **Language:** [TypeScript](https://www.typescriptlang.org/)
- **Styling:** [Tailwind CSS](https://tailwindcss.com/)
- **Icons:** [Lucide React](https://lucide.dev/)

### Backend & Database
- **Framework:** [Laravel 10](https://laravel.com/) (RESTful API Architecture)
- **Database:** [MySQL](https://www.mysql.com/) / MariaDB
- **Authentication:** Laravel Sanctum (Token-based) + WhatsApp OTP Verification
- **External Services:** Meta WhatsApp Cloud API (Graph API v20.0+)

---

## 📁 Repository Structure

```
royal-fish-store/
├── src/                          # React Frontend Application
│   ├── components/               # Header, Footer, CategoryList, BottomNav, Modals
│   ├── context/                  # AppContext (State Management, Cart, Auth, Addresses)
│   ├── pages/                    # Home, Catalog, ProductDetails, Cart, Profile, Onepager
│   ├── types.ts                  # TypeScript interfaces and schemas
│   ├── config.ts                 # API and asset base configuration
│   └── App.tsx                   # Main router and layout shell
├── backend/                      # Laravel 10 Backend API & Admin Dashboard
│   ├── app/
│   │   ├── Http/Controllers/    # ApiController, AdminController, RiderController
│   │   ├── Models/               # Order, Product, Category, User, Setting, Rider
│   │   └── Services/             # WhatsAppService, OrderWhatsAppService
│   ├── database/                 # Migrations, seeders, production SQL patches
│   ├── resources/views/admin/    # Blade templates for Admin Panel
│   └── routes/                   # api.php, web.php
├── public/                       # Static public assets (Favicons, logos)
├── package.json                  # Node.js dependencies
└── README.md                     # Documentation
```

---

## 🚀 Getting Started

### Prerequisites
- **Node.js:** v18.0 or later
- **PHP:** v8.1 or later with `pdo_mysql`, `curl`, `mbstring`, `openssl`
- **Composer:** v2.x
- **MySQL:** v8.0 or MariaDB v10.4+

---

### 1. Backend Setup

```bash
# Navigate to backend directory
cd backend

# Install PHP dependencies
composer install

# Copy environment configuration
cp .env.example .env

# Generate application encryption key
php artisan key:generate

# Configure your database & WhatsApp credentials in .env:
# DB_DATABASE=royalfishstore
# DB_USERNAME=root
# DB_PASSWORD=your_password
# WHATSAPP_ACCESS_TOKEN=your_meta_token
# WHATSAPP_PHONE_NUMBER_ID=your_phone_id

# Run database migrations and seed default records
php artisan migrate --seed

# Start the local development server
php artisan serve --port=8000
```

---

### 2. Frontend Setup

```bash
# In the project root directory:
npm install

# Configure API URL in src/config.ts if different from default:
# export const API_BASE_URL = 'http://127.0.0.1:8000/api';

# Run the local frontend dev server
npm run dev
```

The application will be accessible at: `http://localhost:5173`

---

## 📦 Production Build & Deployment

### Build the Frontend
```bash
npm run build
```
The optimized production bundle will be generated in the `dist/` directory.

### Deploying to cPanel / Shared Hosting
1. **Frontend:** Upload the contents of the `dist/` folder directly to your web server's `public_html/`.
2. **Backend:** Deploy the `backend/` folder to a directory outside `public_html`, configure database credentials in `.env`, and link storage.
3. **Clear Cache:** Access `https://yourdomain.com/api/clear-cache` to reset Laravel configuration and route caches.

---

## 💬 WhatsApp Integration Details

Royal Fish Store uses Meta's official WhatsApp Business Cloud API for:
- **Authentication:** Instant OTP login codes for frictionless customer registration without passwords.
- **Transactional Updates:** Order confirmation messages with delivery time slot, address, and live tracking links.
- **Admin & Rider Alerts:** Instant order dispatch, delivery status changes, and cancellation notices.

---

## 👨‍💻 Author & Maintainer

- **Developer:** [Imikkhan](https://github.com/Imikkhan)
- **Repository:** [https://github.com/Imikkhan/royalfishstore](https://github.com/Imikkhan/royalfishstore)

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).
