# TEMA 1: APLIKASI DOMPET DIGITAL - DATA KYC & CONFIDENTIALITY

## SKENARIO

Kebocoran masif pada Aplikasi E-PayKu melibatkan data NIK dan KTP pengguna. Dalam konteks aplikasi manajemen data santri ini, kebocoran data PII (Personally Identifiable Information) seperti NIS, alamat, nomor HP, foto, dan data wali santri dapat terjadi dengan skenario serupa.

---

## ANALISIS: BAGAIMANA DATA PII BISA BOCOR

### 1. **Kerentanan Data At Rest (Data Tersimpan)**

#### a. Database Tanpa Enkripsi

-   **Masalah**: Data PII seperti NIS, alamat, nomor HP, dan foto disimpan dalam database tanpa enkripsi
-   **Contoh dalam Aplikasi**:
    ```php
    // Di SantriController.php
    SantriDetail::create([
        'nis' => $validated['nis'],           // Plain text
        'alamat_santri' => $validated['alamat_santri'],  // Plain text
        'nomor_hp_santri' => $validated['nomor_hp_santri'], // Plain text
    ]);
    ```
-   **Dampak**: Jika database diretas atau diakses secara tidak sah, semua data PII dapat langsung dibaca

#### b. Penyimpanan File Tidak Aman

-   **Masalah**: Foto santri disimpan di storage publik tanpa enkripsi
-   **Contoh dalam Aplikasi**:
    ```php
    $fotoPath = $request->file('foto')->store('santri/fotos', 'public');
    ```
-   **Dampak**: Siapa pun yang mengetahui URL dapat mengakses foto santri secara langsung

#### c. Kredensial Database Terpapar

-   **Masalah**: File `.env` yang berisi kredensial database tidak dilindungi dengan baik
-   **Dampak**: Jika file `.env` terpapar, penyerang dapat langsung mengakses database

#### d. Backup Database Tidak Terenkripsi

-   **Masalah**: Backup database disimpan dalam format plain text
-   **Dampak**: Jika backup dicuri, semua data PII dapat diakses

### 2. **Kerentanan Data In Transit (Data Saat Berpindah)**

#### a. Komunikasi HTTP (Non-HTTPS)

-   **Masalah**: Data dikirim melalui protokol HTTP yang tidak terenkripsi
-   **Dampak**: Data dapat di-intercept oleh penyerang menggunakan Man-in-the-Middle (MITM) attack
-   **Contoh**: Login credentials, data santri yang ditampilkan di browser

#### b. API Tanpa Enkripsi

-   **Masalah**: Jika aplikasi menggunakan API, data dikirim tanpa enkripsi
-   **Dampak**: Data PII dapat dilihat oleh siapa saja yang meng-intercept komunikasi

#### c. Session Hijacking

-   **Masalah**: Session token dikirim melalui koneksi yang tidak aman
-   **Dampak**: Penyerang dapat mencuri session dan mengakses data sebagai user yang sah

### 3. **Kerentanan Akses Tidak Terkontrol**

#### a. Principle of Least Privilege Tidak Diterapkan

-   **Masalah**: User dengan privilege berlebih dapat mengakses data yang tidak seharusnya
-   **Contoh**: Admin dapat melihat semua data santri tanpa batasan

#### b. Tidak Ada Logging dan Monitoring

-   **Masalah**: Tidak ada pencatatan siapa yang mengakses data kapan
-   **Dampak**: Kebocoran data sulit dideteksi dan dilacak

---

## TINDAKAN PREVENTIVE (Pencegahan)

### A. **Melindungi Data At Rest**

#### 1. **Enkripsi Database**

**a. Column-Level Encryption**

```php
// Di Model SantriDetail.php - tambahkan accessor/mutator
use Illuminate\Support\Facades\Crypt;

public function setNomorHpSantriAttribute($value)
{
    if ($value) {
        $this->attributes['nomor_hp_santri'] = Crypt::encryptString($value);
    }
}

public function getNomorHpSantriAttribute($value)
{
    if ($value) {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }
    return null;
}
```

**b. File-Level Encryption untuk Foto**

