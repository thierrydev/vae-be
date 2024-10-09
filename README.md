# Documentation

## Dependencies to use this repo

-   [Docker](https://www.docker.com/)
-   [Make](https://www.gnu.org/software/make/) (OPTIONAL)

## Usage

### Prepare the development environment

```bash
make for-linux-env
make install
```

If you do not have Make

```bash
cp .env.example .env && echo "UID=$$(id -u)" >> .env && echo "GID=$$(id -g)" >> .env
```

```bash
docker compose build \
&& docker compose up --detach \
&& docker compose exec app composer install \
&& docker compose exec app php artisan key:generate \
&& docker compose exec app php artisan storage:link \
&& docker compose exec app chmod -R 777 storage bootstrap/cache \
&& docker compose exec app php artisan serve \
&& docker compose exec app php artisan migrate:fresh --seed
```

### OpenApi documentation

After running the commands above the documentation will be available via http://localhost/docs/api#/
You can use this interface to download an Open API 3.1.0 JSON file via the export button

### API Enpoinds

The API will be accessible via `http://localhost/api/v*/`

### Make documentation

Run make help to get a description of all the commands available

```bash
make help
```
