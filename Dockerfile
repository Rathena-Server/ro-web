FROM php:8.3-apache

# Install required PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mysqli \
    gd \
    zip \
    xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy FluxCP files
COPY . /var/www/html/

# Create required directories and set permissions
RUN mkdir -p /var/www/html/data/logs \
    /var/www/html/data/logs/schemas \
    /var/www/html/data/logs/schemas/logindb \
    /var/www/html/data/logs/schemas/charmapdb \
    /var/www/html/data/itemshop \
    /var/www/html/data/tmp \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/data

# Configure Apache DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html|g' /etc/apache2/sites-available/000-default.conf \
    && echo '<Directory /var/www/html>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    Require all granted' >> /etc/apache2/sites-available/000-default.conf \
    && echo '</Directory>' >> /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
