# Selcuk

Kök dizinde `index.php` bulunan bir PHP sitesi. Railway, `index.php` veya `composer.json` gördüğünde projeyi PHP uygulaması olarak tanır ve FrankenPHP ile yayınlar.

## Railway’e bağlama

Bu depodan Railway hesabınıza servis eklemek için (hesap Railway tarafında sizde olmalı):

1. [railway.com](https://railway.com) içinde **New Project** açın.
2. **Deploy from GitHub repo** seçin ve `wucles26/Selcuk` deposunu bağlayın.
3. Root Directory boş bırakın (dosyalar repo kökünde).
4. Deploy sonrası **Generate Domain** ile herkese açık URL verin.

Railway CLI kullanıyorsanız:

```bash
railway login
railway init
railway up
railway domain
```

Yeni Railway servisleri varsayılan olarak Railpack kullanır. Ek `railway.toml` gerekmez.

## Yerel çalıştırma

PHP 8.2+ ile:

```bash
php -S 127.0.0.1:8080
```

Tarayıcıda `http://127.0.0.1:8080` adresini açın.
