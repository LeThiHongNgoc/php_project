<?php

include_once __DIR__ . '../../partials/boostrap.php';

include_once __DIR__ . '../../partials/header.php';

require_once __DIR__ . '../../partials/connect.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}
;

    $order_id = $_POST['order_id'];

    // Kiểm tra xem đơn đặt hàng có tồn tại không
    $check_order_query = $pdo->prepare("SELECT * FROM `orders` WHERE id = ? AND user_id = ?");
    $check_order_query->execute([$order_id, $user_id]);
    $order_data = $check_order_query->fetch(PDO::FETCH_ASSOC);

    if ($order_data) {
        // Cập nhật cột payment_status thành 'cancel'
        $update_order_query = $pdo->prepare("UPDATE `orders` SET payment_status = 'cancel', cancel_date = current_timestamp() WHERE id = ?");
        $update_order_query->execute([$order_id]);
        
        echo "Đơn hàng đã được hủy thành công!";
    } else {
        echo "Không tìm thấy đơn đặt hàng!";
    }
?>

<title>My order</title>
</head>

<!-- Cart -->
<section id="cart" class="pt-5">
    <div class="container">
        <div class="title text-center mt-5 pt-5">
            <h2 class="position-relative d-inline-block">My order</h2>
            <hr>
        </div>
        <?php
        $total = 0;
        $sub_total = 0;

        $sql = "SELECT orders.id as order_id, orders.total_products, orders.total_price, orders.placed_on, orders.check_date, orders.method, orders.payment_status, 
                products.id as product_id, products.name as product_name, products.price as product_price, products.image as product_image, orders_details.quantity as product_quantity
                FROM orders
                JOIN orders_details ON orders.id = orders_details.order_id
                JOIN products ON orders_details.pid = products.id
                WHERE orders.user_id = :user_id";

        $select_orders = $pdo->prepare($sql);
        $select_orders->execute([':user_id' => $user_id]);


                    <div class="col-12">
                        <!-- Hiển thị sản phẩm trong đơn đặt hàng -->
                        <table class="mt-5 pt-5">
                            <tr>
                                <th class="col">Product Image</th>
                                <th class="col">Product Name</th>
                                <th class="col">Product Price</th>
                                <th class="col">Product Quantity</th>
                            </tr>

                            <?php
                            // Lấy danh sách sản phẩm trong đơn đặt hàng dựa trên mã đơn hàng
                            $order_id = $fetch_orders['order_id'];
                            $select_products = $pdo->prepare("SELECT products.image as product_image, products.name as product_name, products.price as product_price, orders_details.quantity as product_quantity
                                                             FROM orders_details
                                                             JOIN products ON orders_details.pid = products.id
                                                             WHERE orders_details.order_id = ?");
                            $select_products->execute([$order_id]);

                            while ($product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <tr>
                                    <td>
                                        <img src="admin/uploaded_img/<?= $product['product_image']; ?>" alt="">
                                    </td>
                                    <td>
                                        <?= $product['product_name']; ?>
                                    </td>
                                    <td>
                                        <?= $product['product_price']; ?>
                                    </td>
                                    <td>
                                        <?= $product['product_quantity']; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>

                        </table>
                    </div>

                    <div class="mt-5 col-4 text-start offset-9">
                        <p>Placed On:
                            <?= $fetch_orders['placed_on']; ?>
                        </p>
                        <p>Total Price:
                            $
                            <?= $fetch_orders['total_price']; ?>
                        </p>
                        <p>Payment Method:
                            <?= $fetch_orders['method']; ?>
                        </p>
                        <p>Payment Status:
                            <?= $fetch_orders['payment_status']; ?>
                        </p>
                        <td>
                            <!-- Trong HTML của trang hiển thị đơn đặt hàng -->
                            <form action="user_my_order.php" method="POST">
                                <input type="hidden" name="order_id" value="<?= $fetch_orders['order_id'] ?>">
                                <button type="submit" name="cancel_order" class="buy-btn">Cancel</button>
                            </form>
                        </td>
                    </div>

                </div>

                <?php
            }
        } else {
            ?>
            <div class="text-center pt-3">
                <h6 class="position-relative d-inline-block">No items found </h6>
                <div>
                    <a type="submit" class="buy-btn text-capitalize text-decoration-none mt-3" name="order now" href="cart.php">Order Now</a>
                </div>
            </div>
            <!-- echo '<p class="empty" >no orders placed yet!</p>'; -->
            <?php
        }
        ?>
    </div>
</section>

<?php
include_once __DIR__ . '/../partials/footer.php';
