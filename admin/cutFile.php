<table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    
                      if (isset($_GET['pageno'])){
                        $pageno = $_GET['pageno'];
                      }else{
                        $pageno = 1;
                      }
                      $numofrecs = 3;
                      $offset = ($pageno - 1) * $numofrecs;

                      if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                        $sql = "SELECT * FROM posts ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();

                        $total_pages = ceil(count($rawResult) / $numofrecs);

                        $sql = "SELECT * FROM posts LIMIT $offset,$numofrecs";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }else{
                        $searchKey = $_COOKIE['search'] ? $_COOKIE['search'] : $_POST['search'];

                        $sql = "SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rawResult = $stmt->fetchAll();

                        $total_pages = ceil(count($rawResult) / $numofrecs);

                        $sql = "SELECT * FROM posts WHERE title LIKE '%$searchKey%' LIMIT $offset,$numofrecs";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll();
                      }                     

                      if ($result) {
                      foreach ($result as $value) {
                    ?>
                    <tr>
                      <td><?php echo escape($value['id']); ?></td>
                      <td><?php echo escape($value['title']); ?></td>
                      <td>
                        <?php echo escape(substr($value['content'],0,100)); ?>
                      </td>
                      <td>
                        <div class="btn btn-group">
                          <div class="container">
                            <a href="edit.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a onclick="return confirm('Are you sure want to delete?');" href="delete.php?id=<?php echo $value['id'];?>" type="button" class="btn btn-danger">Delete</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php
                        }
                      }
                    ?>
                  </tbody>
                </table><br>
                <!-- Pagination Start -->
                <nav aria-label="Page navigation example" class="float-right">
                  <ul class="pagination">
                    <li class="page-item">
                      <a class="page-link" href="?pageno=1">First</a>
                    </li>
                    <li class="page-item <?php if($pageno <=1 ){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
                    </li>
                    <li class="page-item active">
                      <a class="page-link " href="#"><?php echo $pageno; ?></a>
                    </li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                    </li>
                    <li class="page-item">
                      <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
                    </li>
                  </ul>
                </nav>
                <!-- Pagination End -->