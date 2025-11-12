@extends('base.base')
@section('title','app-parafrasa')
@section('content')
		<!-- Hero Section -->
		<section id="hero" class="hero section">
			<div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
				<div class="carousel-item active">
					<img src="assets/img/hero-carousel/bg.png" alt="">
					<div class="container">
						<h2>Selamat Datang di Aplikasi Parafrasa</h2>
						<p>Aplikasi ini digunakan untuk memparafrasa paragraf per bab agar terhindar dari plagiat saat ujian proposal maupun skripsi ya.</p>
						<a href="#about" class="btn-get-started">Lebih Detail</a>
					</div>
				</div>
				<!-- End Carousel Item -->
				<div class="carousel-item">
					<img src="assets/img/hero-carousel/bg.png" alt="">
					<div class="container">
						<h2>Selamat Datang di Aplikasi Parafrasa</h2>
						<p>Aplikasi ini digunakan untuk memparafrasa paragraf per bab agar terhindar dari plagiat saat ujian proposal maupun skripsi ya.</p>
						<a href="#about" class="btn-get-started">Lebih Detail</a>
					</div>
				</div>
				<!-- End Carousel Item -->
				<div class="carousel-item">
					<img src="assets/img/hero-carousel/bg.png" alt="">
					<div class="container">
						<h2>Selamat Datang di Aplikasi Parafrasa</h2>
						<p>Aplikasi ini digunakan untuk memparafrasa paragraf per bab agar terhindar dari plagiat saat ujian proposal maupun skripsi ya.</p>
						<a href="#about" class="btn-get-started">Lebih Detail</a>
					</div>
				</div>
				<!-- End Carousel Item -->
				<a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
					<span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
				</a>
				<a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
					<span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
				</a>
				<ol class="carousel-indicators"></ol>
			</div>
		</section>
		<!-- /Hero Section -->

		<!-- Appointment Section -->
		<section id="appointment" class="appointment section light-background">
			<div class="container section-title" data-aos="fade-up">
				<h2>Aplikasi Parafrasa Dokumen</h2>
				<p>Silakan masukkan teks atau file dokumen (.docx) untuk diparafrase. Disarankan per Bab agar hasil lebih cepat dan akurat.</p>
			</div>
			<div class="container" data-aos="fade-up" data-aos-delay="100">
				<form action="{{ route('parafrase.proses') }}" id="parafraseForm" method="POST" enctype="multipart/form-data">
				@csrf
					<div class="form-group mt-3">
						<label>Teks (opsional):</label>
						<textarea class="form-control" name="message" rows="6" placeholder="Tulis teks di sini..."></textarea>
					</div>
					<div class="form-group mt-3">
						<label>Atau upload file .docx:</label>
						<input type="file" class="form-control" name="file" accept=".docx">
					</div>
					<div class="progress mt-4" style="height: 20px; display: none;">
						<div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%;">0%</div>
					</div>
					<div id="status-message" class="mt-3 text-center text-muted"></div>
					<div class="text-center mt-4">
						<button type="submit" class="btn btn-primary">
							<i class="fa fa-sync-alt me-2"></i> Proses Parafrasa
						</button>
					</div>
				</form>
				<div id="download-link" class="text-center mt-4"></div>
				@if(session('download'))
					<div class="alert alert-success mt-3 text-center">
						✅ Parafrasa selesai! <a href="{{ session('download') }}" class="btn btn-success btn-sm" download>Unduh Hasil DOCX</a>
					</div>
				@endif

				@if(session('error'))
					<div class="alert alert-danger mt-3">
						{{ session('error') }}
					</div>
				@endif
			</div>
		</section>
		<!-- /Appointment Section -->

		<!-- Contact Section -->
		<section id="contact" class="contact section">
			<!-- Section Title -->
			<div class="container section-title" data-aos="fade-up">
				<h2>Kontak Kami</h2>
			</div>
			<!-- End Section Title -->
			<div class="mb-5" data-aos="fade-up" data-aos-delay="200">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d147700.3054762082!2d112.51092645772403!3d-7.384722457501857!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e3d322e0ac83%3A0x1819ccd197419e8a!2sWarung%20Bebek%20Srundeng!5e0!3m2!1sid!2sid!4v1761478541197!5m2!1sid!2sid" width="1600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
			<!-- End Google Maps -->
			<div class="container" data-aos="fade-up" data-aos-delay="100">
				<div class="row gy-4">
					<div class="col-lg-12">
						<div class="row gy-4">
							<div class="col-lg-12">
								<div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
									<i class="bi bi-geo-alt"></i>
									<h3>Alamat</h3>
									<p>GTA San Anddreas</p>
								</div>
							</div>
							<!-- End Info Item -->
							<div class="col-md-6">
								<div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
									<i class="bi bi-telephone"></i>
									<h3>No. Whatsapp</h3>
									<p>+1 5589 55488 55</p>
								</div>
							 </div>
							 <!-- End Info Item -->
							 <div class="col-md-6">
								<div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
									<i class="bi bi-envelope"></i>
									<h3>Email</h3>
									<p>wbs@gmail.com</p>
								</div>
							 </div>
							 <!-- End Info Item -->
						</div>
					</div>
					<!-- End Contact Form -->
				</div>
			</div>
		</section>
		<!-- /Contact Section -->
@endsection