# Website Theme: E-commerce (Mama Shop)

## Standarized Features:
- Navigation Bar w/ Search Bar (Home, Search Bar, Catalogue, Order History, Cart Icon --> redirect if not login, Login/Register)
- All pages to be responsive (Bootstrap)
- Parameterized Query for all inputs

## Features - 1 page/feature:
### Common Pages - All users
- [Keefe] Home Page (Carousell of Promotions Items, Icons of Products Categories --> link to product catalogue)
- [Shawn] Login (Customer/Staff/Admin)
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
- [Keefe] Update Staff account - CRUD users
- [Shawn] Order History (Filter by Customer)


## \<Roles\>:
- Admin: View dashboard
- Staff: Maintain catalogs
- Customers

## [Joel] DB Schemas (CRUD for all)
- Products (Has a promo column) 
	[product_id (INT 4), product_img (BLOB), product_name (VARCHAR 255), product_desc (TEXT), quantity (INT 6), price (FLOAT), is_active (INT 1), created_at (DATETIME), promo (FLOAT)]
- Users
	[user_id (INT 4), email (VARCHAR 255), password (VARCHAR 255), profile_img (BLOB)]
- Shopping_Cart <Linked to userID and email>
	[user_id (INT 4), email (VARCHAR 255), product_id (INT 4), quantity (INT 6)]
- Order_History
	[order_id (INT 9), user_id (INT 4), email(VARCHAR 255), product_id (INT 4), quantity (INT 6), price (FLOAT), order_at (DATETIME), payment_mtd (VARCHAR 15), card_num (INT 19)]
- Feedback
	[user_id (INT 4), email (VARCHAR 255), comments (LONGTEXT)]
