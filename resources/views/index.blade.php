<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <title>User Page</title>
</head>

<body>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="userDetails" aria-labelledby="userDetailsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="userDetailsLabel">User Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <p><strong>ID:</strong> <span id="detail-id"></span></p>
            <p><strong>Name:</strong> <span id="detail-name"></span></p>
            <p><strong>Email:</strong> <span id="detail-email"></span></p>
        </div>
    </div>

    <div class="container-lg">
        <br>
        <div class="row justify-content-between">
            <div class="col-4">
                <h1>User Page</h1>
                <p>Load time: <span id="load-time">0</span> ms</p>

            </div>
            <div class="col-4">
                <input type="text" name="search" placeholder="Search ..." class="form-control"
                    onkeyup="fetchUsers(1,'asc', this.value)">
            </div>
        </div>
        <br>
        <div class="row justify-content-end">
            <div class="col-4">
                <label for="filter">Filter</label>
                <select name="filter" id="filter" class="form-control" onchange="fetchUsers(1,this.value)">
                    <option value="asc">ASC</option>
                    <option value="desc">DESC</option>
                </select>
            </div>
        </div>
        <br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item first_page_url btn-primary btn" onclick="fetchUsers(1)">First Page</li>
                &nbsp;
                <li class="page-item last_page_url btn-primary btn" onclick="">Last Page</li>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function fetchUsers(page = 1, sort = "asc", search = '') {
            if (search) {
                search = `&search=${search}`;
            } else {
                search = '';
            }
            if (sort) {
                sort = `&sort_order=${sort}`;
            } else {
                sort = '';
            }
            const startTime = Date.now();
            const url = "{{ url('/api') }}";
            $.ajax({
                url: `${url}?page=${page}${search}${sort}`,
                method: 'GET',
                success: function(response) {
                    const users = response.data.data;
                    let tableRows = '';
                    users.forEach(user => {
                        tableRows += `<tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td><button class="btn btn-sm btn-info" onclick='showUserDetails(${JSON.stringify(user)})'>View</button></td>

                            </tr>`;
                    });
                    $('table tbody').html(tableRows);
                    $('.last_page_url').attr('onclick', `fetchUsers(${response.data.last_page})`);
                    $('.pagination').find('.page-number').remove();
                    const endTime = Date.now();
                    let duration = Math.round(endTime - startTime);
                    $("#load-time").text(duration);
                    for (let i = 1; i <= response.data.last_page; i++) {
                        $(`<li class="page-item page-number btn btn-outline-secondary mx-1" onclick="fetchUsers(${i}, '${sort}', '${search}')">${i}</li>`).insertBefore('.last_page_url');
                    }
                },
                error: function(error) {
                    alert('Error fetching users. Please try again later.', error+"");
                    console.error('Error fetching users:', error);
                }
            });
        }

        function showUserDetails(user) {
            $('#detail-id').text(user.id);
            $('#detail-name').text(user.name);
            $('#detail-email').text(user.email);

            let offcanvasEl = document.getElementById('userDetails');
            let bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
            bsOffcanvas.show();
        }

        $(document).ready(function() {
            fetchUsers();
        });
    </script>
</body>

</html>
