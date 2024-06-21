@extends('admin.admin_master')
@section('admin')
    <div class="page-wrapper">
        <div class="page-content">

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">

                <div class="col">
                    <div class="card radius-10 bg-gradient-deepblue">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 id="total-orders" class="mb-0 text-white">Loading...</h5>
                                <div class="ms-auto">
                                    <i class='bx bx-cart fs-3 text-white'></i>
                                </div>
                            </div>
                            <div class="progress my-3 bg-light-transparent" style="height:3px;">
                                <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Total Orders</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-orange">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 id="total-revenue" class="mb-0 text-white">Loading...</h5>
                                <div class="ms-auto">
                                    <i class='bx bx-dollar fs-3 text-white'></i>
                                </div>
                            </div>
                            <div class="progress my-3 bg-light-transparent" style="height:3px;">
                                <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Total Revenue</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ohhappiness">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 id="total-visitors" class="mb-0 text-white">Loading...</h5>
                                <div class="ms-auto">
                                    <i class='bx bx-group fs-3 text-white'></i>
                                </div>
                            </div>
                            <div class="progress my-3 bg-light-transparent" style="height:3px;">
                                <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Visitors</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-gradient-ibiza">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 id="total-reviews" class="mb-0 text-white">Loading...</h5>
                                <div class="ms-auto">
                                    <i class='bx bx-envelope fs-3 text-white'></i>
                                </div>
                            </div>
                            <div class="progress my-3 bg-light-transparent" style="height:3px;">
                                <div class="progress-bar bg-white" role="progressbar" style="width: 55%" aria-valuenow="25"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <p class="mb-0">Reviews</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row">

                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="mb-0">Orders Summary</h5>
                            </div>
                            <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Product Name </th>
                                        <th> Invoice No </th>
                                        <th> Quantity </th>
                                        <th> Total Price </th>
                                        <th> Order Date </th>
                                        <th> Order Stauts </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($orders as $item)
                                        <tr style="height: 65px">
                                            <td>{{ $i++ }}</td>
    
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ $item->invoice_no }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->total_price }}</td>
                                            <td>{{ $item->order_date }}</td>
                                            <td> <strong><span class="badge rounded-pill 
                                                @if($item->order_status == 'Pending')
                                                    bg-light-warning text-warning
                                                @elseif($item->order_status == 'Processing')
                                                    bg-light-info text-info
                                                @elseif($item->order_status == 'Complete')
                                                    bg-light-success text-success
                                                @endif
                                                w-100">{{ $item->order_status }}</span> </strong>
                                            </td>
                                        </tr>
                                    @endforeach
    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route('admin.total.orders') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-orders').textContent = data.total_orders;
                })
                .catch(error => {
                    console.error('Error fetching total orders:', error);
                    document.getElementById('total-orders').textContent = 'Error';
                });

            fetch('{{ route('admin.total.revenue') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-revenue').textContent = data.total_revenue.toFixed(2) + 'DA';
                })
                .catch(error => {
                    console.error('Error fetching total revenue:', error);
                    document.getElementById('total-revenue').textContent = 'Error';
                });

            fetch('{{ route('admin.total.visitors') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-visitors').textContent = data.total_visitors;
                })
                .catch(error => {
                    console.error('Error fetching total visitors:', error);
                    document.getElementById('total-visitors').textContent = 'Error';
                });

            fetch('{{ route('admin.total.reviews') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-reviews').innerText = data.total_reviews;
                })
                .catch(error => console.error('Error fetching total reviews:', error));
        });
    </script>

@endsection
