<!DOCTYPE html>
<html lang="en">
<head>
  @include('includes.head')
</head>
<body class="index-page">
	<header id="header" class="header sticky-top">
		@include('includes.header')
	</header>
	
	<main class="main">
		@yield('content')
	</main>

	<footer id="footer" class="footer light-background">
		@include('includes.footer')
	</footer>

	<!-- Scroll Top -->
	<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
	<!-- Preloader -->
	<div id="preloader"></div>

	<!-- Vendor JS Files -->
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/vendor/php-email-form/validate.js"></script>
	<script src="assets/vendor/aos/aos.js"></script>
	<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
	<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
	<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
	<!-- Main JS File -->
	<script src="assets/js/main.js"></script>
	<!-- Tambahkan SweetAlert CDN -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("parafraseForm");
  const textarea = document.querySelector("textarea[name='message']");
  const fileInput = document.querySelector("input[name='file']");
  const statusMsg = document.getElementById("status-message");
  const progressBar = document.getElementById("progress-bar");
  const progressContainer = document.querySelector(".progress");
  const downloadLink = document.getElementById("download-link");

  const MAX_WORDS = 2000;
  const MAX_FILE_SIZE_MB = 10;

  // 🧮 Hitung jumlah kata
  function countWords(text) {
    return text.trim().split(/\s+/).filter(Boolean).length;
  }

  // 🔹 Hitung kata saat mengetik
  textarea.addEventListener("input", function () {
    const words = countWords(this.value);
    statusMsg.textContent = `Jumlah kata: ${words}/${MAX_WORDS}`;
    if (words > MAX_WORDS) {
      statusMsg.classList.add("text-danger");
      statusMsg.textContent += " ⚠️ Melebihi batas.";
    } else {
      statusMsg.classList.remove("text-danger");
      statusMsg.classList.add("text-muted");
    }
  });

  // 🔹 Validasi file DOCX
  fileInput.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const sizeMB = file.size / (1024 * 1024);
    if (sizeMB > MAX_FILE_SIZE_MB) {
      Swal.fire("File Terlalu Besar", "Ukuran maksimum 10MB.", "warning");
      e.target.value = "";
    }
  });

  // 🔹 Event submit utama
  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const textValue = textarea.value.trim();
    const fileValue = fileInput.files[0];

    if (!textValue && !fileValue) {
      Swal.fire("Input Kosong", "Isi teks atau unggah file .docx.", "warning");
      return;
    }

    // 🔄 Reset tampilan progress
    progressContainer.style.display = "block";
    progressBar.style.width = "0%";
    progressBar.textContent = "0%"; // ✅ tambahkan teks awal
    statusMsg.textContent = "⚙️ Memproses di server AI...";
    downloadLink.innerHTML = "";

    const formData = new FormData(form);

    // 🔁 Simulasi progress naik halus
    let progress = 0;
    const timer = setInterval(() => {
      if (progress < 95) {
        progress += 2;
        progressBar.style.width = progress + "%";
        progressBar.textContent = progress + "%"; // ✅ tampilkan angka
      }
    }, 500);

    try {
      const response = await fetch("{{ route('parafrase.proses') }}", {
        method: "POST",
        body: formData,
      });

      clearInterval(timer);
      progressBar.style.width = "100%";
      progressBar.textContent = "100%"; // ✅ tampilkan angka selesai

      const contentType = response.headers.get("content-type") || "";

      // === 📄 Jika server mengirim file DOCX ===
      if (response.ok && contentType.includes("application/vnd.openxmlformats-officedocument")) {
        const blob = await response.blob();
        const url = URL.createObjectURL(blob);
        statusMsg.textContent = "✅ Parafrasa selesai! Klik untuk mengunduh hasil:";
        downloadLink.innerHTML = `<a href="${url}" download="Hasil_Parafrase.docx" class="btn btn-success mt-3">⬇️ Unduh Hasil DOCX</a>`;
        Swal.fire("Berhasil!", "Dokumen berhasil diparafrase.", "success");

      // === 💬 Jika server mengirim JSON ===
      } else if (response.ok && contentType.includes("application/json")) {
        const data = await response.json();
        const hasil = data.result || "(Tidak ada hasil)";
        statusMsg.textContent = "✅ Parafrasa selesai!";
        downloadLink.innerHTML = `<div class="alert alert-success mt-3"><strong>Hasil:</strong><br>${hasil}</div>`;
        Swal.fire("Berhasil!", "Teks berhasil diparafrase.", "success");

      } else {
        const data = await response.json().catch(() => ({}));
        Swal.fire("Gagal", data.error || "Gagal memproses permintaan.", "error");
      }

    } catch (err) {
      clearInterval(timer);
      Swal.fire("Koneksi Gagal", "Pastikan server Flask berjalan di port 5000.", "error");
      statusMsg.textContent = "⚠️ Gagal terhubung ke server AI.";
    }
  });
});
</script>

</body>
</html>