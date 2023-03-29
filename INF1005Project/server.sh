
sudo useradd -p $(openssl passwd -crypt ) dev
sudo usermod -aG sudo dev

sed -re 's/^(PasswordAuthentication)([[:space:]]+)no/\1\2yes/' -i /etc/ssh/sshd_config
sudo systemctl restart ssh.service

sudo apt update
sudo apt upgrade -y

sudo apt install -y apache2
sudo usermod -a -G www-data dev

sudo chown -R dev:www-data /var/www/html
sudo chmod 2775 /var/www/html
find /var/www/html -type d -exec sudo chmod 2775 {} \;
find /var/www/html -type f -exec sudo chmod 0664 {} \;

sudo apt install -y mysql-server
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''";

mysql -e "DROP USER ''@'localhost';"
mysql -e "DROP USER ''@'$(hostname)';"
mysql -e "DROP DATABASE test;"
mysql -e "FLUSH PRIVILEGES;"

mysql -u root -p -e "CREATE USER 'sqldev'@'localhost' IDENTIFIED BY '';"
mysql -u root -p -e "GRANT CREATE, SELECT, INSERT, UPDATE, DELETE, DROP, ALTER, REFERENCES, SHOW DATABASES ON * . * TO 'sqldev'@'localhost';"
mysql -u root -p -e "FLUSH PRIVILEGES;"

mysql -u sqldev -pdev10per123 -e "CREATE SCHEMA mydb DEFAULT CHARACTER SET utf8 ;"


mysql -u sqldev -p <<END

CREATE TABLE IF NOT EXISTS mydb.Products (
  product_id INT NOT NULL,
  product_name VARCHAR(255) NULL,
  product_desc LONGTEXT NULL,
  product_category VARCHAR(255) NULL,
  quantity INT NULL,
  price FLOAT NULL,
  is_active INT NULL,
  created_at DATETIME NULL,
  promo FLOAT NULL,
  PRIMARY KEY (product_id));

CREATE TABLE IF NOT EXISTS mydb.Users (
  email VARCHAR(255) NOT NULL,
  username VARCHAR(255) NULL,
  password VARCHAR(255) NULL,
  priority INT NULL,
  PRIMARY KEY (email),
  UNIQUE INDEX username_UNIQUE (username ASC) VISIBLE);

