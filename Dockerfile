FROM php:8.3-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Configure Apache DocumentRoot to AllowOverride All for .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Setup working directory and data storage
WORKDIR /var/www/html
RUN mkdir -p /var/www/data && chown -R www-data:www-data /var/www/data /var/www/html

# Copy application source
COPY --chown=www-data:www-data src/ /var/www/html/

# Health check
HEALTHCHECK --interval=30s --timeout=3s CMD curl -f http://localhost:${PORT:-80}/ || exit 1

EXPOSE 80

# Configure dynamic port binding for Vercel Fluid container runtime
CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT:-80}/\" /etc/apache2/ports.conf && sed -i \"s/:80/:*${PORT:-80}/\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]
