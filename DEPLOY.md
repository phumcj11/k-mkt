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
git remote add origin https://github.com/YOUR_USERNAME/k-mkt-website.git
git branch -M main
git push -u origin main
```

> 💡 แทนที่ `YOUR_USERNAME` ด้วย GitHub Username จริงของคุณ

### 1.3 ถ้าใช้ SSH Key
```bash
git remote add origin git@github.com:YOUR_USERNAME/k-mkt-website.git
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
ssh username@your-server-ip
```

ตัวอย่าง:
```bash
ssh admin@103.xxx.xxx.xxx
```

ถ้าใช้ SSH Key:
```bash
ssh -i ~/.ssh/id_rsa username@your-server-ip
```

---

### 3.2 โฟลเดอร์ Website บน DirectAdmin

```bash
# โฟลเดอร์หลัก (ปรับ username ให้ตรง)
/home/USERNAME/domains/k-mkt.com/public_html
```

ตรวจสอบ:
```bash
ls /home/USERNAME/domains/k-mkt.com/public_html
```

---

### 3.3 Clone ครั้งแรก (First Time Deploy)

```bash
# ไปที่โฟลเดอร์ domain
cd /home/USERNAME/domains/k-mkt.com

# Backup public_html เดิม (ถ้ามี)
mv public_html public_html_backup_$(date +%Y%m%d_%H%M%S)

# สร้าง public_html ใหม่
mkdir public_html

# Clone จาก GitHub
cd public_html
git clone https://github.com/YOUR_USERNAME/k-mkt-website.git .
```

---

### 3.4 Pull อัปเดตครั้งต่อไป (Every Update)

```bash
# SSH เข้า Server
ssh username@server-ip

# ไปที่ public_html
cd /home/USERNAME/domains/k-mkt.com/public_html

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
cp -r /home/USERNAME/domains/k-mkt.com/public_html \
      /home/USERNAME/domains/k-mkt.com/public_html_backup_$(date +%Y%m%d_%H%M%S)

# หรือ Backup เฉพาะไฟล์สำคัญ
tar -czf backup_$(date +%Y%m%d).tar.gz /home/USERNAME/domains/k-mkt.com/public_html
```

---

## 5. Production Update Flow (Full)

```bash
# Step 1: SSH
ssh username@server-ip

# Step 2: Backup
cd /home/USERNAME/domains/k-mkt.com
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
cd /home/USERNAME/domains/k-mkt.com
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
| A | @ | YOUR_VPS_IP | 3600 |
| A | www | YOUR_VPS_IP | 3600 |
| CNAME | www | k-mkt.com | 3600 |

> ตรวจสอบ IP ของ VPS ใน DirectAdmin → Server → Show Server Information

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

```
โฟลเดอร์หลัก: /home/USERNAME/domains/k-mkt.com/public_html
Log Files:     /home/USERNAME/logs/
Error Log:     /home/USERNAME/logs/error.log
```

ถ้า Domain ยังไม่ชี้ DNS:
- A Record ชี้ไปที่ IP VPS
- www เป็น CNAME ไปที่ k-mkt.com
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
          host: ${{ secrets.SERVER_HOST }}
          username: ${{ secrets.SERVER_USER }}
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /home/${{ secrets.SERVER_USER }}/domains/k-mkt.com/public_html
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

## Contact & Support

หากมีปัญหาในการ Deploy:  
**LINE:** @k-mkt  
**Email:** hello@k-mkt.com
