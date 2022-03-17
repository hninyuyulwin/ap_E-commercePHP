<?php 
    require_once "header.php";
?>
    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                <?php if (isset($_SESSION['cart'])): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $total = 0;
                                foreach ($_SESSION['cart'] as $key => $qty) :
                                    $id = str_replace('id','',$key);

                                    $stmt = $pdo->prepare("SELECT * FROM products where id=".$id);
                                    $stmt->execute();
                                    $res = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $total += $res['price'] * $qty;
                            ?>                            
                            <tr>
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="images/<?php echo $res['image']; ?>" width=100 height=110>
                                        </div>
                                        <div class="media-body">
                                            <p><?php echo $res['name']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5><?php echo $res['price'].' MMK'; ?></h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="text" name="qty" value="<?php echo $qty;  ?>"
                                            class="input-text qty" readonly>
                                    </div>
                                </td>
                                <td>
                                    <h5><?php echo $res['price'] * $qty." MMK"; ?></h5>
                                </td>
                                <td>
                                    <a href="clear_item.php?id=<?php echo $res['id']; ?>" class="btn btn-sm btn-warning">Clear</a>
                                </td>
                            </tr>       
                            <?php
                                endforeach ;
                            ?>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5><?php echo $total." MMK"; ?></h5>
                                </td>
                                <td></td>
                            </tr>
                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" href="clearall.php">Clear All</a>
                                        <a class="primary-btn" href="index.php">Continue Shopping</a>
                                        <a class="gray_btn" href="sale_order.php">Order Submit</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif ?>
                    
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

    <!-- start footer Area -->
<?php 
    require_once "footer.php";
?>