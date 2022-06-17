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
                        <div class="card-body">
                            <div id="utama"></div>
                        </div>
                    </div>
				</div>
			</main>
		</div>
        <div id="modal"></div>
		<?php $this->load->view('templates/footer'); ?>
		<?php $this->load->view('templates/js'); ?>
        <script>
            const baseUrl = '<?= base_url('posts/'); ?>';
            const url = '<?= apiUrl(); ?>';

            window.addEventListener('DOMContentLoaded', () => renderUtama());

            const renderUtama = () => {
                document.querySelector('#utama').innerHTML = utamaHTML;
                renderTabTeks();
                renderTablePublish();
            };

            const renderTabTeks = () => {
                fetch(`${url}/count`)
                    .then(res => res.json())
                    .then(res => {
                        document.querySelectorAll('.navTombol').forEach((tombol, i) => {
                            tombol.textContent = `${tombol.dataset.judul} (${res.data[i].jumlah})`;
                        });
                    });
            };

            const utamaHTML = `<nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link navTombol active" id="navPublish" data-coreui-toggle="tab" data-coreui-target="#tabPublish" type="button" role="tab" data-judul="Publish"></button>
                    <button class="nav-link navTombol" id="navDraft" data-coreui-toggle="tab" data-coreui-target="#tabDraft" type="button" role="tab" data-judul="Draft"></button>
                    <button class="nav-link navTombol" id="navThrash" data-coreui-toggle="tab" data-coreui-target="#tabThrash" type="button" role="tab" data-judul="Thrash"></button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade navTab show active" id="tabPublish" role="tabpanel"></div>
                <div class="tab-pane fade navTab" id="tabDraft" role="tabpanel"></div>
                <div class="tab-pane fade navTab" id="tabThrash" role="tabpanel">Thrash</div>
            </div>`;

            document.querySelector('body').addEventListener('click', e => {
                if(e.target.id == 'navPublish') {
                    gantiTab(e);
                    renderTablePublish();
                } else if(e.target.id == 'navDraft') {
                    gantiTab(e);
                    renderTableDraft();
                } else if(e.target.id == 'navThrash') {
                    gantiTab(e);
                    renderTableThrash();
                } else if(e.target.id == 'tombolThrash') {
                    e.preventDefault();
                    renderModalThrash(e);
                } else if(e.target.id == 'btnThrash') {
                    thrash();
                }
            });

            const renderModalThrash = e => {
                fetch(`${url}/${e.target.dataset.id}`)
                    .then(res => res.json())
                    .then(res => {
                        document.querySelector('#modal').innerHTML = modal({
                            idModal: 'modalThrash',
                            judul: 'Thrash',
                            body: thrashHTML(res.data),
                            idBtn: 'btnThrash'
                        });
                        $('#modalThrash').modal('show');
                    });
            };

            const thrash = () => ajax({
                link: `/${document.querySelector(`#teksThrash`).dataset.id}`,
                sukses: aksiSukses,
                gagal: aksiGagal,
                method: 'delete',
                data: {}
            });

            const aksiSukses = res => {
                renderTabTeks();
				document.querySelector('#tempatAlert').innerHTML = alertHTML('success', 'Berhasil menyimpan data');
                let id = '';
                document.querySelectorAll('.navTombol').forEach(tombol => {
                    if(tombol.classList.contains('active')) id += tombol.id;
                });
                document.querySelector(`#${id}`).click();
				$('#modalThrash').modal('hide');
			};

			const aksiGagal = res => {
				document.querySelector('#tempatAlert').innerHTML = alertHTML('danger', res.message);
                $('#modalThrash').modal('hide');
			};

            const thrashHTML = data => `<p id="teksThrash" data-id="${data.id}">Move ${data.title} to thrash?</p>`;

            const gantiTab = e => {
                renderTabTeks();
                document.querySelectorAll('.navTombol').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.navTab').forEach(tab => tab.classList.remove('active'));
                e.target.classList.add('active');
                document.querySelector(e.target.dataset.coreuiTarget).classList.add('show', 'active');
            };

            const renderTablePublish = () => {
                document.querySelector('#tabPublish').innerHTML = tableHTML('tablePublish');
                dataTablePublish();
            };

            const renderTableDraft = () => {
                document.querySelector('#tabDraft').innerHTML = tableHTML('tableDraft');
                dataTableDraft();
            };

            const renderTableThrash = () => {
                document.querySelector('#tabThrash').innerHTML = tableHTML('tableThrash');
                dataTableThrash();
            };

            const tableHTML = id => `<div class="table-responsive">
                <table class="table table-hover table-bordered" id="${id}" style="width: 100%">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th>Title</th>
                            <th>Category</th>
                            <th style="width: 10%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>`;

            const dataTablePublish = () => {
                $('#tablePublish').dataTable({
                    ajax: {
                        url: `${url}?status=publish`,
                        dataSrc: 'data'
                    },
                    columns: [
                        {"data": 'title'},
                        {"data": 'category'},
                        {
                            "data": null,
                            "render" : data => btnHTML([
                                {
                                    id: 'tombolUbah',
                                    nama: 'Edit',
                                    ikon: 'fa fa-fw fa-edit',
                                    url: `${baseUrl}edit/${data.id}`,
                                    target: 1
                                },
                                {
                                    id: 'tombolThrash',
                                    param: 'id',
                                    nilai: data.id,
                                    nama: 'Thrash',
                                    ikon: 'fa fa-fw fa-trash'
                                }
                            ])
                        }
                    ],
                    "columnDefs": [
                        {
                            "class": 'text-center',
                            "targets": [2]
                        }
                    ]
                });
            };

            const dataTableDraft = () => {
                $('#tableDraft').dataTable({
                    ajax: {
                        url: `${url}?status=draft`,
                        dataSrc: 'data'
                    },
                    columns: [
                        {"data": 'title'},
                        {"data": 'category'},
                        {
                            "data": null,
                            "render" : data => btnHTML([
                                {
                                    id: 'tombolUbah',
                                    nama: 'Edit',
                                    ikon: 'fa fa-fw fa-edit',
                                    url: `${baseUrl}edit/${data.id}`,
                                    target: 1
                                },
                                {
                                    id: 'tombolThrash',
                                    param: 'id',
                                    nilai: data.id,
                                    nama: 'Thrash',
                                    ikon: 'fa fa-fw fa-trash'
                                }
                            ])
                        }
                    ],
                    "columnDefs": [
                        {
                            "class": 'text-center',
                            "targets": [2]
                        }
                    ]
                });
            };

            const dataTableThrash = () => {
                $('#tableThrash').dataTable({
                    ajax: {
                        url: `${url}?status=thrash`,
                        dataSrc: 'data'
                    },
                    columns: [
                        {"data": 'title'},
                        {"data": 'category'},
                        {
                            "data": null,
                            "render" : data => btnHTML([
                                {
                                    id: 'tombolUbah',
                                    nama: 'Edit',
                                    ikon: 'fa fa-fw fa-edit',
                                    url: `${baseUrl}edit/${data.id}`,
                                    target: 1
                                }
                            ])
                        }
                    ],
                    "columnDefs": [
                        {
                            "class": 'text-center',
                            "targets": [2]
                        }
                    ]
                });
            };
        </script>
	</body>
</html>
