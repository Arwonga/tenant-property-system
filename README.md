# 🏢 Enterprise Property Management System

A robust, full-stack property and tenant management system built with Laravel. This platform is designed to automate real estate operations, featuring an automated billing engine, seamless M-Pesa payment integration, and a mobile-responsive command center for property managers.

## ✨ Key Features

### 👑 Admin Command Center
* **Live Financial Dashboard:** Real-time tracking of Total Revenue and Uncleared Arrears.
* **Portfolio Analytics:** High-level metrics for Total Properties, Unit Counts, and Occupancy/Vacancy rates.
* **Recent Cash Flow:** Dynamic ledger displaying the latest incoming payments across all properties.
* **Fully Responsive UI:** Built with a mobile-first Tailwind CSS grid, ready for web and future mobile app integration.

### 👥 Tenant Portal
* **Smart Balance Calculator:** Automatically calculates outstanding arrears by tracking partial and fully unpaid invoices.
* **Automated Monthly Billing:** Custom backend cron-ready engine that hunts down occupied units and generates precise monthly invoices without double-billing.
* **Integrated Payments:** Built-in Lipa Na M-Pesa gateway via STK push, allowing tenants to clear balances instantly from their secure dashboard.
* **Maintenance Engine:** Dedicated ticketing system for tenants to report issues directly to management, complete with status tracking (Pending/Resolved).

## 🛠️ Tech Stack
* **Backend:** PHP 8.x, Laravel 11.x
* **Frontend:** Blade Templating, Tailwind CSS, Alpine.js
* **Database:** MySQL
* **Payments:** Safaricom Daraja API (M-Pesa Express/STK Push)

## 🚀 Getting Started

### Prerequisites
* PHP >= 8.2
* Composer
* MySQL
* Node.js & NPM

### Installation

1. **Clone the repository**
   ```bash
   git clone [https://github.com/Arwonga/tenant-property-system.git](https://github.com/Arwonga/tenant-property-system.git)
   cd tenant-property-system
