# 🧠 App Paraphraser API (Indonesian Formal Academic Style)

Proyek ini adalah layanan API Flask berbasis NLP (Natural Language Processing) yang berfungsi untuk memparafrase teks atau dokumen .docx berbahasa Indonesia menjadi gaya formal dan akademik.
Flask digunakan sebagai microservice NLP berbasis Python, sedangkan Laravel bertugas sebagai gateway API utama atau backend utama aplikasi — mengirimkan teks ke Flask untuk diproses dan menerima hasilnya untuk ditampilkan di aplikasi web.  
Proses dilakukan dalam beberapa tahap:
1. Translate teks Indonesia → Inggris  
2. Parafrase dengan model **T5-Large Paraphraser (High Quality)**  
3. Translate kembali Inggris → Indonesia  
4. Hasil dikembalikan dalam bentuk teks atau file `.docx`.

[Laravel App]  <--->  [Flask Paraphraser API]  <--->  [Hugging Face Models]
     │                           │
     │                           ├── ramsrigouthamg/t5-large-paraphraser-diverse-high-quality
     │                           ├── Helsinki-NLP/opus-mt-id-en
     │                           └── Helsinki-NLP/opus-mt-en-id
     │
     └── Mengirim request teks atau file DOCX untuk diparafrase

Penjelasan alur:

Laravel mengirimkan teks (atau file .docx) ke Flask API menggunakan endpoint /parafrase_text atau /parafrase_docx.

Flask melakukan proses:

Translate ID → EN

Paraphrase (T5 model)

Translate kembali EN → ID

Flask API mengirim hasilnya ke Laravel untuk ditampilkan ke pengguna atau disimpan.

Hasil dapat dikirim dalam bentuk teks JSON atau file .docx.

---

##🚀 Fitur Utama

🧠 Integrasi Laravel ↔ Flask (via HTTP request)

📝 Parafrase teks langsung (/parafrase_text)

📄 Parafrase dokumen .docx secara asynchronous (/parafrase_docx)

⏱️ Cek progress task (/parafrase_status/<task_id>)

📥 Unduh hasil (/parafrase_download/<task_id>)

🔧 Logging otomatis disimpan di ../logs/app_parafrasa.log

🔄 Terjemahan otomatis ID ↔ EN menjaga hasil tetap alami dan akademik

💾 Mendukung multi-threading agar Flask tidak hang selama pemrosesan file besar

---

## 🧰 Persiapan

### 1️⃣ Clone Repository
bash
git clone https://github.com/Yudya404/app_parafrasa.git
cd app_parafrasa

##Buat Virtual Environment
python -m venv venv
venv\Scripts\activate  # Windows
#atau
source venv/bin/activate  # Linux/Mac

##Install Dependensi
pip install -r requirements.txt

##Jalankan Aplikasi
python opencv.py

---

🧠 Model yang Digunakan
Fungsi	Model Hugging Face	Deskripsi
Parafrase	ramsrigouthamg/t5-large-paraphraser-diverse-high-quality	Model T5 untuk parafrase teks bahasa Inggris
Translate ID → EN	Helsinki-NLP/opus-mt-id-en	Model penerjemahan Indonesia ke Inggris
Translate EN → ID	Helsinki-NLP/opus-mt-en-id	Model penerjemahan Inggris ke Indonesia

---

⚙️ Catatan Tambahan

- Pertama kali dijalankan, model akan diunduh dari Hugging Face (ukuran sekitar ±2GB total).
- Disarankan menggunakan GPU (CUDA) untuk mempercepat proses.
- Flask dan Laravel bisa dijalankan di server yang sama atau terpisah.

Jika di server berbeda, pastikan Flask sudah di-expose ke IP publik dan CORS diaktifkan.

🧑‍💻 Lisensi & Pengembang

Proyek ini dikembangkan oleh @Yudya404
Lisensi: MIT License

⭐ Dukungan

Jika proyek ini bermanfaat untuk penelitian atau integrasi NLP-mu, berikan ⭐ di GitHub ❤️
Atau kontribusi dengan menambahkan model terjemahan dan paraphrasing baru untuk bahasa Indonesia.



