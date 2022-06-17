<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view('templates/css'); ?>
        <title>Article - <?= $judul; ?></title>
	</head>
	<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
		<?php $this->load->view('templates/topbar'); ?>
		<div class="app-body">
			<?php $this->load->view('templates/sidebar'); ?>
            <main class="main">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">Posts</li>
					<li class="breadcrumb-item active"><?= $judul; ?></li>
				</ol>
				<div class="container-fluid">
					<div id="tempatAlert"></div>
                    <h5><?= $judul; ?></h5>
                    <div class="card">
                        <div class="card-body" id="utama"></div>
                    </div>
				</div>
			</main>
		</div>
		<?php $this->load->view('templates/footer'); ?>
		<?php $this->load->view('templates/js'); ?>
		<script>
			const url = '<?= apiUrl(); ?>';

			window.addEventListener('DOMContentLoaded', () => renderUtama());
			
			const renderUtama = () => document.querySelector('#utama').innerHTML = utamaHTML;

			document.querySelector('body').addEventListener('click', e => {
				if(e.target.classList.contains('tombolPublish')) {
					sendData('publish');
				} else if(e.target.classList.contains('tombolDraft')) {
					sendData('draft');
				}
			});

			const utamaHTML = `<div class="row mb-1">
				<div class="col-3">Title</div>
				<div class="col"><input type="text" id="title" class="form-control form-control-sm"></div>
			</div>
			<div class="row mb-1">
				<div class="col-3">Category</div>
				<div class="col"><input type="text" id="category" class="form-control form-control-sm"></div>
			</div>
			<div class="row mb-2">
				<div class="col-3">Content</div>
				<div class="col"><textarea class="form-control" id="content"></textarea></div>
			</div>
			<button class="btn btn-primary btn-sm tombolPublish"><i class="fa fa-upload tombolPublish"></i> Publish</button>
			<button class="btn btn-success btn-sm tombolDraft"><i class="fa fa-save tombolDraft"></i> Draft</button>`;

			const sendData = status => {
				let total = 0;
				let data = {};
				const ids = ['title', 'category', 'content'];
				for(id of ids) {
					total += 1;
					const input = document.querySelector(`#${id}`);
					if(!input.value) {
						input.classList.add('is-invalid');
					} else {
						input.classList.remove('is-invalid');
						data[id] = input.value;
					}
				}
				if(Object.keys(data).length == total) {
					data.status = status;
					ajax({
						link: '',
						sukses: aksiSukses,
						gagal: aksiGagal,
						data
					});
				}
			};

			const aksiSukses = res => {
				document.querySelector('#tempatAlert').innerHTML = alertHTML('success', 'Berhasil menyimpan data');
				renderUtama();
			};

			const aksiGagal = res => {
				document.querySelector('#tempatAlert').innerHTML = alertHTML('danger', res.message);
			};
		</script>
	</body>
</html>
