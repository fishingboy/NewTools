## app config
- APP_NAME = Alex

## 套件管理
AdminUI 的套件放在 packages 底下，若之後有共用的可以也丟在那邊，可能需再規畫前端頁面框架是否也要丟在 packages 裡。adminui 的 component 都吃裡面的，包括首頁的 page，其它的頁面用的在外面的 resources

## Migration
### DDL
原生的
```shell
php artisan migrate
```

專案的
```shell
php artisan migrate --path=database/migrations/alex/
```

之後若有更動 schema
於開發階段，都不再用 rollback, 整張 drop 後重跑，所以只做這個指令

```shell
php artisan migrate:refresh --path=database/migrations/alex/
```

### DML
import.sql 直接注入

## UI 
請參考這個套件 https://coreui.io/docs/getting-started/introduction/

目前沒有 include jquery 所以若有要 ajax 要找方法

# NewTools
