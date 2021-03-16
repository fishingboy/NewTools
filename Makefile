BRANCH := $(shell git branch | grep \* | cut -d ' ' -f2)

# 安裝
install:
	@echo ">>> 安裝 alex_project ....."
	@echo ">>> 調整 storage 權限 ....."
	chmod -R 777 storage
	@echo ">>> 複製 .env 檔 ....."
	cp .env.example .env
	@echo ">>> artisan key:generate ....."
	php artisan key:generate
	@echo ">>> composer install ....."
	composer install

# 重建資料
rebuild: refresh fake-data-seeder

composer-install:
	@echo ">>> composer install ....."
	composer install

pull:
	@echo ">>> Pull Code on Current branch [$(BRANCH)]"
	git pull origin $(BRANCH) --rebase

push:
	@echo ">>> Current branch [$(BRANCH)] Pushing Code"
	git push origin $(BRANCH)

refresh:
	@echo ">>> 重建資料庫 ....."
	php artisan migrate:refresh --path=database/migrations/alex/

migrate:
	@echo ">>> 資料庫 migrate ....."
	php artisan migrate --path=database/migrations/alex/

rollback:
	@echo ">>> 資料庫 rollback ....."
	php artisan migrate:rollback --path=database/migrations/alex/

fake-data-seeder:
	@echo ">>> 重灌假資料 ....."
	@echo nothing to do ...