```php
// Simpan foto dengan enkripsi
use Illuminate\Support\Facades\Storage;

$encryptedContent = encrypt(file_get_contents($request->file('foto')->getRealPath()));
Storage::disk('local')->put('encrypted/santri/' . $filename, $encryptedContent);
```

#### 2. **Secure File Storage**

```php
// Simpan file di storage private, bukan public
$fotoPath = $request->file('foto')->store('santri/fotos', 'local'); // 'local' bukan 'public'

// Akses file melalui controller dengan autentikasi
public function getFoto($filename)
{
    $this->authorize('view', SantriDetail::class);
    $path = storage_path('app/santri/fotos/' . $filename);
    return response()->file($path);
}
```

#### 3. **Environment Protection**

-   Pastikan file `.env` tidak di-commit ke repository
-   Gunakan `.gitignore` untuk mengecualikan `.env`
-   Set permission file `.env` menjadi `600` (hanya owner yang bisa read/write)
-   Gunakan environment variables yang di-manage oleh hosting provider

#### 4. **Backup Encryption**

```bash
# Backup database dengan enkripsi
mysqldump -u user -p database_name | openssl enc -aes-256-cbc -salt -out backup.sql.enc
```

#### 5. **Access Control pada Database**

```sql
-- Buat user database dengan privilege minimal
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON managemen_data_santri.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
```

### B. **Melindungi Data In Transit**

#### 1. **Implementasi HTTPS/SSL**

```apache
# Di .htaccess atau server config
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

#### 2. **HSTS (HTTP Strict Transport Security)**

```php
// Di AppServiceProvider.php atau middleware
public function boot()
{
    if (config('app.env') === 'production') {
        \URL::forceScheme('https');
    }
}
```

#### 3. **Session Security**

```php
// Di config/session.php
'secure' => env('SESSION_SECURE_COOKIE', true), // HTTPS only
'http_only' => true, // Prevent JavaScript access
'same_site' => 'strict', // CSRF protection
```

#### 4. **API Authentication**

```php
// Gunakan token-based authentication dengan HTTPS
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/api/santri', [SantriController::class, 'index']);
});
```

### C. **Access Control & Least Privilege**

#### 1. **Role-Based Access Control (RBAC)**

```php
// Pastikan hanya admin yang bisa mengakses data santri
public function index()
{
    $this->authorize('viewAny', SantriDetail::class);

    $santri = User::where('role', 'santri')
        ->with('santriDetail')
        ->paginate(10);

    return view('santri.index', compact('santri'));
}
```

#### 2. **Data Masking untuk Non-Admin**

```php
// Di view atau controller
public function show(string $id)
{
    $santri = User::findOrFail($id);

    // Mask sensitive data untuk non-admin
    if (Auth::user()->role !== 'admin') {
        $santri->nomor_hp_santri = $this->maskPhoneNumber($santri->nomor_hp_santri);
    }

    return view('santri.show', compact('santri'));
}

private function maskPhoneNumber($phone)
{
    if (!$phone) return null;
    return substr($phone, 0, 3) . '****' . substr($phone, -3);
}
```

#### 3. **Audit Logging**

```php
// Log semua akses ke data PII
use Illuminate\Support\Facades\Log;

