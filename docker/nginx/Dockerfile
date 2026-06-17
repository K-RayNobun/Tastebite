# Multi-stage build for Nginx
FROM alpine:latest AS assets-cleaner
WORKDIR /app
COPY . .
# Keep only static files if needed, but for simplicity, we'll copy everything and let Nginx serve what's relevant.
# In a real project with a build step (like Vite/Webpack), we'd only copy the 'dist' folder.

FROM nginx:alpine
# Copy custom configuration
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy application files (to serve static assets directly from Nginx)
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
