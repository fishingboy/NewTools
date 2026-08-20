# NewTools

內部使用的小工具集合（Laravel 8 + PHP 8.0）。

目前包含：

- **Text Editor**（`/`）— 線上文字轉換工具，支援 JSON pretty、JSON decode、urlencode / urldecode、base64 encode / decode
- **AdminUI**（`/adminui`）— `packages/wcstudio/adminui` 提供的後台介面（選單管理、群組權限）
- **i18n CLI** — `artisan i18n:deploy` / `artisan i18n:remove`，處理 Magento 多語系 CSV

---

## 環境需求

| 工具 | 說明 |
| --- | --- |
| [Docker](https://www.docker.com/) | 含 Docker Compose v2（`docker compose`，不是 `docker-compose`） |
| [Task](https://taskfile.dev/) | 跨平台的指令入口，取代原本的 Makefile（Windows 也能用） |

安裝 Task：

```bash
# macOS
brew install go-task/tap/go-task

# Windows
winget install Task.Task      # 或 scoop install task

# Linux
sh -c "$(curl -ssL https://taskfile.dev/install.sh)" -- -d -b /usr/local/bin
```

本機**不需要**安裝 PHP、Composer、MySQL — 全部都跑在容器裡。

---

## 快速開始

```bash
task install
```

這一行會做完所有事：建立 `.env` → 建 image → 啟動容器 → `composer install` → 產生 `APP_KEY` → 跑 migration。

跑完之後：

| 服務 | 位址 | 帳密 |
| --- | --- | --- |
| App | <http://localhost:7777> | — |
| Mailpit（收信介面） | <http://localhost:8025> | — |
| MySQL | `127.0.0.1:13306` | `newtools` / `secret` |
| Redis | `127.0.0.1:16379` | — |

Port 可以在 `.env` 裡改 `APP_PORT`、`FORWARD_DB_PORT`、`FORWARD_REDIS_PORT`、`FORWARD_MAILPIT_PORT`，改完 `task restart`。

---

## 常用指令

`task` 或 `task --list` 可以列出全部指令。

### 生命週期

| 指令 | 說明 |
| --- | --- |
| `task install` | 第一次建置（只需要跑一次） |
| `task start` | 啟動所有容器 |
| `task stop` | 停止並移除容器（資料庫資料保留） |
| `task restart` | 重啟容器，不重建 image |
| `task rebuild` | `--no-cache` 重建 image 後重啟，並重跑 `composer install`。改過 `docker/php/Dockerfile` 時用這個 |
| `task reset` | ⚠️ 連 MySQL / Redis 的 volume 一起砍掉後完整重建，**資料庫資料會全部消失**（會先問你一次） |

### 觀察

| 指令 | 說明 |
| --- | --- |
| `task ps` | 容器狀態 |
| `task logs` | 追蹤全部 log；`task logs -- mysql` 只看單一服務 |
| `task info` | 印出各服務的存取位址 |

### 進容器

| 指令 | 說明 |
| --- | --- |
| `task shell` | 進 app 容器（`www-data`，日常用這個） |
| `task root-shell` | 進 app 容器（`root`，要裝東西時用） |
| `task mysql` | 開 MySQL client |

### 開發

| 指令 | 說明 |
| --- | --- |
| `task artisan -- route:list` | 執行任意 artisan 指令 |
| `task composer -- require foo/bar` | 執行任意 composer 指令 |
| `task composer-install` | `composer install` |
| `task tinker` | 開 tinker |
| `task test` | 跑 PHPUnit |
| `task cache-clear` | `artisan optimize:clear` |
| `task permissions` | 修 `storage` / `bootstrap/cache` 權限 |

> `--` 後面的參數會原封不動傳給容器裡的指令。

### 資料庫

| 指令 | 說明 |
| --- | --- |
| `task migrate` | 跑全部 migration |
| `task migrate:fresh` | 清空資料庫後重跑全部 migration |
| `task migrate:rollback` | 回退上一批 migration |
| `task db:refresh` | 只重建 `database/migrations/alex/` 底下的資料表 |
| `task db:seed` | 重灌假資料（目前是空的） |
| `task db:rebuild` | `db:refresh` + `db:seed` |

> ⚠️ `php artisan migrate` **不會**遞迴讀 `database/migrations/alex/` 子目錄，必須另外用 `--path` 指定。
> `task migrate` 已經把兩段都包好了，直接用它就不會漏掉 alex 的資料表。

### Git

| 指令 | 說明 |
| --- | --- |
| `task git:pull` | 對目前分支做 `git pull --rebase` |
| `task git:push` | 推送目前分支 |

---

## 環境架構

```
docker-compose.yml
├── app      php:8.0-apache + Composer  → localhost:7777
├── mysql    mysql:8.0                  → localhost:13306
├── redis    redis:alpine               → localhost:16379
└── mailpit  攔截所有寄出的信            → localhost:8025
```

- 專案目錄以 volume 掛進 `app` 容器的 `/var/www/html`，**改 code 不用重建 image**，存檔即生效。
- Apache 的 DocumentRoot 指到 `public/`，設定在 `docker/php/vhost.conf`。
- PHP 設定（memory_limit、時區、display_errors）在 `docker/php/php.ini`。
- 已安裝的 extension：`bcmath` `exif` `gd` `mbstring` `pcntl` `pdo_mysql` `zip` `redis`。

### 檔案

| 檔案 | 用途 |
| --- | --- |
| `Taskfile.yml` | 所有指令的入口 |
| `docker-compose.yml` | 服務定義 |
| `docker/php/Dockerfile` | app 容器的 image |
| `docker/php/vhost.conf` | Apache virtual host |
| `docker/php/php.ini` | PHP 設定覆寫 |
| `.env.docker` | Docker 環境的 `.env` 範本（`DB_HOST=mysql` 等） |
| `.env.example` | 本機直接跑 PHP 用的 `.env` 範本（`DB_HOST=127.0.0.1`） |

需要覆寫 compose 設定時，可以自己開 `docker-compose.override.yml`（已在 `.gitignore` 內）。

---

## 疑難排解

**Port 7777 被佔用**
改 `.env` 的 `APP_PORT`，然後 `task restart`。

**瀏覽器說「此網址已被限制」/ `ERR_UNSAFE_PORT`**
你把 `APP_PORT` 改到瀏覽器的封鎖清單上了。Firefox 與 Chromium 都會擋掉
一批「通常不用於網頁瀏覽」的 port，常見的有 6665–6669、6697（IRC）、
6000（X11）、25（SMTP）、587、465 等。換一個沒被擋的 port 即可，
例如 7777、8666、16666。注意 1024 以下是特權 port，也盡量避開。

**改了 `docker/php/` 底下的檔案沒生效**
那些是 build 進 image 的，要 `task rebuild`。

**`storage` 權限錯誤 / 寫不進 log**
`task permissions`。

**資料庫想從乾淨狀態重來**
`task reset`（會刪光資料）。

**測試有失敗**
`tests/Feature/I18nTest.php` 需要 `storage/app/i18n/*.csv` 這些 fixture 檔（repo 內沒有），
`tests/Feature/SpiderTest.php` 會去連內網的 `local-software.qnap.com`。
這兩組在一般開發機上本來就會失敗，與 Docker 環境無關。
