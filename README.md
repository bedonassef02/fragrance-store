<p align="center">
  <img src="https://via.placeholder.com/200x80/1a1a1a/d4af37?text=MOON" alt="Moon Logo">
</p>

<p align="center">
  <strong>✨ Exquisite Art of Application ✨</strong>
</p>

<p align="center">
  Premium perfumes, authentic oils, and exclusive fragrances for the connoisseur
</p>

---

## 🌙 About Moon

Moon is an exclusive online boutique offering premium fragrances. Built with modern web technologies, it features a fluid, responsive shopping experience and a powerful administrative dashboard tailored for complex product variations like concentrations and bottle sizes.

We specialize in:
- **Luxurious Perfumes** - High-concentration extracts (Parfum, EDP, EDT)
- **Authentic Oils** - Pure, alcohol-free essence oils
- **Inspired Collections** - Expertly crafted alternatives to niche favorites

---

## ✨ Key Features

### 🛍️ Storefront
- **Responsive Design**: Mobile-first approach with smooth animations.
- **Advanced Product Search**: Filter by **Olfactory Notes** (Top, Middle, Base), **Concentration**, and **Scent Function**.
- **Product Variants**: Support for multiple bottle sizes (e.g., 50ml, 100ml) and types (Bottle, Decant).
- **Collections**: Organized browsing by 'Inspired By', 'Occasion', or 'Gender'.
- **Shopping Cart**: Real-time cart with coupon code support.
- **Reviews**: Customer reviews with star ratings and photos.

### 🛡️ Admin Dashboard
- **Product Management**: Full CRUD for products with detailed attributes (Notes breakdown, Concentration details).
- **Inventory Control**: Track stock levels per bottle size/variant.
- **Order Management**: Process orders from 'Pending' to 'Delivered'.
- **Analytics**: Orders, Revenue, and Best-selling fragrances.
- **Content Management**: Manage Brands, Collections, Notes, and Categories.

---

## 🛠️ Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates, Alpine.js, Vanilla JS
- **Styling**: Tailwind CSS 4
- **Database**: MySQL / SQLite
- **Build Tool**: Vite

---

## 📂 Project Structure

- **`app/Models`**: Includes domain-specific models like `Note`, `ProductVariant` (for capacities), `Brand`.
- **`app/Services`**: Business logic layers (`ProductService`, `CartService`).
- **`app/Http/Controllers/Admin`**: Admin management for Products, Orders, Notes, etc.
- **`routes/web.php`**: Organized routes for Shop, Cart, and Admin panels.

---

## 🚀 Getting Started

1. **Clone & Install**
   ```bash
   git clone https://github.com/yourusername/moon.git
   composer install
   npm install
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   ```

3. **Run**
   ```bash
   php artisan serve
   npm run dev
   ```

---

## 📧 Contact

For inquiries: contact@moon-fragrances.com

---

<p align="center">Made with ❤️ for the Art of Scent</p>
