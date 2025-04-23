@extends('layout')
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quản lý nhân sự</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Quản lý tuyển dụng</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách tuyển dụng</h2>
                        @if (Auth::user()->hasPermissionOnPage('3', '4'))
                            <button data-bs-toggle="modal" data-bs-target="#addRecruitmentModal"
                                class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Add
                                new
                                item</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('recruitment.index.admin') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 col-sm-3 mb-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo vị trí"
                    value="{{ request('position') }}" name="position" id="">
            </div>
            <div class="col-6 col-sm-3 mb-2">
                <input type="date" class="form-control" placeholder="Tìm kiếm theo ngày" value="{{ request('date') }}"
                    name="date" id="">
            </div>

            <div class="col-6 col-sm-3 mb-2">
                <button type="submit" class="btn btn-success  me-3">Tìm kiếm</button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-hover text-center" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Vị trí tuyển dụng</th>
                                    <th>Mô tả lương</th>
                                    <th>Hình thức</th>
                                    <th>Số lượng tuyển</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Trạng thái</th>
                                    <th>Chức năng</th>
                                    <th>Xem CV</th>
                                </tr>
                            </thead>
                            <tbody id="recruitment-list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dialog-edit"></div>
    <div id="dialog-delete"></div>
    <div id="dialog-view-cv">
        <div class="modal fade" id="view-cv" tabindex="-1" aria-labelledby="view-cv-Label" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="view-cv-Label">Xem thông tin ứng tuyển</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card table-card">
                                    <div class="card-body pt-3">
                                        <div class="table-responsive">
                                            <table class="table table-hover text-center" id="pc-dt-simple">
                                                <thead>
                                                    <tr>
                                                        <th>Họ tên</th>
                                                        <th>Số điện thoại</th>
                                                        <th>email@gmail.com</th>
                                                        <th>Ngày ứng tuyển</th>
                                                        <th>Chức năng</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cv-list">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addRecruitmentModal" tabindex="-1" aria-labelledby="addRecruitmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addRecruitmentModalLabel">Đăng tin tuyển dụng</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="add-recruitment">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="hoten" class="form-label">Vị trí tuyển dụng</label>
                                    <input type="text" class="form-control" name="position_job"
                                        placeholder="Vị trí tuyển dụng" required>
                                </div>
                                <div class="mb-3">
                                    <label for="hoten" class="form-label">Mô tả lương</label>
                                    <input type="text" class="form-control" name="salary" placeholder="Mô tả lương"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hình thức</label>
                                    <input type="text" class="form-control" name="time"
                                        placeholder="Hình thức tuyển dụng" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số lượng tuyển</label>
                                    <input type="text" class="form-control" name="quantity"
                                        placeholder="Số lượng tuyển dụng" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" class="form-control" name="expiration_date" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hiển thị</label>
                                    <select name="show" id="" class="form-select">
                                        <option value="0">Không</option>
                                        <option value="1">Có</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mô tả công việc</label>
                                    <div id="quill-editor" class="mb-3" style="height: 300px;">
                                    </div>
                                    <textarea rows="3" class="mb-3 d-none" name="content" id="quill-editor-area"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            quill('quill-editor-area', '#quill-editor')
        });

        function quill(idEditorErea, idEditor) {
            if (document.getElementById(idEditorErea)) {
                var editor = new Quill(idEditor, {
                    theme: 'snow',
                    modules: {
                        toolbar: [

                            [{
                                'font': []
                            }],
                            [{
                                'size': ['small', false, 'large', 'huge']
                            }],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            [{
                                'header': [1, 2, false]
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['link', 'image', 'video'],
                            ['clean']
                        ]
                    }
                });
                editor.getModule('toolbar').addHandler('image', function() {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.click();

                    input.onchange = function() {
                        var file = input.files[0];
                        if (file) {
                            var formData = new FormData();
                            formData.append('image', file);

                            fetch('{{ config('services.recruitment_api.url') }}/api/upload-image-description', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(result => {
                                    const url = result.url;
                                    const range = editor.getSelection();
                                    editor.insertEmbed(range.index, 'image',
                                        url);
                                })
                                .catch(error => console.error('Error uploading image:', error));
                        }
                    };
                });
                var quillEditor = document.getElementById(idEditorErea);
                editor.on('text-change', function() {
                    quillEditor.value = editor.root.innerHTML;
                });
                quillEditor.addEventListener('input', function() {
                    editor.root.innerHTML = quillEditor.value;
                });
            }
        }

        async function getRecruitments(position, date) {
            const url = '{{ config('services.recruitment_api.url') }}/api/recruitments';

            if (position != '' || date != '') {
                url += '?position=' + position + '&date=' + date;
            }
            try {
                const response = await fetch(`${url}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!response.ok) {
                    alert(`Thông báo: Có lỗi xảy ra - ${response.status}`);
                    return;
                }
                const data = await response.json();
                if (!data.recruitments || data.recruitments.length === 0) {
                    document.getElementById('recruitment-list').innerHTML =
                        `<tr><td colspan="8">Không có dữ liệu</td></tr>`;
                    return;
                }
                let listRecruitElement = '';
                data.recruitments.forEach((item) => {
                    listRecruitElement += `<tr>
                        <td>${item.id}</td>
                        <td>${item.position_job}</td>
                        <td>${item.salary}</td>
                        <td>${item.time}</td>
                        <td>${item.quantity}</td>
                        <td>${item.expiration_date}</td>
                        <td>${item.show == 1
                            ? '<span class="text-success">Đang hiển thị</span>'
                            : '<span class="text-danger">Đang ẩn</span>'}</td>
                        <td>
                            @if (Auth::user()->hasPermissionOnPage('5', '4'))
                                <a href="{{ config('services.recruitment_api.url') }}/recruitment/detail/${item.id}" class="avtar avtar-xs btn-link-secondary">
                                    <i class="ti ti-eye f-20"></i>
                                </a>
                            @endif

                            @if (Auth::user()->hasPermissionOnPage('4', '4'))
                                <a href="#" class="avtar avtar-edit avtar-xs btn-link-secondary" data-id="${item.id}">
                                    <i class="ti ti-edit f-20"></i></a>
                            @endif
                            @if (Auth::user()->hasPermissionOnPage('6', '4'))
                                <a href="#" class="avtar avtar-delete avtar-xs btn-link-secondary" data-id="${item.id}" data-position="${item.position_job}">
                                    <i class="ti ti-trash f-20"></i>
                                </a>
                            @endif

                        </td>
                        <td>
                            @if (Auth::user()->hasPermissionOnPage('5', '4'))
                                <a href="#" class="avtar avtar-view-cv avtar-xs btn-link-secondary" data-id="${item.id}">
                                    <i class="fas fa-info-circle f-20"></i></a>
                            @endif
                        </td>
                    </tr>`;
                });

                document.getElementById('recruitment-list').innerHTML = listRecruitElement;
                onClickEdit();
                onClickDelete();
                onClickViewCV();
            } catch (error) {
                alert('Không thể kết nối đến server.');
            }
        }
        document.getElementById('add-recruitment').addEventListener('submit', async () => {
            event.preventDefault()
            try {
                tinymce.triggerSave();
                const form = event.target;
                const formData = new FormData(form);

                const response = await fetch('{{ config('services.recruitment_api.url') }}/api/recruitments', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    document.getElementById('notification').innerHTML = `<div class="alert alert-danger" role="alert">
                                                                        Thêm tin tuyển dụng không thành công
                                                                    </div>`;
                    setTimeoutAlert();
                    return;
                }

                getRecruitments('', '');
                document.getElementById('notification').innerHTML = `<div class="alert alert-success" role="alert">
                                                                        Thêm tin tuyển dụng thành công
                                                                    </div>`;
                setTimeoutAlert();

                form.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addRecruitmentModal'));
                modal.hide();
                tinymce.editors.forEach((editor) => editor.setContent(''));
            } catch (error) {
                alert('Có lỗi xảy ra ' + error);
            }
        });

        function onClickEdit() {
            document.querySelectorAll('.avtar-edit').forEach((element) => {
                element.addEventListener('click', async () => {
                    const id = element.dataset.id;
                    if (id) {
                        const recruitment = await getRecruitmentDetails(id);
                        if (recruitment) {
                            document.getElementById('dialog-edit').innerHTML = `<div class="modal fade" id="editRecruitmentModal" tabindex="-1" aria-labelledby="addRecruitmentModalLabel"
                                                                                aria-hidden="true">
                                                                                <div class="modal-dialog modal-xl">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h4 class="modal-title" id="addRecruitmentModalLabel">Chỉnh sửa tin tuyển dụng</h4>
                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <form method="post" id="update-recruitment">
                                                                                                <div class="row">
                                                                                                    <input type="hidden" name="_method" value="PUT" />
                                                                                                    <div class="col-12">
                                                                                                        <div class="mb-3">
                                                                                                            <label for="hoten" class="form-label">Vị trí tuyển dụng</label>
                                                                                                            <input type="text" class="form-control" name="position_job"
                                                                                                                placeholder="Vị trí tuyển dụng" value="${recruitment.position_job}" required >
                                                                                                        </div>
                                                                                                        <div class="mb-3">
                                                                                                            <label for="hoten" class="form-label">Mô tả lương</label>
                                                                                                            <input type="text" class="form-control" name="salary" placeholder="Mô tả lương"
                                                                                                                value="${recruitment.salary}" required>
                                                                                                        </div>
                                                                                                        <div class="mb-3">
                                                                                                            <label class="form-label">Hình thức</label>
                                                                                                            <input type="text" class="form-control" name="time"
                                                                                                                placeholder="Hình thức tuyển dụng" value="${recruitment.time}" required>
                                                                                                        </div>
                                                                                                        <div class="mb-3">
                                                                                                            <label class="form-label">Số lượng tuyển</label>
                                                                                                            <input type="text" class="form-control" name="quantity"
                                                                                                                placeholder="Số lượng tuyển dụng" value="${recruitment.quantity}" required>
                                                                                                        </div>
                                                                                                        <div class="mb-3">
                                                                                                            <label class="form-label">Ngày kết thúc</label>
                                                                                                            <input type="date" class="form-control" name="expiration_date" value="${recruitment.expiration_date}" required>
                                                                                                        </div>
                                                                                                        <div class="mb-3">
                                                                                                            <label class="form-label">Hiển thị</label>
                                                                                                            <select name="show" id="" class="form-select">
                                                                                                                <option value="0" ${recruitment.show == 0 ? 'selected' : ''}>Không</option>
                                                                                                                <option value="1" ${recruitment.show == 1 ? 'selected' : ''}>Có</option>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                        <div class="mb-3">
                                                                                                            <label class="form-label">Mô tả công việc</label>
                                                                                                            <div id="quill-editor-edit" class="mb-3" style="height: 300px;">
                                                                                                                ${recruitment.content}
                                                                                                            </div>
                                                                                                            <textarea rows="3" class="mb-3 d-none" name="content" id="quill-editor-area-edit"></textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <button type="submit" class="btn btn-info">Cập nhật</button>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>`;

                            await quill('quill-editor-area-edit', '#quill-editor-edit')
                            const modal = new bootstrap.Modal(document.getElementById(
                                'editRecruitmentModal'));
                            modal.show();

                            document.getElementById('update-recruitment').addEventListener('submit',
                                async () => {
                                    event.preventDefault();
                                    await updateRecruiment(id);
                                })
                        }
                    }

                })
            })
        }

        function onClickDelete() {
            document.querySelectorAll('.avtar-delete').forEach((element) => {
                element.addEventListener('click', async () => {
                    const id = element.dataset.id;
                    const positionJob = element.dataset.position;

                    if (id) {
                        document.getElementById('dialog-delete').innerHTML = `<div class="modal fade" id="delete-recruitment-modal" tabindex="-1"
                                                                                aria-labelledby="deleteRecruitmentLabel" aria-hidden="true">
                                                                                <div class="modal-dialog">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="deleteRecruitmentLabel">Xác nhận xóa</h5>
                                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                                                aria-label="Close"></button>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <p>Bạn có muốn xóa tin tuyển dụng <strong>${positionJob}</strong>
                                                                                                không?</p>
                                                                                        </div>
                                                                                        <div class="modal-footer">
                                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                                            <button type="button" class="btn btn-info" id="btn-delete-recruitment">Xóa</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>`;
                        const modal = new bootstrap.Modal(document.getElementById(
                            'delete-recruitment-modal'));
                        modal.show();
                        document.getElementById('btn-delete-recruitment').addEventListener('click',
                            () => {
                                deleteRecruitment(id);
                                modal.hide();
                            })
                    }
                })
            })
        }

        function onClickViewCV() {
            document.querySelectorAll('.avtar-view-cv').forEach((element) => {
                element.addEventListener('click', async () => {
                    const id = element.dataset.id;
                    if (id) {
                        getCVOfRecuitment(id);
                    }


                    const modal = new bootstrap.Modal(document.getElementById('view-cv'));
                    modal.show();
                });
            })
        }
        async function deleteCV(id, recruitmentId) {
            try {
                const response = await fetch(
                    `{{ config('services.recruitment_api.url') }}/api/delete-application/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                        }
                    });
                if (!response.ok) {
                    document.getElementById('notification').innerHTML = `<div class="alert alert-danger" role="alert">
                                                                        Có lỗi xảy ra
                                                                    </div>`;
                    setTimeoutAlert();
                    return;
                }
                await getCVOfRecuitment(recruitmentId);
                document.getElementById('notification').innerHTML = `<div class="alert alert-success" role="alert">
                                                                        Xóa tin tuyển dụng thành công
                                                                    </div>`;
                setTimeoutAlert();

            } catch (error) {
                console.log("Có lỗi xảy ra 1", error);
                return;
            }
        }
        async function getCVOfRecuitment(id) {
            try {
                const response = await fetch(`{{ config('services.recruitment_api.url') }}/api/get-cvs/${id}`, {
                    method: "GET",
                    headers: {
                        'accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    alert('Có lỗi xảy ra');
                    return;
                }
                const data = await response.json();
                let cvListElement = '';
                data.cvs.forEach((item) => {
                    cvListElement += `
                                <tr>
                                    <td>${item.full_name}</td>
                                    <td>${item.phone_number}</td>
                                    <td>${item.email}</td>
                                    <td>${item.application_date}</td>
                                    <td>
                                        <a href="{{ config('services.recruitment_api.url') }}${item.cv_path}" target="_blank" title="Xem CV" class="avtar avtar-xs btn-link-secondary">
                                            <i class="ti ti-eye f-20"></i>
                                        </a>
                                        <a href="{{ config('services.recruitment_api.url') }}/api/download-cv${item.cv_path}" title="Tải xuống CV" class="avtar avtar-xs btn-link-secondary">
                                            <i class="fas fa-cloud-download-alt f-20"></i>
                                        </a>
                                        
                                        <a href="#" data-id=${item.id} title="Tải xuống CV" class="avtar avtar-xs btn-link-secondary btn-delete-cv">
                                            <i class="ti ti-trash f-20"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                });
                document.getElementById('cv-list').innerHTML = cvListElement;

                document.querySelectorAll('.btn-delete-cv').forEach((element) => {
                    element.addEventListener('click', async () => {
                        const idCV = element.dataset.id;
                        await deleteCV(idCV, id);
                    });
                })
            } catch (error) {
                console.log("Có lỗi xảy ra 1", error);
                return;
            }

        }

        function setTimeoutAlert() {
            setTimeout(function() {
                const alert = document.getElementsByClassName("alert")[0];
                if (alert) {
                    alert.classList.add("d-none");
                }
            }, 3000);
        }
        async function getRecruitmentDetails(id) {
            try {
                const response = await fetch(`{{ config('services.recruitment_api.url') }}/api/recruitments/${id}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                if (!response.ok) {
                    document.getElementById('notification').innerHTML = `<div class="alert alert-danger" role="alert">
                                                                        Có lỗi xảy ra
                                                                    </div>`;
                    setTimeoutAlert();
                    return null;
                }
                const data = await response.json();

                return data.recruitment;
            } catch (error) {
                console.log("Có lỗi xảy ra 1", error);
                return null;
            }
        }
        async function updateRecruiment(id) {
            try {
                const form = event.target;
                const formData = new FormData(form);


                const response = await fetch(`{{ config('services.recruitment_api.url') }}/api/recruitments/${id}`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (!response.ok) {
                    document.getElementById('notification').innerHTML = `<div class="alert alert-danger" role="alert">
                                                                        Cập nhât tin tuyển dụng không thành công
                                                                    </div>`;
                    setTimeoutAlert();
                    return;
                }

                getRecruitments('', '');
                document.getElementById('notification').innerHTML = `<div class="alert alert-success" role="alert">
                                                                        Cập nhật tin tuyển dụng thành công
                                                                    </div>`;
                setTimeoutAlert();

                form.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('editRecruitmentModal'));
                modal.hide();
            } catch (error) {
                alert('Có lỗi xảy ra: ' + error);
            }
        }
        async function deleteRecruitment(id) {
            try {
                const response = await fetch(`{{ config('services.recruitment_api.url') }}/api/recruitments/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                if (!response.ok) {
                    document.getElementById('notification').innerHTML = `<div class="alert alert-danger" role="alert">
                                                                        Có lỗi xảy ra
                                                                    </div>`;
                    setTimeoutAlert();
                    return;
                }
                await getRecruitments('', '');
                document.getElementById('notification').innerHTML = `<div class="alert alert-success" role="alert">
                                                                        Xóa tin tuyển dụng thành công
                                                                    </div>`;
                setTimeoutAlert();

            } catch (error) {
                console.log("Có lỗi xảy ra 1", error);
                return;
            }
        }

        getRecruitments('', '');
    </script>
@endsection
