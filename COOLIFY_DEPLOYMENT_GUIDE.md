# KATANA - Coolify Deployment Guide

## Deployment menggunakan Coolify

Coolify adalah platform self-hosted untuk deployment yang mudah digunakan. Berikut adalah panduan untuk deploy aplikasi KATANA menggunakan Coolify.

### 1. Persiapan Server dan Coolify

#### a. Install Coolify di VPS
```bash
# Login ke VPS
ssh user@your-vps-ip

# Install Coolify (one-liner)
curl -fsSL https://cdn.coollabs.io/coolify/install.sh | bash
```

#### b. Akses Coolify Dashboard
- Buka browser dan akses: `http://your-vps-ip:8000`
- Setup admin account pertama kali
- Login ke dashboard Coolify

### 2. Setup Project di Coolify

#### a. Buat Project Baru
1. Klik **"+ New"** di dashboard
2. Pilih **"Project"**
3. Beri nama project: `katana`
4. Klik **"Create"**

#### b. Tambah Environment
1. Di project `katana`, klik **"+ New Environment"**
2. Nama environment: `production`
3. Klik **"Create"**

### 3. Deploy Database (MySQL)

#### a. Tambah Database Service
1. Di environment `production`, klik **"+ New Resource"**
2. Pilih **"Database"** → **"MySQL"**
3. Konfigurasi:
   - **Name**: `katana-db`
   - **MySQL Root Password**: `secure_root_password_change_this`
   - **MySQL Database**: `katana_db`
   - **MySQL User**: `katana_user`
   - **MySQL Password**: `secure_password_change_this`
4. Klik **"Deploy"**

### 4. Deploy Aplikasi Laravel

#### a. Tambah Application Service
1. Klik **"+ New Resource"**
2. Pilih **"Application"**
3. Pilih **"Docker Compose"**

#### b. Konfigurasi Git Repository
```
Repository URL: https://github.com/username/katana.git
Branch: main
Build Pack: Docker Compose
```

