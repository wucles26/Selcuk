# Selcuk

Kökteki `index.php` front controller’dır. İstekler mini MVC katmanlarına dağılır.

```
index.php                 # yönlendirici / giriş
bootstrap.php             # sabitler, autoload
config/
  app.php                 # uygulama ayarları
  routes.php              # URL → controller
app/
  Core/                   # çekirdek: App, Router, Request, Response, View, Model, Controller
  Controllers/            # HTTP aksiyonları
  Models/                 # veri / domain
  Views/                  # şablonlar (layouts, sayfalar, hatalar)
  helpers.php
public/                   # css, js, görseller
```

Akış: **HTTP → index.php → Router → Controller → Model → View**.

## Railway

`index.php` kökte kaldığı için Railpack PHP’yi tanır. Root Directory boş bırakın. Statik dosyalar `/public/...` üzerinden gelir.

## Yerel

```bash
php -S 127.0.0.1:8080
```

- `/` ana sayfa
- `/about` katman özeti
- bilinmeyen yollar `404`
