version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: visify-app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - ./src:/var/www/html
      - ./php.ini:/usr/local/etc/php/conf.d/custom.ini
    networks:
      - visify-network
    depends_on:
      - db
      - redis

  web:
    image: nginx:alpine
    container_name: visify-nginx
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    networks:
      - visify-network
    depends_on:
      - app

  db:
    image: mysql:8.0
    container_name: visify-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: visify_db
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_PASSWORD: dbpassword
      MYSQL_USER: visify_user
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
    networks:
      - visify-network

  redis:
    image: redis:alpine
    container_name: visify-redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    networks:
      - visify-network

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: visify-phpmyadmin
    restart: unless-stopped
    ports:
      - "8081:80"
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
      PMA_ARBITRARY: 1
    networks:
      - visify-network
    depends_on:
      - db

networks:
  visify-network:
    driver: bridge

volumes:
  db_data: