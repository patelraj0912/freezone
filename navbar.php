<div class="p-3 m-0 border-0 bd-example m-0 border-0">
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow-lg p-3 mb-5 rounded">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">FreeZone</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            <a class="nav-link active" href="my_innovations.php">My Innovation</a>
          </div>
        </div>
        <div class="d-flex">
          <a href="profile.php" class="nav-link active">
            <div class="profile-image fs-3">
              <?php
                  $username = $_SESSION['username'];
                  $fstchar = substr($username, 0,1);
                  echo ucfirst($fstchar);
              ?>
            </div>
          </a>
        </div>
      </div>
    </nav>
</div>

