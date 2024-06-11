#!/bin/bash

# Запрос домена у пользователя
echo "Введите домен для настройки Nginx:"
read DOMAIN

# Определение текущего каталога
CURRENT_DIR=$(pwd)

# Меню выбора пути
echo "Выберите вариант пути к проекту:"
options=("/var/www/laravel/public" "Текущий каталог (${CURRENT_DIR})" "Указать путь вручную")
select opt in "${options[@]}"
do
    case $REPLY in
        1)
            PATCH="/var/www/laravel/public"
            break
            ;;
        2)
            PATCH=${CURRENT_DIR}
            break
            ;;
        3)
            echo "Введите путь к проекту:"
            read PATCH
            break
            ;;
        *) echo "Неправильный выбор";;
    esac
done

# Создание конфигурации Nginx
cat > /etc/nginx/sites-available/${DOMAIN} <<EOF
server {
    listen 80;
    server_name ${DOMAIN};
    root ${PATCH};

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF


# Активация конфигурации сайта
ln -s /etc/nginx/sites-available/${DOMAIN} /etc/nginx/sites-enabled/

# Проверка конфигурации Nginx и перезапуск сервиса
nginx -t && systemctl restart nginx
