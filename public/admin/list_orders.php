<?php
include_once __DIR__ . "../../../partials/admin_boostrap.php";
session_start();
require_once __DIR__ . '../../../partials/connect.php';

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}
;

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_orders = $pdo->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_orders->execute([$delete_id]);
    header('location:list_orders.php');
}

if (isset($message)) {
    foreach ($message as $message) {
        // echo '<script>alert(" ' . $message . ' ");</script>';
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($message) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>';
    }
}
?>

<title>List Orders</title>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        include_once __DIR__ . "../../../partials/admin_header_column.php";
        ?>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php
                include_once __DIR__ . "../../../partials/admin_header.php";
                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Danh sách đơn hàng</h1>
                        <a href="status_orders.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fa-solid fa-pen-to-square"></i> Trạng thái đơn hàng</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">STT</th>
                                    <th scope="col">Fullname</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Date Place</th>
                                    <th scope="col">Total Products</th>
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Payment Method</th>
                                    <th scope="col">Payment Status</th>
                                    <th scope="col">Edit</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php
                                $i = 1;
                                $select_orders = $pdo->prepare("SELECT * FROM `orders`");
                                $select_orders->execute();
                                if ($select_orders->rowCount() > 0) {
                                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                <tr>
                                    <td class="pt-4">
                                        <b><?= htmlspecialchars($i++); ?></b>
                                    </td>

                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['name']); ?>
                                    </td>

                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['email']); ?>
                                    </td>

                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['number']); ?>
                                    </td>

                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['address']); ?>
                                    </td>

                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['placed_on']); ?>
                                    </td>
                                    
                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['future_date']); ?>
                                    </td>

                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['total_products']); ?>
                                    </td>

                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['total_price']); ?>
                                    </td>
                                    <td class="pt-4">
                                        <?= htmlspecialchars($fetch_orders['method']); ?>
                                    </td>
                                    <td class="pt-4 text-success">
                                        <?= htmlspecialchars($fetch_orders['payment_status']); ?>
                                    </td>

                                    <td class="pt-4">
                                        <a class="btn btn-primary"
                                            href="edit_orders.php?update=<?= htmlspecialchars($fetch_orders['id']); ?>"
                                            class="option-btn">edit</a>
                                    </td>

                                    <td class="pt-4">
                                        <a class="btn btn-danger" data-id="<?= htmlspecialchars($fetch_orders['id']); ?>"
                                            data-toggle="modal" data-target="#deleteConfirmationModal">delete</a>
                                    </td>

                                </tr>
                            </tbody>
                            <?php
                                    }
                                }
                                ?>
                        </table>
                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>


    <!-- Delete Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa dòng này?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <a id="deleteLink" href="" class="btn btn-danger">Xóa</a>
                </div>
            </div>
        </div>
    </div>

    
    <script>
    // JavaScript code to handle delete from modal
    $(document).ready(function() {
        $('#deleteConfirmationModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var Id = button.data('id');

            // Set the delete button link with productId
            var deleteLink = 'list_orders.php?delete=' + Id;
            $('#deleteLink').attr('href', deleteLink);
        });
    });
    </script>

    <?php
    include_once __DIR__ . '../../../partials/admin_footer.php';