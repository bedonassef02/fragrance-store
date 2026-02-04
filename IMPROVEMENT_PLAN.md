# Moon Store Improvement Plan

## Phase 1: Smart Recommendations ("The Scent Matcher")
**Goal**: Increase specific relevance of "You May Also Like" products to drive conversion.

### 1.1 Weighted Scoring Algorithm
Modify `ProductService::getRelatedProducts` to calculate similarity scores instead of random selection.
- **Base Score**: 0
- **Same Collection**: +50 points
- **Shared Notes**: +10 points per matching note
- **Same Concentration**: +5 points
- **Same Category**: +5 points
- **Price Match**: +5 points (within 20% range)

### 1.2 Implementation Details
- Update `App\Services\ProductService.php`.
- Create a test route or command to verify the logic.

---

## Phase 2: Personalization Engine
**Goal**: Create a tailored experience for returning users (Guest & Auth).

### 2.1 Tracking History (Cookies)
- Implement a lightweight tracking system using encrypted HTTP-only cookies.
- **Key**: `moon_history`
- **Data Structure**:
  ```json
  {
    "viewed_products": [101, 45, 12], // Limit to last 10
    "last_search": "vanilla wood"
  }
  ```
- **Middleware**: Create `TrackProductView` middleware to handle cookie updates on `ProductController@show`.

### 2.2 Functional Usage
- **Auth Sync**: When a user logs in, merge cookie data into the `product_views` table.
- **"Picked for You" Section**:
  - Add to Homepage (`welcome.blade.php`) and Shop Top (`shop/index.blade.php`).
  - Logic: Fetch products from `viewed_products`. If empty, fall back to `Trending`.

---

## Phase 3: Feature Expansion (Wishlist & Dashboard)
**Goal**: Improve user retention and account utility.

### 3.1 Wishlist
- **Database**: Create `wishlists` table (`user_id`, `product_id`).
- **Backend**: `WishlistController` (Toggle, Index).
- **Frontend**:
  - Add "Heart" icon to `ProductCard` component.
  - Create `/wishlist` view (Grid of saved items).

### 3.2 Enhanced User Dashboard
- **Views**: Create dedicated views for:
  - `account.orders` (List of past orders with status).
  - `account.profile` (Edit Name/Email/Password).
  - `account.addresses` (Manage shipping addresses).

---

## Phase 4: Content & UX Polish
**Goal**: Professionalize the store presence.

### 4.1 Static Pages
- Create `resources/views/contact.blade.php` with a functional form (or mailto link).
- Create `resources/views/faq.blade.php` with accordion-style questions.
- Update `about.blade.php` if needed.

### 4.2 Error Handling
- Create custom designs for `404.blade.php` and `500.blade.php` matching the Moon aesthetic.

### 4.3 UI Refinements
- **Loading States**: Add skeleton loaders for AJAX content (Reviews, Load More).
- **Mobile Filters**: Optimize the slide-over filter menu for better performance.
