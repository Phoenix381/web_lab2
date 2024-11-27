FROM php:8.1

RUN apt-get update

#postgres
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN apt-get install -y libicu-dev
RUN docker-php-ext-install intl

RUN apt-get install curl

# composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN apt-get install -y git

# NVM
RUN mkdir /root/.nvm
ENV NVM_DIR /root/.nvm
# /usr/local/nvm or ~/.nvm , depending
ENV NODE_VERSION 18.12.1

# Install nvm with node and npm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

ENV NODE_PATH $NVM_DIR/versions/node/v$NODE_VERSION/lib/node_modules 
ENV PATH      $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

RUN apt-get install -y zsh
RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended

RUN git clone --depth 1 -- https://github.com/marlonrichert/zsh-autocomplete.git ~/.zsh/zsh-autocomplete
RUN echo "source ~/.zsh/zsh-autocomplete/zsh-autocomplete.plugin.zsh" >> ~/.zshrc
RUN sed -i 's/robbyrussell/lukerandall/g' ~/.zshrc

ENV LC_CTYPE C.UTF-8


WORKDIR /var/www/html