public function show(string $id)
{
    Log::channel('audit')->info('Data santri diakses', [
        'user_id' => Auth::id(),
        'user_role' => Auth::user()->role,
        'santri_id' => $id,
        'ip_address' => request()->ip(),
        'timestamp' => now(),
    ]);

    // ... rest of the code
}
```

---

## TINDAKAN CORRECTIVE (Perbaikan Setelah Kebocoran)

### A. **Respond (Merespons Insiden)**

#### 1. **Incident Response Plan**

```php
// Buat middleware untuk detect anomaly
class SecurityAlertMiddleware
{
    public function handle($request, Closure $next)
    {
        // Deteksi multiple failed login attempts
        if ($this->detectBruteForce($request)) {
            Log::critical('Brute force attack detected', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);

            // Block IP temporarily
            Cache::put('blocked_ip_' . $request->ip(), true, 3600);
        }

        return $next($request);
    }
}
```

#### 2. **Immediate Actions**

-   **Isolasi Sistem**: Matikan akses ke sistem yang terkompromi
-   **Notifikasi**: Beri tahu semua stakeholder dan pengguna yang terpengaruh
-   **Forensics**: Kumpulkan log dan bukti untuk analisis

#### 3. **NIST CSF - Respond Functions**

**RS.RP-1: Response Plan Executed**

```php
// Buat endpoint untuk emergency response
Route::post('/admin/emergency/lockdown', function() {
    // Lock semua akses sementara
    Cache::put('system_lockdown', true, 3600);
    return response()->json(['message' => 'System locked down']);
})->middleware(['auth', 'role:admin']);
```

**RS.CO-1: Personnel Know Roles**

-   Dokumentasi jelas siapa yang bertanggung jawab untuk setiap tahap response
-   Tim security harus dapat dihubungi 24/7

**RS.CO-2: Incidents Reported**

```php
// Sistem pelaporan insiden otomatis
public function reportIncident($type, $details)
{
    Log::critical('Security incident reported', [
        'type' => $type,
        'details' => $details,
        'timestamp' => now(),
    ]);

    // Send email alert to security team
    Mail::to(config('app.security_email'))->send(new IncidentAlert($type, $details));
}
```

**RS.CO-3: Information Shared**

-   Komunikasi transparan dengan pengguna yang terpengaruh
-   Berikan informasi tentang apa yang terjadi dan langkah-langkah yang diambil

**RS.CO-4: Coordination with Stakeholders**

-   Koordinasi dengan pihak hukum jika diperlukan
-   Kerjasama dengan CERT/CSIRT lokal

**RS.CO-5: Voluntary Information Sharing**

-   Berbagi informasi dengan komunitas security untuk mencegah serangan serupa

**RS.AN-1: Notifications Detected**

```php
// Real-time monitoring dan alerting
class SecurityMonitoring
{
    public function checkForAnomalies()
    {
        // Check for unusual data access patterns
        $recentAccess = DB::table('audit_logs')
            ->where('created_at', '>', now()->subHours(1))
            ->where('action', 'view_santri')
            ->count();

        if ($recentAccess > 100) { // Threshold
            $this->triggerAlert('Unusual data access pattern detected');
        }
    }
}
```

**RS.AN-2: Impact Determined**

-   Tentukan scope kebocoran data
-   Identifikasi data apa saja yang terpengaruh
-   Tentukan jumlah pengguna yang terpengaruh

**RS.AN-3: Forensics Performed**

-   Analisis log untuk menentukan bagaimana kebocoran terjadi
-   Identifikasi vektor serangan
-   Dokumentasi timeline kejadian

**RS.AN-4: Incidents Categorized**

-   Kategorikan insiden berdasarkan severity
-   High: Data PII bocor ke publik
-   Medium: Data diakses oleh unauthorized user
-   Low: Attempt yang gagal

**RS.AN-5: Processes Established**

-   Dokumentasi proses response untuk insiden serupa di masa depan

**RS.MI-1: Incidents Contained**

```php
// Containment actions
public function containIncident($incidentId)
{
    // Revoke all active sessions
    DB::table('sessions')->truncate();

    // Force password reset for affected users
    User::where('role', 'admin')->update([
        'password' => Hash::make(Str::random(32)),
        'must_change_password' => true,
    ]);

    // Block suspicious IPs
    $this->blockSuspiciousIPs();
}
```

**RS.MI-2: Incident Mitigated**

```php
// Mitigation steps
public function mitigateIncident()
{
    // Patch vulnerabilities
    // Update dependencies
    // Change all encryption keys
    // Rotate API keys and tokens
}
```

**RS.IM-1: Response Improvements Identified**

-   Pelajari dari insiden
-   Identifikasi area yang perlu diperbaiki

**RS.IM-2: Response Plans Updated**

-   Update incident response plan berdasarkan lesson learned

### B. **Recover (Pemulihan)**

#### 1. **RC.RP-1: Recovery Plan Executed**

**a. Data Recovery**

```php
// Restore dari backup yang aman
// Pastikan backup sudah di-verify sebelum restore
public function restoreFromBackup($backupFile)
{
    // Verify backup integrity
    if (!$this->verifyBackupIntegrity($backupFile)) {
        throw new Exception('Backup file corrupted');
    }

    // Restore database
    Artisan::call('db:restore', ['file' => $backupFile]);

    // Verify data integrity after restore
    $this->verifyDataIntegrity();
}
```

**b. System Recovery**

-   Restore sistem dari backup terakhir sebelum insiden
-   Pastikan semua patch keamanan sudah diinstal
-   Verifikasi semua komponen sistem berfungsi normal

#### 2. **RC.IM-1: Recovery Improvements Identified**

-   Evaluasi proses recovery
-   Identifikasi area yang dapat diperbaiki

#### 3. **RC.IM-2: Recovery Plans Updated**

-   Update recovery plan berdasarkan pengalaman
-   Dokumentasikan waktu recovery yang sebenarnya vs target

#### 4. **RC.CO-1: Public Relations**

-   Komunikasi publik yang transparan
-   Berikan update regular kepada pengguna yang terpengaruh
-   Publikasikan langkah-langkah perbaikan yang telah dilakukan

#### 5. **Post-Incident Activities**

**a. Password Reset untuk Semua User**

```php
// Force password reset untuk semua pengguna
User::where('role', 'santri')->update([
    'must_change_password' => true,
    'password_reset_token' => Str::random(64),
]);
```

**b. Rotate All Secrets**

-   Generate new encryption keys
-   Rotate database credentials
-   Update API keys
-   Generate new session keys

**c. Security Audit Lengkap**

-   Lakukan audit keamanan menyeluruh
-   Identifikasi semua kerentanan yang ada
-   Prioritaskan perbaikan berdasarkan risk level

**d. Enhanced Monitoring**

```php
// Implementasi monitoring yang lebih ketat
class EnhancedMonitoring
{
    public function monitorDataAccess()
    {
        // Log setiap akses ke data PII
        // Alert jika ada pola yang mencurigakan
        // Real-time dashboard untuk security team
    }

