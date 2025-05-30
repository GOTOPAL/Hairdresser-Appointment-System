# 💇‍♂️ Hairdresser Appointment System

Bu proje, kuaförlerin müşterilerini, hizmetlerini ve randevularını kolayca yönetmesini sağlayan modern ve kullanıcı dostu bir web panelidir. Laravel PHP framework'ü ve SQL Server veritabanı kullanılarak geliştirilmiştir.

## 🚀 Özellikler

✅ Kuaförler için:
- Profil düzenleme (fotoğraf, biyografi, iletişim bilgileri)  
- Hizmet ekleme ve düzenleme  
- Randevuları listeleme, onaylama, reddetme  
- Bildirimler ve yorum yönetimi  
- Randevu takvimi ve raporlama araçları  

✅ Müşteriler için:
- Kuaför arama ve profil inceleme  
- Randevu oluşturma ve yönetme  
- Hizmet değerlendirme ve yorum yapma  

✅ Admin paneli:
- Kuaför, müşteri ve randevu yönetimi  
- Sistem ayarları ve bakım modu  

## 🛠️ Kurulum

1️⃣ **Depoyu klonla:**  
```bash
git clone https://github.com/kullanici-adi/hairdresser-paneli.git
cd hairdresser-paneli
```

2️⃣ **Bağımlılıkları yükle:**  
```bash
composer install
npm install && npm run dev
```

3️⃣ **.env yapılandırması:**  
```bash
cp .env.example .env
php artisan key:generate
```

4️⃣ **Veritabanı ayarları:**  
`.env` dosyasındaki DB bilgilerini kendi ortamınıza göre güncelleyin.

5️⃣ **Veritabanını oluştur:**  
```bash
php artisan migrate --seed
```

6️⃣ **Sunucuyu başlat:**  
```bash
php artisan serve
```

Artık proje `http://localhost:8000` adresinde çalışacaktır.

## ⚙️ Kullanılan Teknolojiler

- **PHP 8.2**  
- **Laravel 12**  
- **SQL Server**  
- **Bootstrap 5**  
- **JavaScript**  
- **Modern CSS**

## 🎨 Ekran Görselleri

Tüm ekran görüntülerini ve proje önizlemesini aşağıdaki bağlantıdan görebilirsiniz:  
[📄 Proje Ekran Görselleri ve Önizleme (PDF)](https://drive.google.com/file/d/1eB8tsxX-Jfhf-Z3v-2gFXaiHKLT_VMoH/view?usp=share_link)
## 👤 Katkıda Bulunanlar

- **Göktuğ Oğuzhan TOPAL** – Proje Geliştiricisi  


## 📄 Lisans

Bu proje [MIT Lisansı](LICENSE) ile lisanslanmıştır.

## 📬 İletişim

Herhangi bir sorunuz veya öneriniz varsa bana şu adresten ulaşabilirsiniz:  
📧 **goktugsw@gmail.com**
