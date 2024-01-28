FROM php:8.3-fpm-alpine

COPY docker/php/install-composer.sh /tmp
RUN chmod +x /tmp/install-composer.sh && sh /tmp/install-composer.sh

COPY ./grpc-client-php /app/grpc-client-php
COPY ./protos /app/protos

RUN apk add --update linux-headers \
   && apk add --no-cache autoconf gcc make g++ zlib-dev git cmake \
   && pecl channel-update pecl.php.net \
   # Install the grpc extension
   && pecl install grpc \
   && docker-php-ext-enable grpc

# Official documentation has two installation options
# We use building from the sources.
RUN cd /app && git clone https://github.com/grpc/grpc \
   && cd /app/grpc && git submodule update --init \
   && mkdir -p cmake/build && cd cmake/build && cmake ../.. && make protoc grpc_php_plugin

RUN cd /app/grpc-client-php \
   #Install required packages
   && composer install \
   # install protobuf compiler
   && apk add protobuf \
   # Compile userService.proto file
   && cd /app && protoc --proto_path=protos \
   --php_out=./grpc-client-php/src \
   --grpc_out=./grpc-client-php/src \
   --plugin=protoc-gen-grpc=./grpc/cmake/build/grpc_php_plugin \
   ./protos/userService.proto

WORKDIR /app

CMD ["php-fpm"]