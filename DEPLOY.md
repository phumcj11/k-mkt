# DEPLOY.md — K-MKT.COM Deployment Guide

## ภาพรวม Workflow

```
Cursor IDE → Git Commit → GitHub Push → VPS Git Pull → Website Live
```

---

## 1. GitHub Setup (ครั้งแรก)

### 1.1 Initialize Git ในเครื่อง
```bash
cd c:/xampp/htdocs/k-mkt
git init
git add .
git commit -m "Initial commit: K-MKT website with GSAP and SEO"
```

### 1.2 เชื่อมต่อ GitHub Repository
```bash
git remote add origin https://github.com/phumcj11/k-mkt.git
git branch -M main
git push -u origin main
```

### 1.3 ถ้าใช้ SSH Key
```bash
git remote add origin git@github.com:phumcj11/k-mkt.git
```

---

## 2. Push Updates (ทุกครั้งที่แก้ไข)

```bash
cd c:/xampp/htdocs/k-mkt
git add .
git commit -m "Update: [ระบุว่าแก้ไขอะไร]"
git push origin main
```

ตัวอย่าง Commit Messages:
```bash
git commit -m "feat: เพิ่ม FAQ section ใน SEO page"
git commit -m "fix: แก้ Mobile Menu animation"
git commit -m "content: เพิ่มบทความใหม่ 3 บทความ"
git commit -m "seo: ปรับ Meta Tags ทุกหน้า"
```

---

## 3. Deploy บน VPS DirectAdmin

### 3.1 SSH เข้า Server

```bash
ssh root@119.59.102.235
```

ถ้าใช้ SSH Key:
```bash
ssh -i ~/.ssh/id_rsa root@119.59.102.235
```

---

### 3.2 โฟลเดอร์ Website บน DirectAdmin

```bash
# โฟลเดอร์หลัก
/home/pcj/domains/k-mkt.com/public_html
```

ตรวจสอบ:
```bash
ls /home/pcj/domains/k-mkt.com/public_html
```

---

### 3.3 Clone ครั้งแรก (First Time Deploy)

```bash
# ไปที่โฟลเดอร์ domain
cd /home/pcj/domains/k-mkt.com

# Backup public_html เดิม (ถ้ามี)
mv public_html public_html_backup_$(date +%Y%m%d_%H%M%S)

# สร้าง public_html ใหม่
mkdir public_html

# Clone จาก GitHub
cd public_html
git clone https://github.com/phumcj11/k-mkt.git .
```

---

### 3.4 Pull อัปเดตครั้งต่อไป (Every Update)

```bash
# SSH เข้า Server
ssh root@119.59.102.235

# ไปที่ public_html
cd /home/pcj/domains/k-mkt.com/public_html

# ตรวจสอบสถานะ
git status

# Pull อัปเดตใหม่
git pull origin main
```

---

### 3.5 ตั้งค่า File Permission

```bash
# Permission ถูกต้องสำหรับ DirectAdmin
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

---

## 4. Backup ก่อน Deploy

```bash
# Backup โฟลเดอร์ก่อน Pull
cp -r /home/pcj/domains/k-mkt.com/public_html \
      /home/pcj/domains/k-mkt.com/public_html_backup_$(date +%Y%m%d_%H%M%S)

# หรือ Backup เฉพาะไฟล์สำคัญ
tar -czf backup_$(date +%Y%m%d).tar.gz /home/pcj/domains/k-mkt.com/public_html
```

---

## 5. Production Update Flow (Full)

```bash
# Step 1: SSH
ssh root@119.59.102.235

# Step 2: Backup
cd /home/pcj/domains/k-mkt.com
tar -czf backup_$(date +%Y%m%d_%H%M%S).tar.gz public_html

# Step 3: Pull
cd public_html
git status
git pull origin main

# Step 4: Permission
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

