<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## API Reference & Testing Guide (Postman)

**Base URL:** `http://localhost:8000/api`

**Auth:** For protected routes, set `Authorization: Bearer <token>` header (get token from Login/Register response).

---

### 🧑 User-Side Endpoints (Public + Authenticated User)

#### 🔓 Public Endpoints (No Auth Required)

| Step | Method | Endpoint | Description | Request Body / Query Params | Expected Status |
|------|--------|----------|-------------|----------------------------|-----------------|
| **1** | **POST** | `/api/register` | Register a new user | `{ "name": "John", "email": "john@test.com", "password": "password123" }` | **201** — returns `user` + `token` |
| **2** | **POST** | `/api/login` | Login and get auth token | `{ "email": "john@test.com", "password": "password123" }` | **200** — returns `user` + `token` |
| **3** | **GET** | `/api/categories` | List all categories | — | **200** — array of categories |
| **4** | **GET** | `/api/categories/{id}` | Get category with products | — | **200** — category with `products` array |
| **5** | **GET** | `/api/products` | List products (with filters) | `?search=laptop&category_id=1&min_price=10&max_price=100&per_page=12` | **200** — paginated products |
| **6** | **GET** | `/api/products/{id}` | Get single product detail | — | **200** — product with category, reviews, discounts |
| **7** | **GET** | `/api/products/{id}/reviews` | Get reviews for a product | — | **200** — array of reviews |

> **Flow:** Test steps 1–2 first to get your token, then set `Authorization: Bearer <token>` for all protected endpoints below. Steps 3–7 can be tested before or after login.

#### 🔐 Authenticated User Endpoints (Bearer Token Required)

| Step | Method | Endpoint | Description | Request Body / Query Params | Expected Status |
|------|--------|----------|-------------|----------------------------|-----------------|
| **8** | **GET** | `/api/user` | Get current authenticated user | — | **200** — user object |
| **9** | **POST** | `/api/logout` | Logout (revoke token) | — | **200** — `{ "message": "Logged out" }` |
| **10** | **GET** | `/api/profile` | Get user profile | — | **200** — user object |
| **11** | **PUT** | `/api/profile` | Update profile | `{ "name": "John Updated", "email": "john@test.com", "phone": "1234567890", "address": "123 Main St" }` | **200** — updated user |
| **12** | **PUT** | `/api/change-password` | Change password | `{ "current_password": "oldpass", "new_password": "newpass123", "new_password_confirmation": "newpass123" }` | **200** — success message |
| **13** | **GET** | `/api/wishlists` | List wishlist items | — | **200** — array of wishlists with products |
| **14** | **POST** | `/api/wishlists` | Add product to wishlist | `{ "product_id": 1 }` | **201** — wishlist item |
| **15** | **DELETE** | `/api/wishlists/{id}` | Remove from wishlist | — | **200** — success message |
| **16** | **GET** | `/api/carts` | List cart items | — | **200** — array of cart items with products |
| **17** | **POST** | `/api/carts` | Add item to cart | `{ "product_id": 1, "quantity": 2 }` | **201** — cart item (increases qty if already in cart) |
| **18** | **PUT** | `/api/carts/{id}` | Update cart item quantity | `{ "quantity": 3 }` | **200** — updated cart item |
| **19** | **DELETE** | `/api/carts/{id}` | Remove item from cart | — | **200** — success message |
| **20** | **POST** | `/api/orders` | Checkout (creates order from cart) | `{}` | **201** — order with items (cart is cleared) |
| **21** | **GET** | `/api/orders` | List user's orders | — | **200** — array of orders |
| **22** | **GET** | `/api/orders/{id}` | Get order details | — | **200** — order with items |
| **23** | **POST** | `/api/orders/{id}/reorder` | Re-order (adds previous items back to cart) | `{}` | **200** — success with count |
| **24** | **POST** | `/api/reviews` | Write a product review | `{ "product_id": 1, "rating": 5, "comment": "Great!" }` | **201** — review (one per user per product) |

> **Ratings** are 1–5 (integer). One review per user per product (re-submitting updates the existing one).

#### 📋 User-Side Recommended Testing Flow

1. **Register → Login** → copy token → set `Authorization: Bearer <token>`
2. Browse **Categories** & **Products** (public — no auth needed)
3. Test **Wishlist**: add, list, remove
4. Test **Cart**: add items, update quantity, list, remove
5. **Checkout** → creates an order from cart items
6. View your **Orders** & **Reorder**
7. Write a **Review** for a purchased product
8. Update your **Profile** & **Change Password**
9. **Logout** → verify protected routes return 401

---

### 🛡️ Admin-Side Endpoints (Admin Role Required)

> **Prerequisite:** The user must have `role = "admin"` in the database. Login with that admin account and use its Bearer token for all requests below.

| Step | Method | Endpoint | Description | Request Body / Query Params | Expected Status |
|------|--------|----------|-------------|----------------------------|-----------------|
| **1** | **GET** | `/api/admin/stats` | Dashboard stats (users, products, categories, orders counts + recent orders) | — | **200** — stats object |
| **2** | **GET** | `/api/admin/categories` | List all categories with product count | — | **200** — categories with `products_count` |
| **3** | **POST** | `/api/admin/categories` | Create a new category | `{ "name": "Electronics", "description": "Electronic devices and accessories" }` | **201** — created category |
| **4** | **GET** | `/api/admin/categories/{id}` | Get a single category | — | **200** — category |
| **5** | **PUT** | `/api/admin/categories/{id}` | Update a category | `{ "name": "Updated Name", "description": "Updated description" }` | **200** — updated category |
| **6** | **DELETE** | `/api/admin/categories/{id}` | Delete a category | — | **200** — success message |
| **7** | **GET** | `/api/admin/products` | List all products with category & discount info | — | **200** — products array |
| **8** | **POST** | `/api/admin/products` | Create a new product | `{ "category_id": 1, "name": "Laptop", "price": 999.99, "stock": 10, "description": "High performance laptop" }` | **201** — created product |
| **9** | **GET** | `/api/admin/products/{id}` | Get a single product | — | **200** — product |
| **10** | **PUT** | `/api/admin/products/{id}` | Update a product | `{ "category_id": 1, "name": "Updated Laptop", "price": 899.99, "stock": 15 }` | **200** — updated product |
| **11** | **DELETE** | `/api/admin/products/{id}` | Delete a product | — | **200** — success message |
| **12** | **GET** | `/api/admin/orders` | List all orders with user & items | — | **200** — orders array |
| **13** | **GET** | `/api/admin/orders/{id}` | Get order details with user & items | — | **200** — order object |
| **14** | **PUT** | `/api/admin/orders/{id}/status` | Update order status | `{ "status": "processing" }` | **200** — updated order |
| **15** | **GET** | `/api/admin/users` | List all users with order count | — | **200** — users with `orders_count` |

> **Valid order statuses:** `pending`, `processing`, `completed`, `cancelled`

#### 📋 Admin-Side Recommended Testing Flow

1. Login with an **admin account** → copy token
2. Check **Dashboard Stats** to see overview
3. **Categories CRUD**: Create → List → Get → Update → Delete
4. **Products CRUD**: Create → List → Get → Update → Delete
5. **Orders**: List all → Get details → Update status (e.g. pending → processing → completed)
6. **Users**: List all users