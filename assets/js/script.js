const btnHTML = tombols => `<div class="dropdown">
    <button class="btn btn-info btn-sm dropdown-toggle" id="tombolAksi" type="button" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-cog"></i> Aksi
    </button>
    <div class="dropdown-menu" aria-labelledby="tombolAksi">
        ${tombols.map(({id, ikon, nama, param = '', nilai = '', url = '#', target = 0}) => `<a class="dropdown-item" id="${id}" ${(target == 1)? 'target="blank"' : ''} href="${url}" ${(!param)? '' : `data-${param}="${nilai}"`}><i class="${ikon}"></i> ${nama}</a>`).join()}
    </div>
</div>`;

const ajax = ({link, data, gagal = '', sukses = '', method = 'post' }) => {
    $.ajax({
        url: url+link,
        type: method,
        dataType: 'json',
        data: JSON.stringify(data),
        contentType: 'application/json; charset=utf-8',
        success: res => {
            if(res.status == false) {
                if(gagal) gagal(res);
            } else {
                if(sukses) sukses(res);
            }
        },
        error: (XMLHttpRequest, textStatus, errorThrown) => {
            if(gagal) gagal({remarks: XMLHttpRequest});
        },
        timeout: 60000
    });
};

const alertHTML = (warna, teks) => `<div class="alert alert-${warna} alert-dismissible fade show" role="alert">
    ${teks}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>`;

const modal = ({idModal, judul, body, idBtn, ukuran = ''}) => {
    return `<div class="modal fade" id="${idModal}" role="dialog">
        <div class="modal-dialog modal-dialog-centered ${ukuran}" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>${judul}</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">${body}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    ${(idBtn)? `<button type="button" id="${idBtn}" class="btn btn-primary">Konfirmasi</button>` : ''}
                </div>
            </div>
        </div>
    </div>`;
};