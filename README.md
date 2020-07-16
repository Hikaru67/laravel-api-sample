## Setup

 1. Tạo mới file **.env**, copy nội dung **.env.example** sang và thay đổi giá trị ``DB_DATABASE`` thành database của mình
 2. Chạy ``composer install`` để cài đặt các package **composer**
 3. Chạy ``php artisan key:generate`` để sinh key cho ứng dụng
 4. Chạy ``php artisan migrate`` để tạo các bảng trong database
 5. Chạy ``php artisan db:seed`` để sinh dữ liệu cho database
 6. Chạy ``php artisan passport:install --force`` để tạo key cho **passport**
 7. Chạy ``php artisan l5-swagger:generate`` để tạo api document

## Update
 1. Checkout branch ``develop`` và pull code mới nhất về
 2. Chạy ``composer dump-autoload`` để autoload các class mới
 3. Chạy ``php artisan migrate:refresh --seed`` để refresh lại database
 4. Chạy ``php artisan passport:install --force`` để tạo key cho **passport**
 5. Chạy ``php artisan l5-swagger:generate`` để tạo api document

## Run

Chạy ``php artisan serve`` để khởi động serve
Truy cập ``/api/documentation`` để sử dụng api document

## Debug

  1. Thông báo lỗi: ``Personal access client not found. Please create one.``
  > Giải pháp: Chạy ``php artisan passport:install --force``

  2. Thông báo lỗi: ``Unable to flush cache.`` (Laravel Permission)
  > Giải pháp: Chạy ``sudo php artisan cache:forget spatie.permission.cache && sudo php artisan cache:clear``