#### c. Docker Compose Configuration
Gunakan file `docker-compose.coolify.yml` yang sudah dibuat:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile.coolify
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_KEY=${APP_KEY}
      - DB_CONNECTION=mysql
      - DB_HOST=katana-db
      - DB_PORT=3306
      - DB_DATABASE=${DB_DATABASE}
      - DB_USERNAME=${DB_USERNAME}
      - DB_PASSWORD=${DB_PASSWORD}
      - CACHE_DRIVER=file
      - SESSION_DRIVER=file
      - QUEUE_CONNECTION=sync
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.katana.rule=Host(\`${DOMAIN}\`)"
      - "traefik.http.routers.katana.entrypoints=websecure"
      - "traefik.http.routers.katana.tls.certresolver=letsencrypt"
```

### 5. Environment Variables

Tambahkan environment variables di Coolify dashboard:

```env
APP_NAME=KATANA
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=katana-db
DB_PORT=3306
DB_DATABASE=katana_db
DB_USERNAME=katana_user
DB_PASSWORD=secure_password_change_this

CACHE_STORE=file
CACHE_DRIVER=file

SESSION_DRIVER=file
QUEUE_CONNECTION=sync

DOMAIN=your-domain.com
```

### 6. Domain Configuration

#### a. Setup Custom Domain
1. Di aplikasi, klik tab **"Domains"**
2. Tambah domain: `your-domain.com`
3. Enable **"Generate Let's Encrypt Certificate"**
4. Klik **"Save"**

#### b. DNS Configuration
Arahkan DNS domain Anda ke IP VPS:
```
A record: your-domain.com → your-vps-ip
CNAME: www.your-domain.com → your-domain.com
```

### 7. Deployment Process

#### a. Initial Deployment
1. Klik **"Deploy"** di dashboard
2. Coolify akan:
   - Clone repository
   - Build Docker image
   - Start containers
   - Setup SSL certificate
   - Configure reverse proxy

#### b. Monitor Deployment
- Lihat logs real-time di tab **"Logs"**
- Check status di tab **"Resources"**

### 8. Post-Deployment Setup

#### a. Generate Application Key
Jika APP_KEY belum di-generate:
1. Masuk ke container: `Execute Command`
2. Run: `php artisan key:generate --force`

#### b. Run Database Migrations
```bash
php artisan migrate --force
```

#### c. Seed Database (opsional)
```bash
php artisan db:seed --force
```

### 9. File Structure untuk Coolify

Pastikan file-file ini ada di repository:

```
katana/
├── Dockerfile.coolify           # Docker image untuk Coolify
├── docker-compose.coolify.yml   # Docker Compose untuk Coolify
├── .env.coolify                # Environment template
└── docker/
    ├── apache/
    │   └── vhost.conf
    ├── scripts/
    │   └── start.sh            # Startup script
    └── mysql/
        └── my.cnf
```

### 10. Monitoring dan Maintenance

#### a. Melihat Logs
- **Application Logs**: Tab "Logs" di dashboard
- **Build Logs**: Tab "Deployments" 
- **Resource Logs**: Klik service individual

#### b. Backup Database
Coolify menyediakan backup otomatis:
1. Klik database service
2. Tab **"Backups"**
3. Enable **"Automatic Backups"**
4. Set schedule (daily/weekly)

#### c. Update Aplikasi
1. Push changes ke Git repository
2. Di Coolify dashboard, klik **"Deploy"**
3. Coolify akan auto-deploy perubahan

### 11. Auto Deployment (Webhook)

#### a. Setup Webhook di GitHub
1. Di repository GitHub, masuk **Settings** → **Webhooks**
2. Add webhook dengan URL dari Coolify
3. Select events: `push` dan `pull_request`

#### b. Enable Auto Deploy
1. Di Coolify app settings
2. Enable **"Auto Deploy"**
3. Set branch: `main`

### 12. Scaling dan Performance

#### a. Resource Allocation
- CPU: 1-2 cores
- Memory: 2-4 GB RAM
- Storage: 20+ GB SSD

#### b. Container Scaling
Coolify mendukung horizontal scaling:
1. Tab **"Resources"**
2. Adjust **"Replicas"** sesuai kebutuhan

### 13. Security Best Practices

- [ ] Enable Coolify 2FA
- [ ] Setup strong passwords
- [ ] Enable firewall (UFW)
- [ ] Regular security updates
- [ ] Monitor access logs
- [ ] Setup fail2ban

### 14. Troubleshooting

#### a. Container Won't Start
```bash
# Check logs di Coolify dashboard
# Atau via SSH:
docker logs coolify-katana-app
```

#### b. Database Connection Issues
```bash
# Test connection
docker exec -it coolify-katana-db mysql -u katana_user -p
```

#### c. SSL Certificate Issues
1. Check domain DNS pointing
2. Regenerate certificate di Coolify
3. Check Traefik logs

### 15. Coolify vs Manual Docker

**Keuntungan Coolify:**
✅ GUI yang user-friendly  
✅ Auto SSL dengan Let's Encrypt  
✅ Built-in monitoring  
✅ Easy backup management  
✅ Auto deployment dari Git  
✅ Resource scaling  
✅ Built-in reverse proxy (Traefik)  

**Cocok untuk:**
- Tim yang ingin deployment mudah
- Tidak ingin manage infra manual
- Butuh monitoring built-in
- Multiple applications

---

## Quick Deploy Commands

Jika Anda sudah familiar dengan Coolify:

```bash
# 1. Push ke repository
git add .
git commit -m "Add Coolify configuration"
git push origin main

# 2. Di Coolify Dashboard:
# - Create new application
# - Connect Git repository
# - Use docker-compose.coolify.yml
# - Set environment variables
# - Deploy!
```

**Catatan:** Coolify sangat mempermudah deployment. Anda tinggal setup sekali dan deployment selanjutnya otomatis melalui Git push!
