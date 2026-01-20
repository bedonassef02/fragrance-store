<p align="center">
  <img src="https://via.placeholder.com/200x80/1a1a1a/d4af37?text=MOON" alt="Moon Logo">
</p>

<p align="center">
  <strong>✨ Elegant Arabian Fashion ✨</strong>
</p>

<p align="center">
  Premium abayas, bags, and accessories for the modern woman
</p>

---

## 🌙 About Moon

Moon is an exclusive online boutique offering premium Arabian fashion. Built with modern web technologies, it features a fluid, responsive shopping experience and a powerful administrative dashboard.

We specialize in:
- **Luxurious Abayas** - Handcrafted designs with exquisite detailing
- **Designer Bags** - Elegant accessories for every occasion
- **Modern Modest Fashion** - Blending tradition with contemporary style

---

## ✨ Key Features

### 🛍️ Storefront
- **Responsive Design**: Mobile-first approach with smooth animations and transitions.
- **Product Management**: Support for multiple product variants (Color/Size), image galleries, and zoom functionality.
- **Collections & Categories**: Organized browsing with dedicated collection pages and category filters.
- **Shopping Cart**: Real-time cart updates with coupon code support.
- **Guest Checkout**: Seamless checkout process for both registered users and guests.
- **Product Reviews**: Customer reviews with star ratings and image uploads.
- **Advanced Filtering**: Filter products by color, price, availability, and more.

### 🛡️ Admin Dashboard
- **Secure Authentication**: Dedicated admin login with separate guard.
- **Dashboard Analytics**: Real-time overview of orders, revenue, and customer stats.
- **Product Management**: Full CRUD for products, variants, and stock management.
- **Order Management**: Track and update order statuses (Pending -> Delivered).
- **Reviews Moderation**: Approve or reject customer reviews before they go live.
- **Collections & Categories**: Manage site structure and featured collections easily.
- **Profile Management**: Update admin credentials and profile settings.

---

## 🛠️ Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade Templates, Alpine.js, Vanilla JS
- **Styling**: Tailwind CSS 4 (Custom Design System)
- **Database**: MySQL / SQLite
- **Build Tool**: Vite
- **Assets**: Custom SVG Icons, Google Fonts (Playfair Display, Inter)

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/moon.git
   cd moon
   ```

2. **Install Backend Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   Configure your `.env` file with your database credentials, then run:
   ```bash
   php artisan migrate --seed
   ```
   *Note: The seeder will create demo products, orders, reviews, and a default admin user.*

6. **Run Development Servers**
   ```bash
   # Terminal 1 (Laravel Server)
   php artisan serve

   # Terminal 2 (Vite Hot Reload)
   npm run dev
   ```
---

## 📂 Project Structure

- **`app/Http/Controllers/Admin`**: Admin-specific controllers (Dashboard, Products, Orders, etc.).
- **`app/Services`**: Business logic layers (e.g., `ReviewService`, `ProductService`).
- **`resources/views/admin`**: Blade templates for the admin panel.
- **`resources/js`**: Modular JavaScript files (`product.js`, `cart.js`, `quick-add.js`, etc.) using generic `ui-helpers.js`.
- **`routes/web.php`**: Organized routes for Store, Cart, Checkout, and Admin prefixes.

---

## 📧 Contact

For inquiries: contact@moon-fashion.com

---

<p align="center">Made with ❤️ for Arabian Fashion</p>
