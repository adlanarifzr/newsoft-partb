## Interview Question (Part B)
##### Newsoft IT Solution Sdn. Bhd.

By: Adlan Arif Zakaria
Position Applied: Senior Backend PHP Developer

### 1. Installation
```
git clone https://github.com/adlanarifzr/newsoft-partb.git
cd newsoft-partb
composer install
```

### 2. Environment Setup
```
cp .env.example .env
php artisan key:generate
```

### 3. Data Initialization
Make sure to change the database credentials inside ```.env``` file before proceed.
```
composer dump-autoload
php artisan migrate --seed
php artisan passport:install
```

### 4. Run Application
```
php artisan serve
```

### 5. Test Account
```
# Admin
Email: admin@domain.com
Password: password

# User
Email: user@domain.com
Password: password
```

### 6. API Documentation / Sample
https://documenter.getpostman.com/view/5546845/SVYotzBw