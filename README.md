# Website Theme: E-commerce (Mama Shop)

## Standarized Features:
- [Finn/Joel] Navigation Bar w/ Search Bar (Home, Search Bar, Catalogue, Order History, Cart Icon --> redirect if not login, Login/Register)
- All pages to be responsive (Bootstrap)
- Parameterized Query for all inputs

## Features - 1 page/feature:
### Common Pages - All users
- [Keefe] Home Page (Carousell of Promotions Items, Icons of Products Categories --> link to product catalogue)
- [Shawn] Login/Logout (Customer/Staff/Admin)
- [Shawn] Register (Customer)
- [Joel] Update Profile
- [Finn] Product Catalogue w/ shopping cart
	-> Product Description
	-> Number of items left
- [Joel] About Us
- [Shawn] Order History (Filter by Customer)
- [Ray Son] Customer Feedback/Review (Can view w/o login but need to login to leave review)

### Customers
- [Ray Son] Checkout page
- [Ray Son] Payment Page

### Staff + Admin
- [Finn] Update Product Catalogue - CRUD Product Items

### Admin
- [Keefe] Dashboard (Overall Sales)
- [Shawn] Order History (Filter by Customer)


## \<Roles\>:
- Admin: View dashboard
- Staff: Maintain catalogs
- Customers

## [Joel] DB Schemas
- Products
	- [product_id (INT), 
	- product_name (VARCHAR 255), 
	- product_desc (LONGTEXT), 
	- product_category (VARCHAR 255), 
	- quantity (INT), 
	- price (FLOAT), 
	- is_active (INT), 
	- created_at (DATETIME), 
	- promo (FLOAT)]
- Users
	- [email (VARCHAR 255), 
	- username (VARCHAR 255), 
	- password (VARCHAR 255), 
	- priority (INT)]
- Cart_Item 
	- [Products_product_id (INT), 
	- Order_History_order_id (INT), 
	- price (FLOAT), 
	- quantity (INT)]
- Order_History
	- [order_id (INT), 
	- Users_email (VARCHAR 255), 
	- order_at (DATETIME), 
	- payment_mtd (VARCHAR 15), 
	- card_num (VARCHAR 16), 
	- purchased (INT)]
- Feedback
	- [Products_product_id (INT), 
	- Users_email (VARCHAR 255), 
	- comments (LONGTEXT), 
	- ratings (INT)]