CREATE TABLE IF NOT EXISTS mydb.Feedback (
  Products_product_id INT NOT NULL,
  Users_email VARCHAR(255) NOT NULL,
  comments LONGTEXT NULL,
  ratings INT NULL DEFAULT NULL,
  PRIMARY KEY (Products_product_id, Users_email),
  CONSTRAINT fk_Products_has_Users_Products
    FOREIGN KEY (Products_product_id)
    REFERENCES mydb.Products (product_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Products_has_Users_Users1
    FOREIGN KEY (Users_email)
    REFERENCES mydb.Users (email)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE IF NOT EXISTS mydb.Order_History (
  order_id INT NOT NULL AUTO_INCREMENT,
  Users_email VARCHAR(255) NOT NULL,
  order_at DATETIME NULL DEFAULT NULL,
  payment_mtd VARCHAR(15) NULL DEFAULT NULL,
  card_num VARCHAR(16) NULL DEFAULT NULL,
  purchased INT NULL DEFAULT NULL,
  PRIMARY KEY (order_id),
  CONSTRAINT fk_Order_History_Users1
    FOREIGN KEY (Users_email)
    REFERENCES mydb.Users (email)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

CREATE TABLE IF NOT EXISTS mydb.Cart_Item (
  Products_product_id INT NOT NULL,
  Order_History_order_id INT NOT NULL,
  quantity INT NULL,
  price FLOAT NULL,
  PRIMARY KEY (Products_product_id, Order_History_order_id),
  CONSTRAINT fk_Products_has_Order_History_Products1
    FOREIGN KEY (Products_product_id)
    REFERENCES mydb.Products (product_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Products_has_Order_History_Order_History1
    FOREIGN KEY (Order_History_order_id)
    REFERENCES mydb.Order_History (order_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

END

mysql -u sqldev -p <<END

INSERT INTO mydb.Users (email,username,password,priority)
VALUES ('admin@gmail.com','administrator','\$2y\$10\$p4rzMZCNJiLZsG8MTilKz.Wd0NRyPP1L7rqvhj.nwqgrhKjGPnPXS',1);

INSERT INTO mydb.Users (email,username,password,priority)
VALUES ('staff@gmail.com','staff','\$2y\$10\$bhHO1WbqWFVSb4IDH4cweOxY.GsN16OBYk9FKbkO2fOt4vQsw/y3K',2);

INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('1', 'Ferrero Rocher', "A unique taste experience of contrasting layers: a whole crunchy hazelnut in the heart, a delicious creamy hazelnut filling, a crisp wafer shell covered with chocolate and gently roasted pieces. And thanks to its inimitable golden wrapper Ferrero Rocher is even more unique and special.", 'Sweets and Snacks', '150', '18.25', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('2', 'Hi-Chew', "HI-CHEW is a uniquely soft candy with a long-lasting, chewy texture. Combine that with smooth, real fruit flavors and – just like that – you've got HI-CHEW. With an assortment of fruity flavors to choose from, we're confident HI-CHEW is like nothing you've ever tasted before!", 'Sweets and Snacks', '250', '2.83', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('3', 'Haribo Gummy Bears', "The original gummi bears and HARIBO's number 1 in the five fruit flavors lemon, orange, pineapple, raspberry and strawberry.", 'Sweets and Snacks', '1250', '5.8', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('4', 'Twisties', "Twisties are a type of cheese curl corn-based snack food product, available mainly in Australia, and other Oceanian countries such as Papua New Guinea, New Caledonia, Vanuatu and Fiji, the Southeast Asian countries Malaysia, Thailand, Singapore and Brunei, and the island of Mauritius in the Indian Ocean.", 'Sweets and Snacks', '300', '2.73', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('5', 'Dasani Bottled Water', "DASANI water is purified and enhanced with a proprietary blend of minerals to give it the clean, fresh taste you want from water. ", 'Drinks and Alcohol', '1000', '0.8', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('6', 'Ribena Packet Drink', "Ribena is made from 100% British blackcurrants and available in a range of luscious flavors and beverage types, including original and light, so consumers of any age can enjoy fantastic fruity flavors, whatever the occasion.", 'Drinks and Alcohol', '1000', '2.10', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('7', 'Heaven and Earth Jasmine Green Tea', "Heaven and Earth Jasmine Green Tea is a soothing fusion of fragrant Jasmine and Green Tea for a smooth, delicious flavour.", 'Drinks and Alcohol', '1000', '1.7', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('8', 'Coca Cola', "Coca Cola, or Coke, is a carbonated soft drink manufactured by the Coca Cola Company. In 2013, Coke products were sold in over 200 countries worldwide, with consumers drinking more than 1.8 billion company beverage servings each day.", 'Drinks and Alcohol', '1000', '1.5', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('9', 'Spinning Mop', "The spin mop is easy to use and has a detachable mop head that allows you to attach it to the mop with ease. Cleaning is easy with the spin mop.", 'Miscellaneous', '1000', '10.7', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('10', 'Lighter', "A cigarette lighter is a device which produces a small flame when you press a switch and which you use to light a cigarette or cigar.", 'Miscellaneous', '1000', '10.7', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('11', 'Yo Yo', "A yo yo (also spelled yoyo) is a toy consisting of an axle connected to two disks, and a string looped around the axle, similar to a spool.", 'Miscellaneous', '1000', '2	', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('12', 'USB C Cable', "This 2 metre charge cable, with USB C connectors on both ends, is ideal for charging, syncing and transferring data between USB C devices.", 'Miscellaneous', '1000', '29.25', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('13', 'Canned Braised Peanuts', "Packed with nutrients like Phosphorus, Magnesium, and Potassium, canned braised peanuts are a perfect choice.", 'Dry and Canned Goods', '1000', '0.85', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('14', 'Hosen Longan', "What is Hosen lychee? Hosen Lychee in Syrup is made from freshly picked lychee fruits from the trees and immediately packed to seal in all the tangy goodness! Hosen Lychee are delicious, tender yet firm in texture for the healthy choice for the entire family. Hosen quality canned fruits.", 'Dry and Canned Goods', '1000', '3', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('15', 'Pork Luncheon Meat', "Running out of ideas for a quick and easy lunch. Not only does Maling Pork Luncheon Meat can satisfy your tummy during meals but it is also made convenient and ready to enjoy. Suitable with salad, egg, fried rice and many more.", 'Dry and Canned Goods', '1000', '2.95', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('16', 'Mushrooms Canned', "Canned mushrooms have the same nutritional content as fresh mushrooms. However, the added sodium from the canning brine, saltwater, is something to consider if you are trying to limit how much sodium you have. ", 'Dry and Canned Goods', '1000', '1.35', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('17', 'Meiji Fresh Milk', "Made from 100 percent fresh milk. Rich in protein, calcium and vitamin B2.", 'Eggs and Diary Products', '1000', '6.45', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('18', 'Oatside Oat Milk', "Oatside comprises beta glucans, which are good for heart health, reduce cholesterol and low in saturated fats.", 'Eggs and Diary Products', '1000', '5.8', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('19', 'SCS Sliced Cheese', "Nothing beats the taste of a slice of quality, healthy cheese. Add these slices to your sandwiches, crackers, soups or just pop it right into your mouth.", 'Eggs and Diary Products', '1000', '7.85', '1', '2023-03-22 16:00:00');
INSERT INTO mydb.Products (product_id, product_name, product_desc, product_category, quantity, price, is_active, created_at) VALUES ('20', 'Yakult', "Yakult is a Japanese sweetened probiotic milk beverage fermented with the bacteria strain Lacticaseibacillus casei Shirota. It is sold by Yakult Honsha, based in Tokyo.", 'Eggs and Diary Products', '1000', '6.8', '1', '2023-03-22 16:00:00');

END

sudo apt install -y php libapache2-mod-php php-mysql
sudo rm /etc/apache2/mods-enabled/dir.conf
sudo echo "<IfModule mod_dir.c>
        DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
</IfModule>" > /etc/apache2/mods-enabled/dir.conf

sudo rm /etc/apache2/sites-enabled/000-default.conf
sudo echo "<VirtualHost *:80>
       ServerAdmin webmaster@localhost
       DocumentRoot /var/www/html

       ErrorDocument 404 /error404.php

       <Files "error404.php">
           <If "-z %{ENV:REDIRECT_STATUS}">
               RedirectMatch 404 ^/error404.php$
           </If>
       </Files>

       ErrorLog ${APACHE_LOG_DIR}/error.log
       CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>" > /etc/apache2/sites-enabled/000-default.conf

sudo service apache2 restart

sudo mv /var/www/html/tmp-index.html /var/www/html/tmp.html

sudo echo "<?php
phpinfo();
?>
" > /var/www/html/info.php

sudo mkdir /var/www/private
sudo echo "[database]
servername = 'localhost'
username = 'sqldev'
password = ''
dbname = 'mydb'" > /var/www/private/db-config.ini