    public function monitorSystemHealth()
    {
        // Monitor server resources
        // Alert jika ada anomali
        // Check for suspicious processes
    }
}
```

---

## IMPLEMENTASI PRINSIP KERAHASIAAN (CONFIDENTIALITY)

### 1. **Data Classification**

-   **High Confidentiality**: NIS, alamat lengkap, nomor HP, foto
-   **Medium Confidentiality**: Nama, tanggal lahir
-   **Low Confidentiality**: Status santri (aktif/boyong)

### 2. **Access Control Matrix**

| Role           | View NIS | View Alamat | View Nomor HP | View Foto | Edit Data |
| -------------- | -------- | ----------- | ------------- | --------- | --------- |
| Admin          | ✅       | ✅          | ✅            | ✅        | ✅        |
| Santri (Own)   | ✅       | ✅          | ✅            | ✅        | ❌        |
| Santri (Other) | ❌       | ❌          | ❌            | ❌        | ❌        |

### 3. **Encryption at Multiple Layers**

-   **Application Layer**: Enkripsi field sensitive di model
-   **Database Layer**: Transparent Data Encryption (TDE)
-   **Storage Layer**: Encrypted file storage
-   **Transport Layer**: HTTPS/TLS

---

## KESIMPULAN

Untuk melindungi data PII dalam aplikasi manajemen data santri, perlu implementasi:

1. **Enkripsi multi-layer** untuk data at rest
2. **HTTPS/TLS** untuk data in transit
3. **Access control yang ketat** dengan prinsip least privilege
4. **Audit logging** untuk semua akses data PII
5. **Incident response plan** yang siap dijalankan
6. **Recovery plan** untuk pemulihan setelah insiden
7. **Continuous monitoring** untuk deteksi dini ancaman

Dengan menerapkan semua langkah preventive dan corrective di atas, risiko kebocoran data PII dapat diminimalisir secara signifikan.
