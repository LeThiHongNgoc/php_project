<?php

include_once __DIR__ . '/../partials/boostrap.php';

include_once __DIR__ . '/../partials/header.php';

require_once __DIR__ . '/../partials/connect.php';

// session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if (isset($_POST['update_order'])) {

    $order_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
    $update_orders = $pdo->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_orders->execute([$update_payment, $order_id]);
    $message[] = 'payment has been updated!';
    $placed_on = date('d-M-Y');
}
;
if (isset($message)) {
    foreach ($message as $message) {
      // echo '<script>alert(" ' . $message . ' ");</script>';
      echo '<div class="alert alert-warning alert-dismissible fade show col-4 offset-4" role="alert" tabindex="-1">
                ' . htmlspecialchars($message) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
  };
?>

<title>My orders</title>
</head>
<section id="cart" class="pt-5">
    <div class="container">
        <div class="title text-center mt-5 pt-5">
            <h2 class="position-relative d-inline-block">My Orders</h2>
            <hr>
        </div>

        <?php
        $total = 0;
        $sub_total = 0;
        $select_orders = $pdo->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $select_orders->execute([$user_id]);
        ?>
        <?php if ($select_orders->rowCount() > 0) { ?>
            <table class="mt-5 pt-5">
                <tr>
                    <th>Product</th>
                    <th>Order date</th>
                    <th>Delivery date</th>
                    <th>Payment method</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                </tr>
                <?php
                $select_orders = $pdo->prepare("SELECT * FROM `orders`");
                $select_orders->execute();
                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {

                        ?>
                        <div>
                            <tr>

                                <td>
                                    <div class="product-info">
                                        <!-- <img src="admin/uploaded_img/<?= $fetch_orders['image']; ?>" alt=""> -->
                                        <div>
                                            <div class=" name text-capitalize">
                                                <?= $fetch_orders['total_products']; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="product-price">
                                        <div class="price">
                                            <?= $fetch_orders['placed_on']; ?>
                                        </div>
                                    </span>
                                </td>

                                <td>
                                    <span class="product-price">
                                        <div class="price">
                                            <?= $fetch_orders['check_date']; ?>
                                        </div>
                                    </span>
                                </td>

                                <td>
                                    <span class="product-price">
                                        <div class="price">
                                            <?= $fetch_orders['method']; ?>
                                        </div>
                                    </span>
                                </td>

                                <td>
                                    <span class="product-price">
                                        <div class="price">
                                            <?= $fetch_orders['total_price']; ?>$
                                        </div>
                                    </span>
                                </td>

                                <td>
                                    <span class="product-price">
                                        Pending confirmation
                                    </span>
                                </td>
                            </tr>
                        </div>
                        <?php

                    }


                    ?>
                </table>

                <?php
                }
        } else { ?>
            <div class="text-center pt-3">
                <h6 class="position-relative d-inline-block">No item found </h6>
                <div>
                    <a type="submit" class="buy-btn text-capitalize text-decoration-none mt-3" name="order now"
                        href="cart.php">order now</a>
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
