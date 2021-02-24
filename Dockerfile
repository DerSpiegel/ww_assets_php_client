FROM php:7.4-cli

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