# Step 5: ตรวจสอบ
echo "Deploy สำเร็จ!"
ls -la
```

---

## 6. Rollback (ย้อนกลับ)

### ย้อนกลับ 1 Commit
```bash
git revert HEAD
git push origin main
# แล้วค่อย git pull บน server
```

### ย้อนกลับจาก Backup
```bash
cd /home/pcj/domains/k-mkt.com
rm -rf public_html
cp -r public_html_backup_YYYYMMDD_HHMMSS public_html
```

### ย้อนกลับไปยัง Commit เฉพาะ
```bash
git log --oneline          # ดู Commit list
git checkout abc1234 -- .  # ย้อนไปยัง commit นั้น
```

---

## 7. DNS Setup (ตั้งค่า DNS ชี้ไป VPS)

ใน Domain Registrar ของคุณ ตั้งค่า:

| Type | Name | Value | TTL |
|------|------|-------|-----|
| A | @ | 119.59.102.235 | 3600 |
| A | www | 119.59.102.235 | 3600 |

> IP ของ VPS คือ **119.59.102.235**

---

## 8. SSL Certificate (HTTPS)

บน DirectAdmin:
1. Login DirectAdmin
2. ไปที่ **SSL Certificates**
3. เลือก **Free & automatic certificate from Let's Encrypt**
4. เลือก Domain k-mkt.com และ www.k-mkt.com
5. กด **Save**

รอ 2-5 นาที SSL จะ Active

---

## 9. DirectAdmin Notes

**Server:** root@119.59.102.235

```
SSH User:      root
Server IP:     119.59.102.235
Web User:      pcj
โฟลเดอร์หลัก: /home/pcj/domains/k-mkt.com/public_html
GitHub Repo:   https://github.com/phumcj11/k-mkt.git
```

ถ้า Domain ยังไม่ชี้ DNS:
- A Record `@` ชี้ไป `119.59.102.235`
- A Record `www` ชี้ไป `119.59.102.235`
- รอ DNS Propagation 15 นาที - 48 ชั่วโมง

---

## 10. Auto Deploy (Optional: GitHub Actions)

สร้างไฟล์ `.github/workflows/deploy.yml`:

```yaml
name: Deploy to VPS

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy via SSH
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.SERVER_HOST }}      # 119.59.102.235
          username: ${{ secrets.SERVER_USER }}  # root
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /home/pcj/domains/k-mkt.com/public_html
            git pull origin main
            find . -type d -exec chmod 755 {} \;
            find . -type f -exec chmod 644 {} \;
```

ตั้งค่า GitHub Secrets:
- `SERVER_HOST`: IP ของ VPS
- `SERVER_USER`: username บน server
- `SSH_PRIVATE_KEY`: Private Key ของ SSH

---

## 11. Quick Reference Commands

```bash
# Push จาก Local
git add . && git commit -m "update" && git push

# Pull บน Server
ssh user@ip "cd /path/to/public_html && git pull"

# ดู Git Log
git log --oneline -10

# ตรวจสอบ Remote
git remote -v

# ดู Branch ปัจจุบัน
git branch
```

---

## 11. ระบบหลังบ้าน PHP + MySQL

### 11.1 Local (XAMPP)

1. สร้าง Database ใน phpMyAdmin ชื่อ `k_mkt`
2. Import ไฟล์:
   - `database/schema.sql`
   - `database/seed.sql`
3. คัดลอก config:
   ```bash
   copy config.local.php.example config.local.php
   ```
4. แก้ `config.local.php` ให้ตรงกับ MySQL local
5. เข้า Admin: `http://localhost/k-mkt/admin/`
   - Username: `admin`
   - Password: `admin123` (เปลี่ยนทันทีหลัง login)

### 11.2 VPS (DirectAdmin) — Deploy อัตโนมัติ

**Database บน Server:** `pcj_kmkt` (user: `pcj_kmkt`)

#### ครั้งแรก (setup 1 นาที)

1. สร้าง MySQL Database `pcj_kmkt` + User `pcj_kmkt` ใน DirectAdmin
2. รัน `scripts\init-deploy-secrets.bat` — ใส่รหัสผ่าน MySQL
3. กด `deploy.bat` — จะ push code + สร้าง config + import DB + seed อัตโนมัติ

#### ครั้งถัดไป

กด **`deploy.bat`** อย่างเดียว — commit, push, deploy ครบ

`deploy.bat` จะทำให้อัตโนมัติ:
- Push ขึ้น GitHub
- ส่ง `deploy.secrets` → server `private/db.env`
- `git pull` + สร้าง `config.local.php`
- รัน `schema.sql` + `seed.sql` (ถ้ายังไม่มี admin)
- ตั้ง permission `uploads/`

#### Manual config (ถ้าไม่ใช้ deploy.bat)

3. สร้าง `config.local.php` บน server (ไม่ push ขึ้น Git) — หรือคัดลอกจาก `config.local.php.server.example`:
   ```php
   return [
       'db_host' => 'localhost',
       'db_name' => 'pcj_kmkt',
       'db_user' => 'pcj_kmkt',
       'db_pass' => 'YOUR_PASSWORD',
       'site_url' => 'https://k-mkt.com',
       'upload_dir' => '/home/pcj/domains/k-mkt.com/public_html/uploads',
       'upload_url' => '/uploads',
   ];
   ```
4. ตั้ง permission:
   ```bash
   chmod 755 uploads/
   chmod 644 config.local.php
   ```

### 11.3 หน้าที่จัดการได้จาก Admin

| URL | ฟีเจอร์ |
|-----|---------|
| `/admin/` | Login |
| `/admin/blog/` | จัดการบทความ |
| `/admin/cases/` | จัดการผลงาน Case Study |
| `/admin/settings.php` | เบอร์โทร, LINE, Email |
| `/admin/submissions.php` | ฟอร์มจากลูกค้า |

หน้าบ้าน dynamic: `blog.php`, `blog-post.php`, `case-study.php`

---

## Contact & Support

หากมีปัญหาในการ Deploy:  
**LINE:** @k-mkt  
**Email:** hello@k-mkt.com
