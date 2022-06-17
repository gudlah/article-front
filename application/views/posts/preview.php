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
			const baseUrl = '<?= base_url('posts/'); ?>';
			const url = '<?= apiUrl(); ?>';

			window.addEventListener('DOMContentLoaded', () => renderItem(0));

			document.querySelector('body').addEventListener('click', e => {
				if(e.target.classList.contains('page')) {
					e.preventDefault();
					renderItem(e.target.dataset.offset);
				} else if(e.target.id == 'prev') {
					e.preventDefault();
					prev();
				} else if(e.target.id == 'next') {
					e.preventDefault();
					next();
				} else if(e.target.id == 'first') {
					e.preventDefault();
					first();
				} else if(e.target.id == 'last') {
					e.preventDefault();
					last();
				}
			});

			const first = () => renderItem(0);

			const last = () => renderItem(parseInt(document.querySelector('#jumlah').value)-1);

			const prev = () => {
				let offset = 0;
				document.querySelectorAll('.page').forEach(page => {
					const parrent = page.parentNode;
					if(parrent.classList.contains('active')) {
						offset += parseInt(page.dataset.offset)-1;
					}
				});
				renderItem(offset);
			};

			const next = () => {
				let offset = 0;
				document.querySelectorAll('.page').forEach(page => {
					const parrent = page.parentNode;
					if(parrent.classList.contains('active')) {
						offset += parseInt(page.dataset.offset)+1;
					}
				});
				renderItem(offset);
			};

			const renderItem = offset => {
				offset = parseInt(offset);
				fetch(`${url}/1/${offset}`)
					.then(res => res.json())
					.then(res => {
						document.querySelector('#utama').innerHTML = itemHTML(res.data[0]);
						renderPagination(offset);
					});
			};

			const renderPagination = offset => {
				fetch(`${url}/count`)
					.then(res => res.json())
					.then(res => {
						const jumlah = res.data[0].jumlah;
						let html = headPageHTML(jumlah, offset);
						for(let i = 0; i < jumlah; i++) {
							html += numberPageHTML(offset, i);
						}
						html += footPageHTML(jumlah, offset);
						document.querySelector('#tempatPagination').innerHTML = paginationHTML(html);
					});
			};

			const headPageHTML = (jumlah, offset) => `<input type="hidden" id="jumlah" value="${jumlah}">
			<li class="page-item ${(offset == 0)? 'disabled' : ''}"><a id="first" class="page-link">First</a></li>
			<li class="page-item ${(offset == 0)? 'disabled' : ''}"><a id="prev" class="page-link">&laquo;</a></li>`;

			const numberPageHTML = (offset, i) => `<li class="page-item ${(offset == i)? 'active' : ''}"><a class="page-link page" href="#" data-offset="${i}">${i+1}</a></li>`;

			const footPageHTML = (jumlah, offset) => `<li class="page-item ${(offset + 1 == jumlah)? 'disabled' : ''}"><a id="next" class="page-link" href="#">&raquo;</a></li>
			<li class="page-item ${(offset + 1 == jumlah)? 'disabled' : ''}"><a id="last" class="page-link" href="#">Last</a></li>`;

			const itemHTML = data => `<div class="row mb-2">
				<div class="col-md-8"><h5 class="font-weight-bold">${data.title}</h5></div>
				<div class="col-md d-flex justify-content-end"><small class="font-italic">${(!data.updated_date)? `Created at ${konversiTanggal(data.created_date)}` : `Updated at ${konversiTanggal(data.updated_date)}`}</small></div>
			</div>
			<p>${data.content}</p>
			<small>Category : ${data.category}</small>
			<div id="tempatPagination" class="mt-4"></div>`;

			const paginationHTML = li => `<nav class="d-flex justify-content-center"><ul class="pagination pagination-sm">${li}</ul></nav>`;

			const konversiTanggal = tgl => {
				tgl = new Date(tgl);
				const ss = String(tgl.getSeconds() + 1).padStart(2, "0");
				const ii = String(tgl.getMinutes() + 1).padStart(2, "0");
				const hh = String(tgl.getHours() + 1).padStart(2, "0");
				const dd = String(tgl.getDate()).padStart(2, "0");
				const mm = String(tgl.getMonth() + 1).padStart(2, "0");
				const yyyy = tgl.getFullYear();
				return `${yyyy}-${mm}-${dd} ${hh}:${ii}:${ss}`;
			};
		</script>
	</body>
</html>
