# Vercel Deployment Guide

## Masalah: Function Size Exceeds Limit

Error:
```
The Vercel Function "api/index" is 259.7mb uncompressed which exceeds
the maximum uncompressed size limit of 250mb.
```

---

## Solusi 1: Optimasi .vercelignore (DIREKOMENDASIKAN)

File `.vercelignore` telah di-update untuk mengecualikan file yang tidak diperlukan:

### File/Folder yang Diexclude:

```
# Mobile app binaries (23MB + 23MB = 46MB savings)
apk/
ipa/

# Laravel dependencies (90MB savings)
vendor/

# Storage & cache (4MB savings)
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*

# Development files
node_modules/
tests/
.vscode/
.idea/

# Documentation (kecuali README)
*.md
!README.md

# Composer & NPM lock files
composer.lock
composer.json
package-lock.json
package.json
```

### Perkiraan Pengurangan Size:

| Item | Size | Status |
|------|------|--------|
| vendor | 90MB | ❌ Excluded |
| apk | 23MB | ❌ Excluded |
| ipa | 23MB | ❌ Excluded |
| storage | 4MB | ❌ Excluded |
| **Total Savings** | **~140MB** | |

**Expected new size:** 259.7MB - 140MB = **~120MB** ✅ (under 250MB limit)

---

## Solusi 2: Enable Large Functions Beta (Alternatif)

Jika optimasi di atas tidak cukup, Anda bisa enable large functions beta:

### Step 1: Set Environment Variable di Vercel

```bash
# Using Vercel CLI
vercel env add VERCEL_SUPPORT_LARGE_FUNCTIONS production
# Input value: 1

# Or via Vercel Dashboard:
# 1. Go to Project Settings → Environment Variables
# 2. Add: VERCEL_SUPPORT_LARGE_FUNCTIONS = 1
# 3. Select: Production
```

### Step 2: Redeploy

```bash
git add .vercelignore
git commit -m 'feat: optimize deployment size'
git push origin main
```

**Catatan:** Large functions beta mungkin memiliki:
- ⚠️ Cold start time yang lebih lama
- ⚠️ Batasan lain yang perlu diperiksa di dokumentasi Vercel

---

## Solusi 3: Reduce Assets Size (Opsional)

Jika masih terlalu besar, pertimbangkan untuk:

### 1. Compress Assets

```bash
# Compress images di public/assets
optipng -o7 public/assets/images/*.png
jpegoptim --max=85 public/assets/images/*.jpg

# Minify CSS/JS
cd public/assets/css && cleancss -o styles.min.css styles.css
cd public/assets/js && terser -o script.min.js script.js
```

### 2. Remove Unnecessary Assets

```bash
# Cek assets yang tidak terpakai
ls -lh public/assets/

# Hapus assets yang tidak dibutuhkan
```

### 3. Use CDN for Assets

Upload assets ke CDN (Cloudflare, AWS CloudFront, dll) dan serve dari sana.

---

## Rekomendasi

### Urutan Prioritas:

1. **WAJIB:** Gunakan Solusi 1 (.vercelignore optimization)
   - ✅ Gratis
   - ✅ Lebih cepat (cold start)
   - ✅ Best practice

2. **JIKA PERLU:** Enable Solusi 2 (Large Functions Beta)
   - ⚠️ Hanya jika Solusi 1 tidak cukup
   - ⚠️ Perhatikan cold start time

3. **OPSIONAL:** Implement Solusi 3 (Asset Optimization)
   - ✅ Meningkatkan performance
   - ✅ Tidak perlu untuk solve size issue

---

## Setelah Fix

### 1. Commit perubahan

```bash
git add .vercelignore
git commit -m 'feat: optimize Vercel deployment size'
```

### 2. Push ke Vercel

```bash
git push origin main
```

### 3. Monitor deployment

```bash
# Check deployment status
vercel --prod

# Atau di Vercel Dashboard
# https://vercel.com/[your-org]/glucoscreen/deployments
```

### 4. Verify function size

```bash
# Set VERCEL_ANALYZE_BUILD_OUTPUT untuk lihat detailed report
vercel env add VERCEL_ANALYZE_BUILD_OUTPUT production
# Value: 1

# Redeploy dan check logs
```

---

## Troubleshooting

### Masih exceeds 250MB?

```bash
# Check apa yang masih included
vercel --prod --debug

# Atau download build output untuk inspection
vercel download [deployment-url]
```

### Cek file size di Vercel

```bash
# Set environment variable untuk analyze
vercel env add VERCEL_ANALYZE_BUILD_OUTPUT production
# Value: 1

# Redeploy dan lihat detailed report di Vercel dashboard
```

---

## Catatan Penting

### 1. File `.vercelignore` vs `.gitignore`

- `.vercelignore` - untuk Vercel deployment
- `.gitignore` - untuk Git version control
- Keduanya bisa berbeda!

### 2. Files yang WAJIB di-deploy

```
✅ api/ - Application entry point
✅ app/ - Laravel application code
✅ bootstrap/ - Laravel bootstrap
✅ config/ - Configuration files
✅ database/ - Migrations & seeds
✅ lang/ - Language files
✅ public/ - Public assets (except apk/ipa)
✅ resources/ - Views & assets
✅ routes/ - Route definitions
✅ storage/app/ - User uploaded files (if needed)
```

### 3. Files yang TIDAK PERLU di-deploy

```
❌ vendor/ - Dependencies (replaced by composer install)
❌ node_modules/ - NPM packages
❌ tests/ - Test files
❌ .git/ - Git repository
❌ apk/ - Mobile binaries
❌ ipa/ - Mobile binaries
❌ storage/logs/ - Log files
❌ bootstrap/cache/ - Cache files
```

---

## Status

**Current Status:** .vercelignore updated ✅

**Next Action:**
1. Commit dan push perubahan
2. Monitor deployment di Vercel
3. Jika masih error, ikuti troubleshooting steps

---

**Last Updated:** 2025-08-12

